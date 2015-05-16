<?php

namespace backend\modules\score\controllers;

use Yii;
use backend\controllers\DefaultController as GolfLeagueController;
use common\models\Competition;
use common\models\Flight;
use common\models\Registration;
use common\models\Team;
use common\models\TeesForm;
use common\models\search\CompetitionSearch;
use common\models\search\FlightSearch;
use common\models\search\RegistrationSearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\helpers\VarDumper;

/**
 * ReportController implements the score entry actions for Registration model.
 */
class ResultController extends GolfLeagueController
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


		if(isset($_POST['Registration'])) {
	        $models = Registration::find()->andWhere(['id' => array_keys($_POST['Registration'])])->indexBy('id')->all();
	        if (! Registration::loadMultiple($models, Yii::$app->request->post()) || ! Registration::validateMultiple($models)) {
				$errors = [];
				foreach($models as $model) {
					$errors += $model->errors;
				}
				if(count($errors)>0)
					Yii::$app->session->setFlash('danger', Yii::t('igolf', 'Error(s): {0}', [VarDumper::dumpAsString($errors, 4, true)]));
			} else {
				foreach ($models as $model) {
					$model->save();
				}
				Yii::$app->session->setFlash('success', Yii::t('igolf', 'Scores updated.'));
			}
		}

        return $this->render('competition', [
			'competition' => $competition,
            'dataProvider' => new ActiveDataProvider([
				'query' => $competition->getRegistrations()->andWhere(['status' => array_merge([Registration::STATUS_CONFIRMED], Registration::getTerminatedStatuses())]),
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

        return $this->redirect(Url::to(['result/view', 'id' => $competition->id]));
    }

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
}
