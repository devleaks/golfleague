<?php

namespace backend\modules\score\controllers;

use Yii;
use backend\controllers\DefaultController as GolfLeagueController;
use common\models\Competition;
use common\models\search\CompetitionSearch;
use common\models\Match;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * CompetitionController implements the CRUD actions for Competition model.
 */
class CompetitionController extends GolfLeagueController
{
    /**
     * Lists all Competition models of interest for Starter.
     * @return mixed
     */
    public function actionIndex()
    {
		$now = date('Y-m-d H:i:s');

		/** Competition open for registration by starter.
		 *  For starters, a competition is open for registration from the day it exists (ie before official registration opens),
		 *  to the day the competition is 'published'.
		 *  So any competition in status OPEN can accept registrations.
		 */
		$ongoingSearch = new CompetitionSearch();
        $ongoingProvider = new ActiveDataProvider([
            'query' => Match::find()->where(['status' => Competition::STATUS_READY])
										  ->andWhere(['<=','start_date', $now])
        ]);

		/** Awaiting scores competition.
		 *  Registration must be OPEN and we must be before the registration 
		 */
		$completedSearch = new CompetitionSearch();
		$completedProvider = new ActiveDataProvider([
            'query' => Competition::find()->where(['status' => Competition::STATUS_COMPLETED])
        ]);

		/** Closed or terminated competition.
		 *  Registration must be CLOSED. 
		 */
		$closedSearch = new CompetitionSearch();
		$closedProvider = new ActiveDataProvider([
            'query' => Competition::find()->where(['status' => Competition::STATUS_CLOSED]),
        ]);

    	return $this->render('index', [
	        'ongoingProvider' => $ongoingProvider,
	        'ongoingSearch'   => $ongoingSearch,
	        'completedProvider'   => $completedProvider,
	        'completedSearch'     => $completedSearch,
	        'closedProvider' => $closedProvider,
	        'closedSearch'   => $closedSearch,
        ]);
    }

    /**
     * Displays a single Competition model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single Competition model.
     * @param integer $id
     * @return mixed
     */
    public function actionLeaderboard($id)
    {
        return $this->render('leaderboard', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Competition model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Competition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Competition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Competition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
