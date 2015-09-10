<?php

namespace common\models\flight;

use common\models\flight\BuildFlight;
use common\models\Competition;
use common\models\Flight;
use common\models\Golfer;
use common\models\Registration;

use Yii;

/**
 * This is the interface for flight building algorithms.
 *
 */
class BuildFlightChrono implements BuildFlight
{
	public function execute($competition) {
		$flight_size = $competition->flight_size ? $competition->flight_size : Competition::FLIGHT_SIZE_DEFAULT;
		$flight_interval = $competition->flight_time ? $competition->flight_time : Competition::FLIGHT_TIME_DEFAULT;
		$flight_time = strtotime("-".$flight_interval." minutes", strtotime($competition->start_date));
		
		Yii::trace('Flight size='.$flight_size, 'BuildFlightChrono::execute');

		$count = $flight_size;
		$position = 1;

		$registrations = $competition->getRegistrations()->andWhere(['status' => Registration::STATUS_REGISTERED])->orderBy('created_at');
		foreach($registrations->each() as $registration) {
			
			if($count >= $flight_size) {
				$flight_time = strtotime("+".$flight_interval." minutes", strtotime($flight_time));
				$count = 0;
				$flight = new Flight();
				$flight->group_type = Flight::TYPE_FLIGHT;
				$flight->name = 'Flight '.$competition->id.'.'.$count;
				$flight->position = $position++;
				$flight->start_time = $flight_time;
				$flight->start_hole = $competition->start_hole;
				$flight->save();
				Yii::trace(print_r($flight->errors, true) , 'BuildFlightChrono::execute');
				$flight->refresh();
			}
			Yii::trace('doing='.$registration->id.'='.$flight->id.' at='.$flight_time, 'BuildFlightChrono::execute');
			$flight->add($registration);
			$count++;
		}
	}
}
