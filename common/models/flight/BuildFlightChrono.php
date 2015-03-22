<?php

namespace common\models\flight;

use common\models\flight\BuildFlight;
use common\models\Competition;
use common\models\Flight;
use common\models\Golfer;
use common\models\Registration;

/**
 * This is the interface for flight building algorithms.
 *
 */
class BuildFlightChrono implements BuildFlight
{
	public function execute($competition) {
		$flight_size = $competition->flight_size ? $competition->flight_size : Competition::FLIGHT_SIZE_DEFAULT;
		
		$count = $flight_size;
		$position = 1;

		$registrations = $competition->getRegistrations()->andWhere(['status' => Registration::STATUS_REGISTERED])->orderBy('created_at');
		foreach($registrations->each() as $registration) {
			if($count >= $flight_size) {
				$count = 0;
				$flight = new Flight();
				$flight->competition_id = $competition->id;
				$flight->position = $position++;
				$flight->save();
			}
			$registration->flight_id = $flight->id;
			$registration->save();
			$count++;
		}
	}
}
