<?php

namespace backend\modules\score\controllers;

use Yii;
use backend\controllers\DefaultController as GolfLeagueController;
use common\models\Competition;
use common\models\Registration;
use common\models\Scorecard;
use common\models\Score;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\helpers\VarDumper;

/**
 * ReportController implements the score entry actions for Registration model.
 */
class ScorecardController extends GolfLeagueController
{

    /**
     * Displays and/or update Flight models for a competition.
     * @return mixed
     */
    public function actionIndex($id)
    {
		$competition = Competition::findOne($id);
		if(!$competition)
        	throw new NotFoundHttpException('The requested page does not exist.');

        return $this->render('index', [
			'competition' => $competition,
            'dataProvider' => new ActiveDataProvider([
				'query' => $competition->getRegistrations()->andWhere(['status' => array_merge([Registration::STATUS_CONFIRMED], Registration::getPostCompetitionStatuses())]),
			]),
        ]);

    }


	public function actionUpdate($id) {
		$registration = $this->findRegistration($id);
		if(! $scorecard = $registration->getScorecards()->one()) { // Scorecard::findOne(['registration_id'=>$registration->id])
			$scorecard = new Scorecard([
				'registration_id' => $registration->id,
				'tees_id' => $registration->tees->id,
			]);
			$scorecard->save();
			$scorecard->init2();
		}
		
		if(isset($_POST['Score'])) {
			$count = 0;
			foreach (Yii::$app->request->post('Score') as $k => $dataToLoad) {
				$pk = explode('_', $k);
				if($model = Score::findOne(['scorecard_id'=>$pk[0], 'hole_id' =>$pk[1]])) {
	                $ret = $model->setAttributes($dataToLoad);
	                if ($model->save()) {
	                    $count++;
	                }
				}
			}
			if($count > 0) {
				Yii::$app->session->setFlash('success', Yii::t('igolf', 'Scorecard updated.'));
			}
		}

        return $this->render('update', [
			'competition' => $registration->competition,
			'registration' => $registration,
			'scorecard' => $scorecard,
            'dataProvider' => new ActiveDataProvider([
				'query' => $scorecard->getScores(),
			]),
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
				
		$competition->status = Competition::STATUS_COMPLETED;
		$competition->save();

        return $this->redirect(Url::to(['view', 'id' => $competition->id]));
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
    protected function findCompetition($id)
    {
        if (($model = Competition::findOne($id)) !== null) {
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
    protected function findRegistration($id)
    {
        if (($model = Registration::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
