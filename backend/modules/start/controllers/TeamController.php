<?php

namespace backend\modules\start\controllers;

use Yii;
use common\models\Competition;
use common\models\Registration;
use common\models\Team;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

class TeamController extends \backend\controllers\DefaultController
{
    /**
     * Displays and/or update Flight models for a competition.
     * @return mixed
     */
    public function actionCompetition($id)
    {
		$competition = Competition::findOne($id);
		if(!$competition)
        	throw new NotFoundHttpException('The requested page does not exist.');

		//should check that competition exists or exit.
		$savedteams = Yii::$app->request->post('teams');
		if($savedteams) {
			$teams = json_decode($savedteams);

			$oldTeams = [];
			$ff = $competition->getTeams()->all();
			if($ff)
				foreach($ff as $f)
					$oldTeams[$f->id] = $f;
				
			foreach($teams as $team) { // update or create each team
				$id = $this->makeTeam($team);
				unset($oldTeams[$id]); // team still used, remove from "oldFlights"
			}
			foreach($oldTeams as $team) { // delete unused teams ($oldTeams minus those still in use.)
				$team->cleanRegistrations(true);
			}

			if($teams) // need a better test...
				Yii::$app->session->setFlash('success', Yii::t('golf', 'Teams saved sucessfully.'));
			else
				Yii::$app->session->setFlash('error', Yii::t('golf', 'There was a problem saving teams.'));
				
			return $this->redirect(Url::to(['list', 'id' => $competition->id]));
		}

		$registrations = $competition->getRegistrations()
			->andWhere(['team_id' => null])
			->andWhere(['status' => Registration::STATUS_REGISTERED]);
			
		$teams = $competition->getTeams();

		if(!$registrations->exists() && !$teams->exists()) // no registration
        	throw new NotFoundHttpException('There is no registration for this competition.');


        return $this->render('teams', [
			'competition' => $competition,
			'registrations' => $registrations,
            'teams' => $teams,
        ]);

    }

	/**
	 * Build flight and place registration in it. Create flight if necessary.
	 * @return flight_id updated or created
	 */
	private function makeTeam($team_str) {
		$team_arr = explode('-', $team_str->id); // team-123
		$team = Team::findOne($team_arr[1]);
		if(!$team) { // need to create it
			$team = new Team();
			$team->name = $team_arr[1];
			$team->save();
			$team->refresh();
		}
		$name = '';
		foreach($team_str->registrations as $registration_str) {
			$registration_arr = explode('-', $registration_str); // registration-456
			if($registration = Registration::findOne($registration_arr[1]))
				$registration->team_id = $team->id;
				$registration->save();
				$name .= $registration->golfer->name.' / ';			
		}
		$name = rtrim($name, ' /');
		$team->name = substr($name, 0, 80);
		$team->handicap = $team_str->handicap;
		$team->save();
		return $team->id;
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
