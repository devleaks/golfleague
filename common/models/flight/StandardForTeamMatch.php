<?php

namespace common\models\flight;

use common\models\Competition;
use common\models\Flight;
use common\models\Match;
use common\models\Team;

use Yii;
use yii\base\Model;

/**
 * This is the interface for match building algorithms.
 *
 */
class StandardForTeamMatch extends Model implements BuildFlightInterface
{
	public $competition;
		
	private function updateFlights($matches){
		$match_size = $this->competition->rule->team_size;
		$flight_size = $this->competition->flight_size ? $this->competition->flight_size : Competition::FLIGHT_SIZE_DEFAULT;
		$flight_interval = $this->competition->flight_time ? $this->competition->flight_time : Competition::FLIGHT_TIME_DEFAULT;
		$position = $this->competition->getFlights()->max('group.position');
		$flight_time = strtotime("+".($position * $flight_interval)." minutes", strtotime($this->competition->start_date));
		if(! intval($position) > 0) $position = 0;
		$position++;
		$count = $flight_size;
		$flight = null;
		foreach($matches->each() as $match) {
			if($count >= $flight_size) {
				if($flight) {
					$flight->handicap = $handicap;
					$flight->save();
				}
				$flight_time = strtotime("+".$flight_interval." minutes", strtotime($flight_time));
				$count = 0;
				$handicap = 0;
				$flight = Flight::getNew(Flight::TYPE_FLIGHT);
				$flight->name = 'Flight '.$this->competition->id.'.'.$count;
				$flight->position = $position++;
				$flight->start_time = $flight_time;
				$flight->start_hole = $this->competition->start_hole;
				$flight->save();
				Yii::trace(print_r($flight->errors, true) , 'StandardForMatch::updateFlights');
			}
			foreach($match->getRegistrations()->each() as $registration) {
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
	
	protected function getMatchesNotInFlights() { //@todo
		return Match::find()->andWhere(['id' => 0]);
	}
	
	public function create() {
		Yii::trace('in', 'StandardForTeamMatch::create');
		$this->updateFlights($this->competition->getMatches());
	}
	
	public function update() {
		Yii::trace('in', 'StandardForTeamMatch::update');
		$this->updateFlights($this->getMatchesNotInFlights());
	}
	
	public function updateFromJson($flight, $json) {
		foreach($json as $match_str) {
			$match_arr = explode('-', $match_str); // competitor-456
			if($match = Match::findOne($match_arr[1])) {
				foreach($match->getRegistrations()->each() as $registration) {
					$flight->add($registration);
				}
			}
		}
	}
}
