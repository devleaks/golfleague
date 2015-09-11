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
		$flight_interval = $competition->flight_time ? $competition->flight_time : Competition::FLIGHT_TIME_DEFAULT;
		$position = $competition->getFlights()->max('group.position');
		$flight_time = strtotime("+".($position * $flight_interval)." minutes", strtotime($competition->start_date));
		if(! intval($position) > 0) $position = 0;
		$position++;
		$count = $flight_size;
		foreach($registrations->each() as $registration) {
			if(! $registration->getFlight()->exists() ) {
				if($count >= $flight_size) {
					$flight_time = strtotime("+".$flight_interval." minutes", strtotime($flight_time));
					$count = 0;
					$flight = Match::getNew(Match::TYPE_FLIGHT);
					$flight->name = 'Flight '.$competition->id.'.'.$count;
					$flight->position = $position++;
					$flight->start_time = $flight_time;
					$flight->start_hole = $competition->start_hole;
					$flight->save();
				}
				$flight->add($registration);
				$count++;
			}
		}
	}
}
