<?php

namespace backend\modules\scorer\controllers;

use Yii;
use common\models\Registration;
use common\models\RegistrationSearch;
use backend\controllers\DefaultController as GolfLeagueController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RegistrationController implements the CRUD actions for Registration model.
 */
class RegistrationController extends GolfLeagueController
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
     * Lists all Registration models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegistrationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Registration model.
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
     * Creates a new Registration model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Registration();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Registration model.
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
     * Deletes an existing Registration model.
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
     * Finds the Registration model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Registration the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Registration::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    protected function getUpdateRegistrations() {
        if(isset($_POST['Registration'])) {
            $i = 0;
            $regs = [];
            while(isset($_POST['Registration'][$i])) {
                $regs[] = Registration::findOne($_POST['Registration'][$i]['id']);
                $i++;
            }
            return $regs;
        }
        return null;
    }

    public function actionUpdateScores() {
        $models = $this->getUpdateRegistrations();
        if (Registration::loadMultiple($models, Yii::$app->request->post()) && Registration::validateMultiple($models)) {
            $count = 0;
            foreach ($models as $model) {
                // populate and save records for each model
				$model->position = null;
				$model->points = null;
                if ($model->save()) {
                    $count++;
                }
            }
            Yii::$app->session->setFlash('success', Yii::t('golfleague', "Processed {0} records successfully.", $count));
            return $this->redirect(['competition/update-scores', 'id' => $models[0]->competition_id]); // redirect to your next desired page
        } else {
            return $this->render('view', [
                'model' => $sourceModel,
                'dataProvider' => $dataProvider
            ]);
        }        
    }

    public function actionUpdates2() {
        $models = $this->getUpdateRegistrations();
        if (Registration::loadMultiple($models, Yii::$app->request->post()) && Registration::validateMultiple($models)) {
            $count = 0;
            foreach ($models as $model) {
                // populate and save records for each model
				$model->position = null;
                if ($model->save()) {
                    $count++;
                }
            }
            Yii::$app->session->setFlash('success', Yii::t('golfleague', "Processed {0} records successfully.", $count));
            return $this->redirect(['competition/update-scores', 'id' => $models[0]->competition_id]); // redirect to your next desired page
        } else {
            return $this->render('view', [
                'model' => $sourceModel,
                'dataProvider' => $dataProvider
            ]);
        }        
    }
}
