<?php

namespace backend\modules\score\controllers;

use backend\controllers\DefaultController as GolfLeagueController;
use common\models\Competition;
use common\models\Registration;
use common\models\search\ScorecardSearch;
use common\models\Scorecard;

use Yii;
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
     * Lists all Registration models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScorecardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['not', ['registration_id' => null]]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays and/or update Score models for a competition.
     * @return mixed
     */
    public function actionCompetition($id)
    {
		$competition = $this->findCompetition($id);

		if(isset($_POST['Scorecard'])) {
	        $models = Scorecard::find()->andWhere(['id' => array_keys($_POST['Scorecard'])])->indexBy('id')->all();
	        if (! Scorecard::loadMultiple($models, Yii::$app->request->post()) || ! Scorecard::validateMultiple($models)) {
				$errors = [];
				foreach($models as $model) {
					$errors += $model->errors;
				}
				if(count($errors)>0)
					Yii::$app->session->setFlash('danger', Yii::t('golf', 'Error(s): {0}', [VarDumper::dumpAsString($errors, 4, true)]));
			} else {
				foreach ($models as $model) {
					$model->save();
				}
				Yii::$app->session->setFlash('success', Yii::t('golf', 'Scores updated.'));
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
     * Lists all Registration models.
     * @return mixed
     */
    public function actionStatus($id)
    {
        if (($competition = Competition::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $searchModel = new ScorecardSearch();
        $dataProvider = new ActiveDataProvider([
			'query' => $competition->getScorecards(),
		]);

        return $this->render('status', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'competition' => $competition,
        ]);
    }

    /**
     * Displays and/or update Score models for a competition.
     * @return mixed
     */
    public function actionPublish($id)
    {
		$competition = $this->findCompetition($id);

		if($competition->getScorecards()->andWhere(['status' => Scorecard::STATUS_OPEN])->exists() ) {
			Yii::$app->session->setFlash('danger', Yii::t('golf', 'There are missing scorecards.'));
	        return $this->redirect(Url::to(['scorecard/competition', 'id' => $competition->id]));
		} else {
			$competition->status = Competition::STATUS_COMPLETED;
			if($competition->save()) {
				Yii::$app->session->setFlash('success', Yii::t('golf', 'Scorecards published.'));
		        return $this->redirect(Url::to(['competition/rule', 'id' => $competition->id]));
			} else {
				Yii::$app->session->setFlash('danger', Yii::t('golf', 'Could not save competition status.'));
		        return $this->redirect(Url::to(['scorecard/competition', 'id' => $competition->id]));
			}
		}
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

	public function actionComputeNet($id) {
		$competition = $this->findCompetition($id);
		foreach($competition->getScorecards()->each() as $scorecard) {
			$scorecard->compute(Scorecard::COMPUTE_GROSS_TO_NET);
		}
        return $this->redirect(Url::to(['competition', 'id' => $competition->id]));
	}

	/**
	 * Bulk update status or delete for PJAXed gridview.
	 */
    public function actionBulkStatus()
    {
		$ids = (array)Yii::$app->request->post('ids'); // Array or selected records primary keys
		$status = Yii::$app->request->post('status');

	    if (!$ids) // Preventing extra unnecessary query
	        return;

		foreach(Scorecard::find()->andWhere(['id' => $ids])->each() as $r) {
			$r->status = $status;
			$r->save();
        }
    }

}
