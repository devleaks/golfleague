<?php

namespace backend\modules\start\controllers;

use Yii;
use common\models\Competition;
use common\models\Registration;
use common\models\match\BuildMatchChrono;

use yii\web\Controller;

class MatchController extends Controller
{

    /**
     * Make or Lists Flight models for a competition.
     * @return mixed
     */
	private function getMatches($competition) {
		$matches = $competition->getMatches()->orderBy('position')->all();		
		if(!$matches) {// need to make them
			$method = new BuildMatchChrono(); // later, method will be chosen from list of value
			$method->execute($competition);
			$matches = $competition->getMatches()->orderBy('position')->all();		
		} else { // we got flights, but may be some players registered after the last time we arranged flights
			$newRegs = $competition->getRegistrations()
						->andWhere(['status' => Registration::STATUS_REGISTERED])
						->andWhere(['match_id' => null])
						;
			// build additional flights with new registrations
			if($newRegs->exists()) {
				BuildMatchChrono::addMatches($competition, $newRegs);
				$flights = $competition->getMatches()->orderBy('position')->all();		
			}
		}
		return $matches;
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
		$savedmatches = Yii::$app->request->post('matches');
		if($savedmatches) {
			$matches = json_decode($savedmatches);
			
			foreach($matches as $match) {
				foreach($match->registrations as $reg_id) {
					if($registration = Registration::findOne($reg_id)) {
						$registration->match_id = $match->id;
						$registration->save();
					}
				}
			}

			if($matches) // need a better test...
				Yii::$app->session->setFlash('success', Yii::t('golf', 'Flight saved sucessfully.'));
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

		$matches = $competition->getMatches();
		
		foreach($matches->each() as $match) {
			$match->cleanRegistrations(true);
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
