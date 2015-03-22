<?php

namespace devleaks\golfleague\controllers;

use common\models\Event;
use common\models\Competition;
use common\models\Golfer;

class LeagueController extends \backend\controllers\DefaultController
{
    public function actionCalendar()
    {		
        return $this->render('calendar');
    }

    public function actionCalendarData()
    {
		$now = date('Y-m-d H:i:s');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
