<?php

namespace frontend\controllers;

use common\models\Event;
use common\models\Round;
use yii2fullcalendar\models\Event as CalendarEvent;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;

class CalendarController extends Controller
{
    /**
     * Lists all Event models.
     * @return mixed
     */
    public function actionIndex()
    {
		$now = date('Y-m-d H:i:s');
		$competitions = new ActiveDataProvider([
			'query' => Round::find()->andWhere(['>','start_date', $now])
		]);
        return $this->render('index', ['competitions' => $competitions]);
    }

    /**
     * Displays a single Message model.
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
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
