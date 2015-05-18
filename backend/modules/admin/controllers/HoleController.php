<?php

namespace backend\modules\admin\controllers;

use Yii;
use common\models\Hole;
use common\models\search\HoleSearch;
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
