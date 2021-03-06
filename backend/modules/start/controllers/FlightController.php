<?php

namespace backend\modules\start\controllers;


use common\models\Competition;
use common\models\Flight;
use common\models\Match;
use common\models\Registration;
use common\models\Team;

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
		Yii::trace('flights? '.($flights? 'yes':'no'), 'FlightController::getFlights');
		
		$method = $competition->getFlightClass();		
		if(!$flights) {// need to make them
			$method->create();			
		} else { // we got flights, but may be some players registered after the last time we arranged flights
			$method->update();			
		}
		return $competition->getFlights()->orderBy('position')->all();
	}

	/**
	 * Build flight and place registration in it. Create flight if necessary.
	 * @return flight_id updated or created
	 */
	private function makeFlight($competition, $flight_str) {
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
		$flight->start_time = $competition_date . ' ' . $flight_str->start_time . ':00';
		Yii::trace('start_time:'.$flight->start_time, 'FlightController::makeFlight');
		$flight->start_hole = $competition->start_hole;
		$flight->save();
		Yii::trace(print_r($flight->attributes, true) , 'FlightController::makeFlight');

		$method = $competition->getFlightClass();
		$method->updateFromJson($flight, $flight_str->registrations);

		Yii::trace('returning '.$flight->id , 'FlightController::makeFlight');
		
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
			Yii::$app->session->setFlash('error', Yii::t('golf', 'Teams for competition not completed.'));
			return $this->redirect(Url::to(['competition/index']));
		}

		if($competition->isMatchCompetition() && !$competition->isMatchOk()) {
			Yii::$app->session->setFlash('error', Yii::t('golf', 'Match for competition not completed.'));
			return $this->redirect(Url::to(['competition/index']));
		}

		//should check that competition exists or exit.
		$savedflights = Yii::$app->request->post('flights');
		if($savedflights) {
			$flights = json_decode($savedflights);
			
			$to_save = false;
			if($GLtimeStart = Yii::$app->request->post('GLtimeStart')) {
				$start_day = date('Y-m-d', strtotime($competition->start_date));
				$start_time = $start_day.' '.$GLtimeStart;
				$competition->start_date = $start_time;
				$to_save = true;
			}
			if($GLdeltaStart = Yii::$app->request->post('GLdeltaStart')) {
				$competition->flight_time = intval($GLdeltaStart);
				$to_save = true;			
			}
			if($GLflightSize = Yii::$app->request->post('GLflightSize')) {
				$competition->flight_size = intval($GLflightSize);
				$to_save = true;
			}
			if($to_save)
				$competition->save();

			$oldFlights = [];
			if($ff = $competition->getFlights()->all())
				foreach($ff as $f)
					$oldFlights[$f->id] = $f;
				
			foreach($flights as $flight) { // update or create each flight
				$id = $this->makeFlight($competition, $flight);
				Yii::trace('unsetting old flight:'.$flight->id, 'FlightController::actionCompetition');
				unset($oldFlights[$id]); // flight still used, remove from "oldFlights"
			}
			foreach($oldFlights as $flight) { // delete unused flights ($oldFlights minus those still in use.)
				Yii::trace('removing old flight:'.$flight->id, 'FlightController::actionCompetition');
				$flight->clean(true);
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
