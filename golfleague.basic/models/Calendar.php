<?php

namespace common\models;

use Yii;

/**
 * This is a model class for calendar objects.
 *
 */
class Calendar 
{
	public $events;
	
    public  function __construct()
    {
		$this->events = [];
    }

    public function add($event)
    {
		$events[] = $event;
    }
}
