<?php

namespace backend\modules\score\controllers;

use Yii;
use backend\controllers\DefaultController as GolfLeagueController;
use common\models\Competition;
use common\models\Registration;
use common\models\ScorecardForCompetition;
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
     * Displays and/or update Score models for a competition.
     * @return mixed
     */
    public function actionCompetition($id)
    {
		$competition = Competition::findOne($id);
		if(!$competition)
        	throw new NotFoundHttpException('The requested page does not exist.');

		if(isset($_POST['ScorecardForCompetition'])) {
	        $models = ScorecardForCompetition::find()->andWhere(['id' => array_keys($_POST['ScorecardForCompetition'])])->indexBy('id')->all();
	        if (! ScorecardForCompetition::loadMultiple($models, Yii::$app->request->post()) || ! ScorecardForCompetition::validateMultiple($models)) {
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
		} else { //@todo do not loop on getScorecards twice...
			$scorecards = [];
			foreach($competition->getRegistrations()
								->andWhere(['registration.status' => array_merge([Registration::STATUS_CONFIRMED], Registration::getPostCompetitionStatuses())])
								->each() as $registration) {
				$scorecards[] = $registration->getScorecard(); // this will create a scorecard if none exists
			}
		}
		

        return $this->render('competition', [
			'competition' => $competition,
            'dataProvider' => new ActiveDataProvider([
				'query' => $competition->getScorecards()
			]),
        ]);

    }


    /**
     * Displays and/or update Score models for a competition.
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
     * Displays a single Score model.
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
     * Finds the Score model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Score the loaded model
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
