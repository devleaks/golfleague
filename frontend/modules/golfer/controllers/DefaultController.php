<?php

namespace frontend\modules\golfer\controllers;

use Yii;
use yii\web\Controller;
use common\models\Golfer;

/**
 * Default Golfer Controller. Allows to edit profile (view).
 */
class DefaultController extends Controller {

	public function actionIndex() {
        return $this->render('index', ['model' => Golfer::me()]);
    }

    /**
     * Displays a single Golfer model.
     * @param integer $id
     * @return mixed
     */
	public function actionView($id) {
        $model=$this->findModel($id);
 
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->actionIndex();
       		//return $this->redirect(['view', 'id'=>$model->id]);
        } else {
			return $this->actionIndex();
            //return $this->render('view', ['model'=>$model]);
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
        if (($model = Golfer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
