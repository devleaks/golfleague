<?php

namespace backend\modules\admin\controllers;

use Yii;
use common\models\Point;
use common\models\search\PointSearch;
use backend\controllers\DefaultController as GolfLeagueController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PointController implements the CRUD actions for Point model.
 */
class PointController extends GolfLeagueController
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
     * Lists all Point models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PointSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Point model.
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
     * Creates a new Point model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Point();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Point model.
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
     * Deletes an existing Point model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		$rule_id = $model->rule_id;		
		$model->delete();

        return $this->redirect(['rule/view', 'id' => $rule_id]);
    }

    /**
     * Deletes an existing Point model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteGet($id)
    {
        $model = $this->findModel($id);
		$rule_id = $model->rule_id;		
		$model->delete();
        Yii::$app->session->setFlash('success', Yii::t('igolf', "Point deleted."));

        return $this->redirect(['rule/view', 'id' => $rule_id]);
    }

    /**
     * Finds the Point model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Point the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Point::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdates() {
		$errors = '';
		$rule_id = null;
        $models = Point::find()->where(['id' => array_keys($_POST['Point'])])->indexBy('id')->all();
        if (Point::loadMultiple($models, Yii::$app->request->post()) && Point::validateMultiple($models)) {
            $count = 0;
            foreach ($models as $model) {
                // populate and save records for each model
				if(count($model->errors)>0)
					$errors .= print_r($model->errors, true);
				if(!$rule_id)
					$rule_id = $model->rule_id;
                if ($model->save()) {
                    $count++;
                }
            }
            Yii::$app->session->setFlash('success', Yii::t('igolf', "Processed {0} records successfully.", $count));
            return $this->redirect(['rule/view', 'id' => $rule_id]); // redirect to your next desired page
        } else {
            Yii::$app->session->setFlash('error', Yii::t('igolf', "Could not processed your request. Errors: ", $errors));
            return $this->redirect(['rule/view', 'id' => $rule_id]); // redirect to your next desired page
       }        
    }


}
