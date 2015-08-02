<?php

namespace backend\modules\start\controllers;

use Yii;
use backend\controllers\DefaultController as GolfLeagueController;
use common\models\Competition;
use common\models\search\CompetitionSearch;
use common\models\Round;
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
		$registrationSearch = new CompetitionSearch();
        $registrationProvider = new ActiveDataProvider([
            'query' => Competition::find()->where(['status' => Competition::STATUS_OPEN])
//										  ->andWhere(['>','registration_end', $now])
        ]);

		/** Competition ready to be prepared. It must be a Match.
		 *  Registration must be officially closed, and competition cannot be started yet.
		 *  So any competition in status OPEN, after the closing registration date is ready to be prepared.
		 */
		$startSearch = new CompetitionSearch();
		$startProvider = new ActiveDataProvider([
            'query' => Round::find()->where(['status' => Competition::STATUS_OPEN])
									->andWhere(['<','registration_end', $now])
//									->andWhere(['>','start_date', $now]),
        ]);

		/** Competition ready to be played.
		 *  Registration must be READY and in the past (terminated).
		 */
		$readySearch = new CompetitionSearch();
		$readyProvider = new ActiveDataProvider([
            'query' => Competition::find()->where(['status' => Competition::STATUS_READY])
										  ->andWhere(['>=','start_date', $now]),
        ]);

		/** Planned competition.
		 *  Registration must be OPEN and we must be before the registration 
		 */
		$planSearch = new CompetitionSearch();
		$planProvider = new ActiveDataProvider([
            'query' => Competition::find()->where(['status' => Competition::STATUS_OPEN])
										  ->andWhere(['>','registration_begin', $now]),
        ]);

		/** Closed or terminated competition.
		 *  Registration must be CLOSED. 
		 */
		$closedSearch = new CompetitionSearch();
		$closedProvider = new ActiveDataProvider([
            'query' => Competition::find()->where(['status' => Competition::STATUS_CLOSED]),
        ]);

		$allSearch = new CompetitionSearch();
		$allProvider = new ActiveDataProvider([
            'query' => Competition::find(),
        ]);

    	return $this->render('index', [
	        'registrationProvider'  => $registrationProvider,
	        'registrationSearch'    => $registrationSearch,
	        'startProvider'  => $startProvider,
	        'startSearch'    => $startSearch,
	        'readyProvider' => $readyProvider,
	        'readySearch'   => $readySearch,
	        'planProvider'   => $planProvider,
	        'planSearch'     => $planSearch,
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
