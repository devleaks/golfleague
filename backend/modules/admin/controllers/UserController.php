<?php

namespace backend\modules\admin\controllers;

use Yii;
use common\models\User;
use common\models\search\UserSearch;
use backend\controllers\DefaultController as GolfLeagueController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends GolfLeagueController
{
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Shows profile settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionLeague()
    {
        $model = User::findOne(Yii::$app->user->identity->getId());

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Your profile has been updated'));

            return $this->refresh();
        }

        return $this->render('league', [
            'model' => $model,
        ]);
    }


    public function actionGolf()
    {
    	if($user = User::findOne(Yii::$app->user->identity->getId())) {
			if($model = $user->getGolfer()->one()) {
		        if ($model->load(Yii::$app->request->post()) && $model->save()) {
		            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Your profile has been updated'));

		            return $this->refresh();
			        return $this->render('golfer', [
			            'model' => $model,
			        ]);
		        } else {
			        return $this->render('golfer', [
			            'model' => $model,
			        ]);
				}
			} else {
	            Yii::$app->getSession()->setFlash('warning', Yii::t('user', 'Your golfer profile does not exists'));
		        return $this->redirect(['league', 'id' => $user->id]);
			}
		} else {
            Yii::$app->getSession()->setFlash('danger', Yii::t('user', 'User not found'));
		}
		return $this->redirect(['/admin']);
    }




    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
