<?php

namespace common\models\flight;

use common\models\flight\BuildFlight;
use common\models\Competition;
use common\models\Flight;
use common\models\Registration;

/**
 * This is the interface for flight building algorithms.
 *
 */
class BuildFlightStandard implements BuildFlight
{
	public function execute($competition) {
		$registrations = $competition->getRegistrations();
		$this->addFlights($competition, $registrations);
	}
	
	public static function addFlights($competition, $registrations) {
		$flight_size = $competition->flight_size ? $competition->flight_size : Competition::FLIGHT_SIZE_DEFAULT;
		$position = Flight::find()->andWhere(['competition_id' => $competition->id])->max('position');
		if(! intval($position) > 0) $position = 0;
		$position++;
		$count = $flight_size;
		foreach($registrations->each() as $registration) {
			if($count >= $flight_size) {
				$count = 0;
				$flight = new Flight();
				$flight->position = $position++;
				$flight->save();
			}
			$registration->flight_id = $flight->id;
			$registration->save();
			$count++;
		}
	}
}
