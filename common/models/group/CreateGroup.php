<?php

namespace common\models\group;

use common\models\Group;
use common\models\Competition;
use common\models\Registration;

/**
 * This is the implementation for group building algorithms.
 *
 */
class CreateGroup extends Model implements CreateGroupInterface
{
	public $competition;
	public $group_type;
		
	public function create() {
		$this->update();
	}
	
	public function update() {
		$registrations = $this->competition->getRegistrationsNotIn($this->group_type);
		$flight_size = $this->competition->flight_size ? $this->competition->flight_size : Competition::FLIGHT_SIZE_DEFAULT;
		$flight_interval = $this->competition->flight_time ? $this->competition->flight_time : Competition::FLIGHT_TIME_DEFAULT;
		$position = $this->competition->getFlights()->max('group.position');
		$flight_time = strtotime("+".($position * $flight_interval)." minutes", strtotime($this->competition->start_date));
		if(! intval($position) > 0) $position = 0;
		$position++;
		$count = $flight_size;
		$flight = null;
		foreach($registrations->each() as $registration) {
			if(! $registration->getFlight()->exists() ) {
				if($count >= $flight_size) {
					if($flight) {
						$flight->handicap = $handicap;
						$flight->save();
					}
					$flight_time = strtotime("+".$flight_interval." minutes", strtotime($flight_time));
					$count = 0;
					$handicap = 0;
					$flight = Group::getNew($this->group_type);
					$flight->name = $this->group_type.' '.$this->competition->id.'.'.$count;
					$flight->position = $position++;
					$flight->start_time = $flight_time;
					$flight->start_hole = $this->competition->start_hole;
					$flight->save();
					Yii::trace(print_r($flight->errors, true) , 'Standard::update');
				}
				$flight->add($registration);
				$handicap += $registration->golfer->handicap;
				$count++;
			}
		}
		if($flight) {
			$flight->handicap = $handicap;
			$flight->save();
		}
	}
	
	public function updateFromJson($flight, $json) {
		foreach($json as $registration_str) {
			$registration_arr = explode('-', $registration_str); // registration-456
			if($registration = Registration::findOne($registration_arr[1])) {
				$flight->add($registration);
			}
		}
	}
}