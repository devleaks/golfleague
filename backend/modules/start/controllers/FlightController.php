<?php

namespace backend\modules\start\controllers;


use common\models\Competition;
use common\models\Flight;
use common\models\Match;
use common\models\Registration;
use common\models\Team;
use common\models\flight\BuildFlightChrono;
use common\models\flight\BuildFlightForTeam;
use common\models\flight\BuildFlightForMatch;
use common\models\flight\BuildFlightStandard;
use common\models\search\CompetitionSearch;
use common\models\search\FlightSearch;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;

/**
 * FlightController implements the CRUD actions for Flight model.
 */
class FlightController extends GroupController
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
     * Make or Lists Flight models for a competition.
     * @return mixed
     */
	private function getFlights($competition) {
		$flights = $competition->getFlights()->orderBy('position')->all();
		if(!$flights) {// need to make them

			$method = null;

			if($competition->isMatchCompetition())
				$method = new BuildFlightForMatch();
			else if($competition->isTeamCompetition())
				$method = new BuildFlightForTeam();
			else
				$method = new BuildFlightChrono(); // later, method will be chosen from list of value
				
			$method->execute($competition);
			
			$flights = $competition->getFlights()->orderBy('position')->all();		
		} else { // we got flights, but may be some players registered after the last time we arranged flights
			$newRegs = $competition->getRegistrationsNotIn(Flight::TYPE_FLIGHT);
			// build additional flights with new registrations
			if($newRegs->exists()) {
				if($competition->isMatchCompetition())
					BuildFlightForMatch::addFlights($competition, $newRegs);
				else if($competition->isTeamCompetition())
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
			$flight->group_type = Flight::TYPE_FLIGHT;
			$flight->name = 'Flight '.$competition->id.'.';
		} else { // remove existings
			$flight->clean();
		}
		$flight->position = $flight_str->position;
		Yii::trace($competition_date . ' ' . $flight_str->start_time . ':00'  , 'FlightController::makeFlight');
		$flight->start_time = $competition_date . ' ' . $flight_str->start_time . ':00';
		$flight->start_hole = $competition->start_hole;
		$flight->save();
		Yii::trace(print_r($flight->attributes, true) , 'FlightController::makeFlight');

		// add currents
		if($competition->isTeamCompetition()) {
			foreach($flight_str->registrations as $registration_str) {
				$registration_arr = explode('-', $registration_str); // registration-456
				$team = Team::findOne($registration_arr[1]);
				if($team) {
					foreach($team->getRegistrations()->each() as $registration) {
						//Yii::trace('Tadding '.$registration->id , 'FlightController::makeFlight');
						$flight->add($registration);
					}
				}
			}
		} else if($competition->isMatchCompetition()) { // matchplay
			foreach($flight_str->registrations as $match_str) {
				$match_arr = explode('-', $match_str); // match-456
				if($match = Match::findOne($match_arr[1])) {
					foreach($match->getRegistrations()->each() as $registration) {
						//Yii::trace('Madding '.$registration->id , 'FlightController::makeFlight');
						$flight->add($registration);
					}
				}
			}
		} else {
			foreach($flight_str->registrations as $registration_str) {
				$registration_arr = explode('-', $registration_str); // registration-456
				$registration = Registration::findOne($registration_arr[1]);
				if($registration) {
					//Yii::trace('Radding '.$registration->id , 'FlightController::makeFlight');
					$flight->add($registration);
				}
			}
		}
		
		return $flight->id;
	}


    /**
     * Displays and/or update Flight models for a competition.
     * @return mixed
     */
    public function actionCompetition2($id)
    {
		$competition = Competition::findOne($id);
		if(!$competition)
        	throw new NotFoundHttpException('The requested page does not exist.');

		$flights = $this->getFlights($competition);
		
        return $this->render('flights', [
			'competition' => $competition,
        ]);

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
			Yii::$app->session->setFlash('error', Yii::t('golf', 'Teams for competition not completed.'));
			return $this->redirect(Url::to(['competition/index']));
		}

		//should check that competition exists or exit.
		$savedflights = Yii::$app->request->post('flights');
		if($savedflights) {
			$flights = json_decode($savedflights);
			
			$to_save = false;
			if(isset($_POST['GLtimeStart'])) {
				$start_day = date('Y-m-d', strtotime($competition->start_date));
				$start_time = $start_day.' '.Yii::$app->request->post('GLtimeStart');
				$competition->start_date = $start_time;
				$to_save = true;
			}
			if(isset($_POST['GLdeltaStart'])) {
				$competition->flight_time = intval(Yii::$app->request->post('GLdeltaStart'));
				$to_save = true;			
			}
			if(isset($_POST['GLflightSize'])) {
				$competition->flight_size = intval(Yii::$app->request->post('GLflightSize'));
				$to_save = true;
			}
			if($to_save)
				$competition->save();

			$oldFlights = [];
			if($ff = $competition->getFlights()->all())
				foreach($ff as $f)
					$oldFlights[$f->id] = $f;
				
			foreach($flights as $flight) { // update or create each flight
				$id = $this->makeFlight($flight, $competition);
				unset($oldFlights[$id]); // flight still used, remove from "oldFlights"
			}
			foreach($oldFlights as $flight) { // delete unused flights ($oldFlights minus those still in use.)
				$flight->clean();
				$flight->delete();
			}

			if($flights) // @todo need a better test...
				Yii::$app->session->setFlash('success', Yii::t('golf', 'Flight saved sucessfully.'));
			else
				Yii::$app->session->setFlash('error', Yii::t('golf', 'There was a problem saving flights.'));
		}

		$flights = $this->getFlights($competition);
		if(!$flights) // no registration
        	throw new NotFoundHttpException('There is no registration for this competition.');

        return $this->render('flights', [
			'competition' => $competition,
        ]);

    }

    /**
     * Resets flights for a competition.
     * @return mixed
     */
    public function actionReset($id) {
		$competition = Competition::findOne($id);
		if(!$competition)
        	throw new NotFoundHttpException('The requested page does not exist.');

		$flights = $competition->getFlights();
		
		foreach($flights->each() as $flight) {
			$flight->clean();
			$flight->delete();
		}

		$flights = $this->getFlights($competition);
		if(!$flights) // no registration
        	throw new NotFoundHttpException('There is no registration for this competition.');

        return $this->redirect(['competition', 'id' => $competition->id]);
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
			Yii::$app->session->setFlash('danger', Yii::t('golf', 'Error(s): {0}', [VarDumper::dumpAsString($competition->errors, 4, true)]));

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

}
