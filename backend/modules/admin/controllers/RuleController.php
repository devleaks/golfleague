<?php

namespace backend\modules\admin\controllers;

use Yii;
use common\models\Rule;
use common\models\search\RuleSearch;
use common\models\Point;
use backend\controllers\DefaultController as GolfLeagueController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;

/**
 * RuleController implements the CRUD actions for Rule model.
 */
class RuleController extends GolfLeagueController
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
     * Lists all Rule models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RuleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rule model.
     * @param integer $id
     * @return mixed
     */
	public function actionView($id) {
        $model=$this->findModel($id);
 
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if(!in_array($model->rule_type, [Rule::TYPE_STROKEPLAY, Rule::TYPE_MATCHPLAY])) {
				$model->rule_type = ($model->rule_type == 1) ? Rule::TYPE_MATCHPLAY : Rule::TYPE_STROKEPLAY;
				$model->save();
			}
            return $this->redirect(['view', 'id'=>$model->id]);
        } else {
			if(count($model->errors)>0)
				Yii::$app->session->setFlash('danger', Yii::t('golf', 'Error(s): {0}', [VarDumper::dumpAsString($model->errors, 4, true)]));
            return $this->render('view', ['model'=>$model]);
        }
    }

    /**
     * Creates a new Rule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rule();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if(!in_array($model->rule_type, [Rule::TYPE_STROKEPLAY, Rule::TYPE_MATCHPLAY])) {
				$model->rule_type = ($model->rule_type == 1) ? Rule::TYPE_MATCHPLAY : Rule::TYPE_STROKEPLAY;
				$model->save();
			}
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Point model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddpoints($rule_id, $count = 1)
    {
        $maxpos = Point::find(['rule_id'=>$rule_id])->max('position');

        for($i = 0; $i < $count; $i++) {
            $model = new Point();
            $model->rule_id = intval($rule_id);
            $model->position = $maxpos + $i + 1;
            $model->points = 0;
            $model->save();
        }
        return $this->redirect(['view', 'id' => $rule_id]);
    }

    /**
     * Updates an existing Rule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if(!in_array($model->rule_type, [Rule::TYPE_STROKEPLAY, Rule::TYPE_MATCHPLAY])) {
				$model->rule_type = ($model->rule_type == 1) ? Rule::TYPE_MATCHPLAY : Rule::TYPE_STROKEPLAY;
				$model->save();
			}
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Rule model.
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
     * Finds the Rule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
