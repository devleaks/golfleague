<?php

namespace backend\modules\start\controllers;

use Yii;
use backend\controllers\DefaultController as GolfLeagueController;
use common\models\Competition;
use common\models\Flight;
use common\models\Registration;
use common\models\Team;
use common\models\TeesForm;
use common\models\flight\BuildFlightChrono;
use common\models\flight\BuildFlightForTeam;
use common\models\flight\BuildFlightStandard;
use common\models\search\CompetitionSearch;
use common\models\search\FlightSearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;

/**
 * FlightController implements the CRUD actions for Flight model.
 */
class FlightController extends GolfLeagueController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Flight models.
     * @return mixed
     */
    public function actionIndex($id)
    {
		$competition = $this->findCompetition($id);

		$flights = $competition->getFlights();

		$regs_ok = [];
		foreach($flights->each() as $flight) {
			foreach($flight->getRegistrations()->each() as $registration) {
				$regs_ok[] = $registration->id;
			}
		}

		// collect new registrations not in existing flights
		$registrations = $competition->getRegistrations()->andWhere(['not', ['registration_id' => $regs_ok]]);	

        return $this->render('flights', [
			'competition' => $competition,
            'flights' => $flights,
            'registrations' => $registrations,
        ]);
    }

	
    /**
     * Lists all Registration models for approval.
     * @return mixed
     */
    public function actionCompetitions()
    {
        $searchModel = new CompetitionSearch();
        $query = Competition::find();
        $query->andWhere(['status' => Competition::STATUS_OPEN]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('competitions', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Make or Lists Flight models for a competition.
     * @return mixed
     */
	private function getFlights($competition) {
		$flights = $competition->getFlights()->orderBy('position')->all();		
		if(!$flights) {// need to make them
			$method = $competition->isTeamCompetition() ? new BuildFlightForTeam() : new BuildFlightChrono(); // later, method will be chosen from list of value
			$method->execute($competition);
			$flights = $competition->getFlights()->orderBy('position')->all();		
		} else { // we got flights, but may be some players registered after the last time we arranged flights
			$newRegs = $competition->getRegistrations()
						->andWhere(['status' => Registration::STATUS_REGISTERED])
						->andWhere(['flight_id' => null])
						;
			// build additional flights with new registrations
			if($newRegs->exists()) {
				if($competition->isTeamCompetition())
					BuildFlightForTeam::addFlights($competition, $newRegs);
				else
					BuildFlightStandard::addFlights($competition, $newRegs);
				$flights = $competition->getFlights()->orderBy('position')->all();		
			}
		}
		return $flights;
	}

	/**
	 * Build flight and place registration in it. Create flight if necessary.
	 * @return flight_id updated or created
	 */
	private function makeFlight($flight_str, $competition) {
		$competition_date = substr($competition->start_date, 0, 10);
		$flight_arr = explode('-', $flight_str->id); // flight-123
		$flight = Flight::findOne($flight_arr[1]);
		if(!$flight) { // need to create it
			$flight = new Flight();
			$flight->competition_id = $competition->id;
		} else { // remove existings
			$flight->cleanRegistrations();
		}
		$flight->position = $flight_str->position;
		Yii::trace($competition_date . ' ' . $flight_str->start_time . ':00'  , 'FlightController::makeFlight');
		$flight->start_time = $competition_date . ' ' . $flight_str->start_time . ':00';
		$flight->save();
		// add currents
		if($competition->isTeamCompetition()) {
			foreach($flight_str->registrations as $registration_str) {
				$registration_arr = explode('-', $registration_str); // registration-456
				$team = Team::findOne($registration_arr[1]);
				if($team) {
					foreach($team->getRegistrations()->each() as $registration) {
						$registration->flight_id = $flight->id;
						$registration->save();
					}
				}
			}
		} else {
			foreach($flight_str->registrations as $registration_str) {
				$registration_arr = explode('-', $registration_str); // registration-456
				$registration = Registration::findOne($registration_arr[1]);
				if($registration) {
					$registration->flight_id = $flight->id;
					$registration->save();
				}
			}
		}
		return $flight->id;
	}


    /**
     * Displays and/or update Flight models for a competition.
     * @return mixed
     */
    public function actionCompetition($id)
    {
		$competition = Competition::findOne($id);
		if(!$competition)
        	throw new NotFoundHttpException('The requested page does not exist.');

		if($competition->isTeamCompetition() && !$competition->isTeamOk()) {
			Yii::$app->session->setFlash('error', Yii::t('igolf', 'Teams for competition not completed.'));
			return $this->redirect(Yii::$app->request->referrer);;
		}

		//should check that competition exists or exit.
		$savedflights = Yii::$app->request->post('flights');
		if($savedflights) {
			$flights = json_decode($savedflights);

			$oldFlights = [];
			$ff = $competition->getFlights()->all();
			if($ff)
				foreach($ff as $f)
					$oldFlights[$f->id] = $f;
				
			foreach($flights as $flight) { // update or create each flight
				$id = $this->makeFlight($flight, $competition);
				unset($oldFlights[$id]); // flight still used, remove from "oldFlights"
			}
			foreach($oldFlights as $flight) { // delete unused flights ($oldFlights minus those still in use.)
				$flight->cleanRegistrations(true);
			}

			if($flights) // need a better test...
				Yii::$app->session->setFlash('success', Yii::t('igolf', 'Flight saved sucessfully.'));
			else
				Yii::$app->session->setFlash('error', Yii::t('igolf', 'There was a problem saving flights.'));
		}

		$flights = $this->getFlights($competition);
		if(!$flights) // no registration
        	throw new NotFoundHttpException('There is no registration for this competition.');

        return $this->render('flights', [
			'competition' => $competition,
            'flights' => $flights,
        ]);

    }

    /**
     * Displays and/or update Flight models for a competition.
     * @return mixed
     */
    public function actionPublish($id)
    {
		$competition = Competition::findOne($id);
		if(!$competition)
        	throw new NotFoundHttpException('The requested page does not exist.');

		$flights = $competition->getFlights();
		
		foreach($flights->each() as $flight) {
			$registrations = $flight->getRegistrations();
			foreach($registrations->each() as $registration) {
				$registration->status = Registration::STATUS_CONFIRMED;
				$registration->save();
			}
		}
				
		$competition->status = Competition::STATUS_READY;
		$competition->save();
		if(count($competition->errors)>0)
			Yii::$app->session->setFlash('danger', Yii::t('igolf', 'Error(s): {0}', [VarDumper::dumpAsString($competition->errors, 4, true)]));

        return $this->render('list', [
			'model' => $competition,
        ]);
    }

    /**
     * Displays a single Flight model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single Flight model.
     * @param integer $id
     * @return mixed
     */
    public function actionList($id)
    {
		$competition = Competition::findOne($id);
		if(!$competition)
        	throw new NotFoundHttpException('The requested page does not exist.');

        return $this->render('list', [
			'model' => $competition,
        ]);
    }

    /**
     * Updates an existing Flight model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Flight model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Flight model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Flight the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Flight::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Flight model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Flight the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCompetition($id)
    {
        if (($model = Competition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
