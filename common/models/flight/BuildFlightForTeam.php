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
		
		$count = $flight_size;
		$position = 1;

		$teams = $competition->getTeams();
		foreach($teams->each() as $team) {
			Yii::trace('adding team '.$team->id);
			if($count >= $flight_size) {
				$count = 0;
				$flight = new Flight();
				$flight->competition_id = $competition->id;
				$flight->position = $position++;
				$flight->save();
				$flight->refresh();
				Yii::trace('new Flight '.$flight->id);
			}
			foreach($team->getRegistrations()->each() as $registration) {
				Yii::trace('update reg '.$registration->id);
				$registration->flight_id = $flight->id;
				$registration->save();
			}
			$count++;
		}
	}

	public static function addFlights($competition, $registrations) {
		$flight_size = $competition->flight_size ? $competition->flight_size : Competition::FLIGHT_SIZE_DEFAULT;
		$position = Flight::find()->andWhere(['competition_id' => $competition->id])->max('position');
		if(! intval($position) > 0) $position = 0;
		$position++;
		$count = $flight_size;
		
		// we collect the teams for which we don't have a registration yet
		$team_ids = [];
		foreach($registrations->each() as $registration)
			$team_ids[$registration->team_id] = $registration->team_id;
			
		foreach(Team::find()->andWhere(['id' => $team_ids])->each() as $team) {
			if($count >= $flight_size) {
				$count = 0;
				$flight = new Flight();
				$flight->competition_id = $competition->id;
				$flight->position = $position++;
				$flight->save();
				$flight->refresh();
			}
			foreach($team->getRegistrations()->each() as $registration) {
				$registration->flight_id = $flight->id;
				$registration->save();
			}
			$count++;
		}
	}
}
