<?php

namespace backend\modules\admin\controllers;

use Yii;
use common\models\Score;
use common\models\ScoreSearch;
use backend\controllers\DefaultController as GolfLeagueController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScoreController implements the CRUD actions for Score model.
 */
class ScoreController extends GolfLeagueController
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
     * Lists all Score models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScoreSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Score model.
     * @param integer $scorecard_id
     * @param integer $hole_id
     * @return mixed
     */
    public function actionView($scorecard_id, $hole_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($scorecard_id, $hole_id),
        ]);
    }

    /**
     * Creates a new Score model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Score();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'scorecard_id' => $model->scorecard_id, 'hole_id' => $model->hole_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Score model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $scorecard_id
     * @param integer $hole_id
     * @return mixed
     */
    public function actionUpdate($scorecard_id, $hole_id)
    {
        $model = $this->findModel($scorecard_id, $hole_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'scorecard_id' => $model->scorecard_id, 'hole_id' => $model->hole_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Score model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $scorecard_id
     * @param integer $hole_id
     * @return mixed
     */
    public function actionDelete($scorecard_id, $hole_id)
    {
        $this->findModel($scorecard_id, $hole_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Score model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $scorecard_id
     * @param integer $hole_id
     * @return Score the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($scorecard_id, $hole_id)
    {
        if (($model = Score::findOne(['scorecard_id' => $scorecard_id, 'hole_id' => $hole_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
