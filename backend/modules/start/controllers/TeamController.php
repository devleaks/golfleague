<?php

namespace backend\modules\start\controllers;

use Yii;
use common\models\Competition;
use common\models\Registration;
use common\models\Team;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

class TeamController extends GroupController
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

		if($savedteams = Yii::$app->request->post('teams')) {
			$this->adjustGroups($savedteams, Team::TYPE_TEAM, $competition);

			if($competition->getTeams()->exists()) // need a better test...
				Yii::$app->session->setFlash('success', Yii::t('golf', 'Teams saved sucessfully.'));
			else
				Yii::$app->session->setFlash('error', Yii::t('golf', 'There was a problem saving teams.'));
				
			return $this->redirect(Url::to(['list', 'id' => $competition->id]));
		}

        return $this->render('teams', [
			'competition' => $competition,
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

}
