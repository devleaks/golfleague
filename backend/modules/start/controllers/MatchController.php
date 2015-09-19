<?php

namespace backend\modules\start\controllers;

use Yii;
use common\models\Competition;
use common\models\Match;
use common\models\Registration;
use common\models\Team;
use common\models\match\BuildMatchChrono;
use common\models\match\BuildMatchForTeam;

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
		Yii::trace('getMatches entered');
		if(!$matches) {// need to make them
			Yii::trace('getMatches has no matches');

			$method = null;

			if($competition->isTeamCompetition())
				$method = new BuildMatchForTeam();
			else
				$method = new BuildMatchChrono(); // later, method will be chosen from list of value

			$method->execute($competition);

			$matches = $competition->getMatches()->orderBy('position')->all();		
		} else { // we got flights, but may be some players registered after the last time we arranged flights
			$newRegs = $competition->getRegistrationsNotIn(Match::TYPE_MATCH);
			// build additional flights with new registrations
			Yii::trace('getMatches newRegs entered');
			if($newRegs->exists()) {
				Yii::trace('getMatches adds newRegs');
				if($competition->isTeamCompetition())
					BuildMatchForTeam::addMatches($competition, $newRegs);
				else
					BuildMatchChrono::addMatches($competition, $newRegs);

				$matches = $competition->getMatches()->orderBy('position')->all();		
			}
		}
		return $matches;
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
			return $this->redirect(Url::to(['competition/index']));
		}

		//should check that competition exists or exit.
		$savedmatches = Yii::$app->request->post('matches');
		Yii::trace('savedmatches='.print_r($savedmatches, true));
		if($savedmatches) {
			$matches = json_decode($savedmatches);
			
			foreach($matches as $match_str) {
				$match = Match::findOne($match_str->id);
				$match->clean();
				Yii::trace('match='.$match->id);
				foreach($match_str->competitors as $competitor_id) {
					Yii::trace('adding reg='.$competitor_id);
					if($competitor = ($competition->isTeamCompetition() ? Team::findOne($competitor_id) : Registration::findOne($competitor_id))) {
						Yii::trace('adding reg='.$competitor->id);
						$match->add($competitor);
					}
				}
			}

			if($matches) // need a better test...
				Yii::$app->session->setFlash('success', Yii::t('golf', 'Matches saved sucessfully.'));
			else
				Yii::$app->session->setFlash('error', Yii::t('golf', 'There was a problem saving flights.'));

			Yii::trace('EXIT Json Save');
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

		$matches = $competition->getMatches();
		
		foreach($matches->each() as $match) {
			$match->clean();
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
