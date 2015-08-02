<?php
/**
 * LatestMessages widget renders the last messages available on the website.
 *
 * @author Pierre M <devleaks.be@gmail.com>
 */

namespace frontend\widgets;

use common\models\Event;
use common\models\Round;
use common\models\Message;
use yii\bootstrap\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

class Calendar extends Widget {
	/** number of recent message to display */
	public $months = 1;
	
	public function run() {
		$calendarEvents = [];

		// 1. Events
		foreach(Event::find()->orderBy('event_start')->each() as $event)
			$calendarEvents[] = $event->getFullCalendarEvent();

		
		// 2. Rounds
		foreach(Round::find()->each() as $match)
			foreach($match->getEvents() as $event)
				$calendarEvents[] = $event->getFullCalendarEvent();

        return $this->render('calendar', [
            'events' => $calendarEvents,
			'months' => 3,
        ]);
	}

}