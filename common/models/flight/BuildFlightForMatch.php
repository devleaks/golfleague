<?php

namespace common\models\flight;

use Yii;
use common\models\flight\BuildFlight;
use common\models\Competition;
use common\models\Flight;
use common\models\Match;
use common\models\Golfer;
use common\models\Registration;

/**
 * This is the interface for flight building algorithms.
 *
 */
class BuildFlightForMatch implements BuildFlight
{
	public function execute($competition) {
		$flight_size = $competition->flight_size ? $competition->flight_size : Competition::FLIGHT_SIZE_DEFAULT;
		$flight_interval = $competition->flight_time ? $competition->flight_time : Competition::FLIGHT_TIME_DEFAULT;
		$flight_time = strtotime("-".$flight_interval." minutes", strtotime($competition->start_date));

		$count = $flight_size;
		$position = 1;

		$matches = $competition->getMatches();
		foreach($matches->each() as $match) {
			Yii::trace('adding match '.$match->id);
			if($count >= $flight_size) {
				$flight_time = strtotime("+".$flight_interval." minutes", strtotime($flight_time));
				$count = 0;
				$flight = Match::getNew(Match::TYPE_FLIGHT);
				$flight->name = 'Flight '.$competition->id.'.'.$count;
				$flight->position = $position++;
				$flight->start_time = $flight_time;
				$flight->start_hole = $competition->start_hole;
				$flight->save();
				$flight->refresh();
				Yii::trace('new Flight '.$flight->id);
			}
			foreach($match->getRegistrations()->each() as $registration) {
				Yii::trace('update reg '.$registration->id);
				$flight->add($registration);
				$count++;
			}
		}
	}

	public static function addFlights($competition, $registrations) {
		$flight_size = $competition->flight_size ? $competition->flight_size : Competition::FLIGHT_SIZE_DEFAULT;
		$position = $competition->getFlights()->max('position');
		if(! intval($position) > 0) $position = 0;
		$position++;
		$count = $flight_size;
		
		// we collect the matches for which we don't have a registration yet
		$match_ids = [];
		foreach($registrations->each() as $registration) {
			$match_ids[] = $registration->getMatch()->one()->id;
		}
			
		foreach(Match::find()->andWhere(['id' => array_values($match_ids)])->each() as $match) {
			if($count >= $flight_size) {
				$count = 0;
				$flight = Match::getNew(Match::TYPE_FLIGHT);
				$flight->name = 'Flight '.$competition->id.'.'.$count;
				$flight->position = $position++;
				$flight->start_time = $flight_time;
				$flight->start_hole = $competition->start_hole;
				$flight->save();
				$flight->refresh();
			}
			foreach($match->getRegistrations()->each() as $registration) {
				$flight->add($registration);
				$count++;
			}
		}
	}
}
