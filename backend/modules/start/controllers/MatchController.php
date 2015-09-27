<?php

namespace backend\modules\start\controllers;

use Yii;
use common\models\Competition;
use common\models\Match;
use common\models\Registration;
use common\models\Team;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MatchController extends Controller
{
    /**
     * Make or Lists Match models for a competition.
     * @return mixed
     */
	private function getMatches($competition) {
		$matches = $competition->getMatches()->orderBy('position')->all();
		Yii::trace(print_r($matches, true) , 'MatchController::getMatches');	
		$method = $competition->getMatchClass();		
		if(!$matches) {// need to make them
			$method->create();			
		} else { // we got flights, but may be some players registered after the last time we arranged flights
			$method->update();			
		}
		return $competition->getMatches()->orderBy('position')->all();
	}

	/**
	 * Build flight and place registration in it. Create flight if necessary.
	 * @return flight_id updated or created
	 */
	private function makeMatch($competition, $match_str) {
		if(!$match = Match::findOne($match_str->id)) { // need to create it
			$position = $competition->getMatches()->max('group.position');
			if(! intval($position) > 0) $position = 0;
			$position++;
			$match = Match::getNew(MAtch::TYPE_MATCH);
			$match->position = $position++;
			$match->name = 'Match '.$this->competition->id.'.'.$count;
		} else { // remove existings
			$match->clean();
		}
		$match->save();

		$method = $competition->getMatchClass();
		$method->updateFromJson($match, $match_str->competitors);

		Yii::trace('returning '.$match->id , 'MatchController::makeMatch');
		
		return $match->id;
	}


    /**
     * Displays and/or update Match models for a competition.
     * @return mixed
     */
    public function actionCompetition($id)
    {
		$competition = Competition::findOne($id);
		if(!$competition)
        	throw new NotFoundHttpException('The requested page does not exist.');

		if($competition->isTeamCompetition() && !$competition->isTeamOk()) {
			Yii::$app->session->setFlash('error', Yii::t('golf', 'Teams for competition not completed.'));
			return $this->redirect(['competition/index']);
		}

		//should check that competition exists or exit.
		$savedmatches = Yii::$app->request->post('matches');
		if($savedmatches) {
			$method = $competition->getMatchClass();		
			$matches = json_decode($savedmatches);
			
			foreach($matches as $match_str) {
				$id = $this->makeMatch($competition, $match_str);
			}

			if($matches) // need a better test...
				Yii::$app->session->setFlash('success', Yii::t('golf', 'Matches saved sucessfully.'));
			else
				Yii::$app->session->setFlash('error', Yii::t('golf', 'There was a problem saving flights.'));
		}

		$matches = $this->getMatches($competition);
		if(!$matches) // no registration
        	throw new NotFoundHttpException('There is no registration for this competition.');

        return $this->render('competition', [
			'competition' => $competition,
            'matches' => $matches,
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

		foreach($competition->getMatches()->each() as $match) {
			$match->clean(true);
		}

		$matches = $this->getMatches($competition);

		if(!$matches) // no registration
        	throw new NotFoundHttpException('There is no registration for this competition.');
		else
			Yii::$app->session->setFlash('success', Yii::t('golf', 'Matches reset sucessfully.'));

        return $this->redirect(['competition', 'id' => $competition->id]);
	}


    /**
     * Resets flights for a competition.
     * @return mixed
     */
    public function actionPublish($id) {
		$competition = Competition::findOne($id);
		if(!$competition)
        	throw new NotFoundHttpException('The requested page does not exist.');

		$matches = $competition->getMatches();
		
		foreach($matches->each() as $match) {

		}

        return $this->redirect(['/start/competition', 'id' => $competition->id]);
	}


    /**
     * Finds the Competition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Competition the loaded model
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
