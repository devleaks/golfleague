<?php

namespace backend\modules\admin\controllers;

use Yii;
use common\models\Tees;
use common\models\Course;
use common\models\Hole;
use common\models\search\TeesSearch;
use backend\controllers\DefaultController as GolfLeagueController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TeesController implements the CRUD actions for Tees model.
 */
class TeesController extends GolfLeagueController
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
     * Lists all Tees models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TeesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tees model.
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
     * Creates a new Tees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tees();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Adds a new Tees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd($course_id)
    {
        $model = new Tees();
        $model->course_id = $course_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // create holes
            $course = Course::findOne($model->course_id);
            for($i = 1; $i <= $course->holes; $i++) {
                $hole = new Hole();
                $hole->position = intval($i);
                $hole->tees_id = intval($model->id);
                $hole->save(); //@todo: check for errors and report
           }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Adds a new holes with no values.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddholes($id)
    {
        $model = Tees::findOne($id);

        if(sizeof($model->getHoles()->all()) == 0) {
            $course = Course::findOne($model->course_id);
            for($i = 1; $i <= $course->holes; $i++) {
                $hole = new Hole();
                $hole->position = intval($i);
                $hole->tees_id = intval($model->id);
                $hole->save(); //@todo: check for errors and report
           }
        } else
            Yii::$app->session->setFlash('error', 'There are already holes defined for this tees set.');

        return $this->redirect(['view', 'id' => $model->id]);
   }

    /**
     * Adds a new holes with values copied from another tees set.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCopyholes($id, $copy_id)
    {
        $src = Tees::findOne($copy_id)->getHoles()->all();

        if(sizeof($src) > 0)
            foreach($src as $srchole) {
                $hole = new Hole();
                $hole->tees_id = intval($id);
                $hole->position = $srchole->position;
                $hole->par = $srchole->par;
                $hole->si = $srchole->si;
                $hole->length = $srchole->length;
                $hole->save(); //@todo: check for errors and report
           }
        else
            Yii::$app->session->setFlash('error', 'Could not find source holes.');

        return $this->redirect(['view', 'id' => $id]);
   }

    /**
     * Deletes an existing Tees model.
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
     * Finds the Tees model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tees the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tees::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
