<?php

namespace backend\modules\score\controllers;

use Yii;
use backend\controllers\DefaultController as GolfLeagueController;
use common\models\Competition;
use common\models\Scorecard;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * ReportController implements the score entry actions for Registration model.
 */
class RuleController extends GolfLeagueController
{
    /**
     * Displays a single Flight model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findCompetition($id),
        ]);
    }

    /**
     * Displays and/or update Flight models for a competition.
     * @return mixed
     */
    public function actionApply($id)
    {
		$competition = Competition::findOne($id);
		if(!$competition)
        	throw new NotFoundHttpException('The requested page does not exist.');

		if($competition->ruleFinal) {
			$competition->ruleFinal->apply($competition);
			Yii::$app->session->setFlash('success', Yii::t('igolf', 'Rule applied.'));
		} else {
			Yii::$app->session->setFlash('info', Yii::t('igolf', 'No final rule to apply.'));
		}

        return $this->redirect(Url::to(['view', 'id' => $id]));
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

		$competition->status = Competition::STATUS_CLOSED;
		$competition->save();

        return $this->redirect(Url::to(['competition/index']));
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
