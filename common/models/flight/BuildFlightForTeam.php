<?php

namespace common\models\flight;

use Yii;
use common\models\flight\BuildFlight;
use common\models\Competition;
use common\models\Flight;
use common\models\Team;
use common\models\Golfer;
use common\models\Registration;

/**
 * This is the interface for flight building algorithms.
 *
 */
class BuildFlightForTeam implements BuildFlight
{
	public function execute($competition) {
		$flight_size = $competition->flight_size ? $competition->flight_size : Competition::FLIGHT_SIZE_DEFAULT;
		$flight_interval = $competition->flight_time ? $competition->flight_time : Competition::FLIGHT_TIME_DEFAULT;
		$flight_time = strtotime("-".$flight_interval." minutes", strtotime($competition->start_date));
		
		$count = $flight_size;
		$position = 1;

		$teams = $competition->getTeams();
		foreach($teams->each() as $team) {
			Yii::trace('adding team '.$team->id);
			if($count >= $flight_size) {
				$flight_time = strtotime("+".$flight_interval." minutes", strtotime($flight_time));
				$count = 0;
				$flight = Flight::getNew(Flight::TYPE_FLIGHT);
				$flight->name = 'Flight '.$competition->id.'.'.$count;
				$flight->position = $position++;
				$flight->start_time = $flight_time;
				$flight->start_hole = $competition->start_hole;
				$flight->save();
				$flight->refresh();
				Yii::trace('new Flight '.$flight->id);
			}
			foreach($team->getRegistrations()->each() as $registration) {
				$flight->add($registration);
				$count++;
			}
		}
	}

	public static function addFlights($competition, $registrations) {
	}
}
