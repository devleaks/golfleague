<?php

namespace backend\modules\score\controllers;

use Yii;
use backend\controllers\DefaultController;
use common\models\Competition;
use common\models\search\CompetitionSearch;
use common\models\Match;
use common\models\search\MatchSearch;
use common\models\Registration;
use common\models\Season;
use common\models\Tournament;
use common\models\rule\RuleMatchStandard;
use common\models\rule\RuleCompetitionStandard;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * CompetitionController implements the CRUD actions for Competition model.
 */
class CompetitionController extends DefaultController
{
    /**
     * Lists all Competition models.
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
        ]);

		/** Competition ready to be prepared. It must be a Match.
		 *  Registration must be officially closed, and competition cannot be started yet.
		 *  So any competition in status OPEN, after the closing registration date is ready to be prepared.
		 */
		$startSearch = new CompetitionSearch();
		$startProvider = new ActiveDataProvider([
            'query' => Match::find()->where(['status' => Competition::STATUS_OPEN])
										  ->andWhere(['<','registration_end', $now])
//										  ->andWhere(['>','start_date', $now]),
        ]);

		/** Competition awaiting results entry.
		 *  Registration must be READY and in the past (terminated).
		 */
		$resultSearch = new MatchSearch();
		$resultProvider = new ActiveDataProvider([
            'query' => Match::find()->where(['status' => Competition::STATUS_READY])
//										  ->andWhere(['<=','start_date', $now]),
        ]);

		/** Competition awaiting results entry.
		 *  Registration must be READY and in the past (terminated).
		 */
		$result2Search = new CompetitionSearch();
		$result2Provider = new ActiveDataProvider([
            'query' => Competition::find()->andWhere(['status' => Competition::STATUS_OPEN])
										  ->andWhere(['!=','competition_type', Competition::TYPE_MATCH]),
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
	        'resultProvider' => $resultProvider,
	        'resultSearch'   => $resultSearch,
	        'result2Provider' => $result2Provider,
	        'result2Search'   => $result2Search,
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
     * Creates a new Competition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Competition();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
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

/* SCORE UPDATE & CLOSE COMPETITIONS
 */
    /**
     * Displays a single Competition model and all registrations.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateScores($id)
    {
        return $this->render('update-scores', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Update positions according to rules and scores entered.
     * @param integer $id
     * @return mixed
     */
	public function actionPositions($id) {
		$model = Competition::findOne($id);
		$rules = new RuleMatchStandard($model);
		$result = $rules->doPositions();
		if($result === false)
            Yii::$app->session->setFlash('error', Yii::t('golfleague', "There was a problem computing positions."));
		else
            Yii::$app->session->setFlash('success', Yii::t('golfleague', "Positions set successfully."));
        return $this->redirect(['competition/update-scores', 'id' => $id]);
	}


    /**
     * Update points for MATCH according to rules and positions;
     * update points for TOURNAMENT and SEASONS according to rules and points in child competitions.
     * @param integer $id
     * @return mixed
     */
	public function actionPoints($id) {
		$model = Competition::findOne($id);
		
		if($model->competition_type == Competition::TYPE_MATCH)
			$rules = new RuleMatchStandard($model);
		else
			$rules = new RuleCompetitionStandard($model);

		$result = $rules->doPoints();

		if($result === false)
            Yii::$app->session->setFlash('error', Yii::t('golfleague', "There was a problem computing points."));
		else
            Yii::$app->session->setFlash('success', Yii::t('golfleague', "Points set successfully."));

        return $this->redirect(['competition/update-scores', 'id' => $id]);
	}
	
	public function actionClose($id) {
		$competition = Competition::findOne($id);
		if(!$competition)
        	throw new NotFoundHttpException('The requested page does not exist.');

		$registrations = $competition->getRegistrations();
		foreach($registrations->each() as $registration) {
			$registration->status = Registration::STATUS_QUALIFIED;
			$registration->save();
		}
				
		$competition->status = Competition::STATUS_CLOSED;
		$competition->save();

        return $this->redirect(['competition/index']);
	}

}
