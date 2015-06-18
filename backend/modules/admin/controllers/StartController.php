<?php

namespace backend\modules\admin\controllers;

use common\models\Competition;
use common\models\Golfer;
use common\models\Start;
use common\models\search\StartSearch;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StartController implements the CRUD actions for Start model.
 */
class StartController extends Controller
{
	/** */
	const METHOD_FIRST = 'q';
	const METHOD_GENDER = 'g';
	
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
     * Deletes an existing Start model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $start = $this->findModel($id);
		$competition = $start->competition;
		$start->delete();

        return $this->redirect(['competition/view', 'id' => $competition->id]);
    }

    /**
     * Adds a new Start model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd($id, $m)
    {
		$competition = $this->findCompetition($id);
		
        $model = new Start();
        $model->competition_id = $id;

		switch($m) {
			case self::METHOD_FIRST:
				if($tees = $competition->course->getTees()->one()) {
					$model->tees_id = $tees->id;
					$model->save();
					return $this->redirect(['competition/view', 'id' => $competition->id]);
				}
			case self::METHOD_GENDER:
			if( ($tees_lady = $competition->course->getTees()->andWhere(['gender'=>Golfer::GENDER_LADY])->one()) &&
				($tees_gentleman = $competition->course->getTees()->andWhere(['gender'=>Golfer::GENDER_GENTLEMAN])->one()) ) {
				$model->tees_id = $tees_lady->id;
				$model->gender = Golfer::GENDER_LADY;
				$model->save();
				$model2 = new Start([
					'competition_id' => $competition->id,
					'tees_id' => $tees_gentleman->id,
					'gender' => Golfer::GENDER_GENTLEMAN,
				]);
				$model2->save();
				return $this->redirect(['competition/view', 'id' => $competition->id]);
			}
			
		}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Start model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Start the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Start::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Competition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Competition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCompetition($id)
    {
        if (($model = Competition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
