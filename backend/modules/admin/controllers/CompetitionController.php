<?php

namespace backend\modules\admin\controllers;

use Yii;
use common\models\Competition;
use common\models\User;
use common\models\search\CompetitionSearch;
use backend\controllers\DefaultController as GolfLeagueController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CompetitionController implements the CRUD actions for Competition model.
 */
class CompetitionController extends GolfLeagueController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Competition models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompetitionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		if(!Yii::$app->user->identity->isA(User::ROLE_ADMIN)) {
			$dataProvider->query->andWhere(['league_id' => Yii::$app->user->identity->league_id]);
		}

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Competition models.
     * @return mixed
     */
    public function actionIndex2($type = Competition::TYPE_SEASON)
    {
        $searchModel = new CompetitionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['competition_type' => $type]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'type' => $type,
        ]);
    }

    /**
     * Displays a single Competition model.
     * @param integer $id
     * @return mixed
     */
	public function actionView($id) {
        $model=$this->findModel($id);
 
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id'=>$model->id]);
        } else {
            return $this->render('view', ['model'=>$model]);
        }
    }

    /**
     * Creates a new Competition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type)
    {
        $model = Competition::getNew($type);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
			if(count($model->errors)>0)
				Yii::$app->session->setFlash('warning', 'Errors: '.print_r($model->errors, true));
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Add a new child model for given model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd($parent_id)
    {
		$parent = $this->findModel($parent_id);
		$type = $parent->childType();
        $model = Competition::getNew($type);
		$model->parent_id = $parent_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
			/** copy initial data from parent competition */
			foreach([
	            'flight_size',
	            'registration_begin',
	            'registration_end',
	            'handicap_min',
	            'handicap_max',
	            'age_min',
	            'age_max',
	            'gender',
	            'recurrence_id',
	            'max_players',
	            'registration_special',
	            'flight_time',
	            'registration_time',
			] as $attribute)
				$model->$attribute = $parent->$attribute;
			
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Competition model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		if($model->getCompetitions()->exists())
			Yii::$app->session->setFlash('warning', 'Competition contains other competitions and cannot be deleted. Delete dependent competitions first.');
		else
			$model->deleteCascade();

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Competition model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionClose($id)
    {
        $model = $this->findModel($id);
		$model->status = Competition::STATUS_CLOSED;
		$model->save();

        return $this->redirect(['index']);
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
