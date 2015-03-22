<?php

namespace backend\modules\admin\controllers;

use Yii;
use common\models\Hole;
use common\models\HoleSearch;
use backend\controllers\DefaultController as GolfLeagueController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HoleController implements the CRUD actions for Hole model.
 */
class HoleController extends GolfLeagueController
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
     * Lists all Hole models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Hole model.
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
     * Creates a new Hole model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Hole();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Hole model.
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

    public function actionUpdates() {
		$errors = '';
		$tees_id = null;
        $models = Hole::find()->where(['id' => array_keys($_POST['Hole'])])->indexBy('id')->all();
        if (Hole::loadMultiple($models, Yii::$app->request->post()) && Hole::validateMultiple($models)) {
            $count = 0;
            foreach ($models as $model) {
                // populate and save records for each model
				if(count($model->errors)>0)
					$errors .= print_r($model->errors, true);
				if(!$tees_id)
					$tees_id = $model->tees_id;
                if ($model->save()) {
                    $count++;
                }
            }
            Yii::$app->session->setFlash('success', Yii::t('igolf', "Processed {0} records successfully.", $count));
            return $this->redirect(['tees/view', 'id' => $tees_id]); // redirect to your next desired page
        } else {
            Yii::$app->session->setFlash('error', Yii::t('igolf', "There are errors processing your request: ".$errors."."));
            return $this->redirect(['tees/view', 'id' => $tees_id]); // redirect to your next desired page
        }        
    }

    /**
     * Update hole length in Editable field (test)
     * @return string Json OK with new value or Json not OK with error.
     */
    public function actionUpdatelength() {
        $model = new Hole(); //$this->findModel($_POST['id']);

        Yii::info(print_r($_POST));
        
        // Check if there is an Editable ajax request
        if (isset($_POST['hasEditable'])) {
            // read your posted model attributes
            if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {
                // read or convert your posted information
                $value = $model->length;

                // return JSON encoded output in the below format
                echo \yii\helpers\Json::encode(['output'=>$value, 'message'=>'']);
                
                // alternatively you can return a validation error
                // echo \yii\helpers\Json::encode(['output'=>'', 'message'=>'Validation error']);
            }
            // else if nothing to do always return an empty JSON encoded output
            else {
                echo \yii\helpers\Json::encode(['output'=>'', 'message'=>'']);
            }
            return;
        }
        
        // Else return to rendering a normal view
        return $this->render('view', ['model'=>$model]);
    }

    /**
     * Deletes an existing Hole model.
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
     * Finds the Hole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Hole the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Hole::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
