<?php

namespace frontend\controllers;

use common\models\Event;
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
		$calendarEvents = [];
		foreach(Event::find()->orderBy('event_start')->each() as $event) {
			$start = \DateTime::createFromFormat('Y-m-d H:i:s', $event->event_start);
			$end   = \DateTime::createFromFormat('Y-m-d H:i:s', $event->event_end);
						
			$calendarEvents[] = new CalendarEvent([
				'id' => $event->id,
				'title' => $event->name,
				'url' => Url::to(['view', 'id'=>$event->id]),
				'className' => 'btn-'.$event->getColor(),
 				'start' => date('Y-m-d\TH:m:s\Z',$start->getTimestamp()),
				'end' => $end ? date('Y-m-d\TH:m:s\Z',$end->getTimestamp()) : null,
			]);
		}

        return $this->render('index', [
            'events' => $calendarEvents,
        ]);
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
