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
class ScoreController extends GolfLeagueController
{

    /**
     * Displays and/or update Scorecard models for a competition.
     * @return mixed
     */
    public function actionCompetition($id)
    {
		$competition = $this->findCompetition($id);

        return $this->render('competition', [
			'competition' => $competition,
            'dataProvider' => new ActiveDataProvider([
				'query' => $competition->getScorecards(),
			]),
        ]);

    }


	public function actionUpdate($id) {
		$scorecard = $this->findScorecard($id);
		
		if(in_array($scorecard->registration->competition->status, [Competition::STATUS_COMPLETED, Competition::STATUS_CLOSED])) {
			Yii::$app->session->setFlash('warning', Yii::t('igolf', 'Scorecard already published cannot be edited.'));
	        return $this->render('view', [
				'scorecard' => $scorecard,
	        ]);
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
				$scorecard->updateScorecard();
				Yii::$app->session->setFlash('success', Yii::t('igolf', 'Scorecard updated.'));
			}
		} else {
			$scorecard->makeScores();
		}
		
        return $this->render('update', [
			'scorecard' => $scorecard,
            'dataProvider' => new ActiveDataProvider([
				'query' => $scorecard->getScores(),
			]),
        ]);
	}

    /**
     * Displays and/or update Scorecard models for a competition.
     * @return mixed
     */
    public function actionView($id)
    {
		$scorecard = $this->findScorecard($id);

        return $this->render('view', [
			'scorecard' => $scorecard,
        ]);
    }

    /**
     * Displays and/or update Scorecard models for a competition.
     * @return mixed
     */
    public function actionPublish($id)
    {
		$scorecard = $this->findScorecard($id);
		if($scorecard->publish()) {
			Yii::$app->session->setFlash('success', Yii::t('igolf', 'Scorecard published.'));
		} else {
			Yii::$app->session->setFlash('danger', Yii::t('igolf', 'Scorecard was not published.'));
		}
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Deletes an existing Scorecard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$scorecard = $this->findScorecard($id);
		if($scorecard->registration) {
			$id = $scorecard->registration->competition_id;
			$scorecard->deleteScores();
	        return $this->redirect(['competition', 'id' => $id]);
		} else {
			$scorecard->delete();
	        return $this->redirect(['/score']);
		}
    }

    /**
     * Finds the Scorecard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Scorecard the loaded model
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
     * Finds the Scorecard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Scorecard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findScorecard($id)
    {
        if (($model = Scorecard::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
