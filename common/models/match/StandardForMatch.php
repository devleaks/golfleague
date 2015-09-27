<?php

namespace common\models\match;

use common\models\Competition;
use common\models\Match;
use common\models\Registration;

use Yii;
use yii\base\Model;

/**
 * This is the interface for match building algorithms.
 *
 */
class StandardForMatch extends Model implements BuildMatchInterface
{
	public $competition;
		
	private function updateMatches($registrations){
		$position = $this->competition->getMatches()->max('group.position');
		if(! intval($position) > 0) $position = 0;
		$position++;
		$count = 0;
		$match = null;
		foreach($registrations->each() as $registration) {
			if(!($count % 2)) {
				if($match) {
					$match->handicap = $handicap;
					$match->save();
				}
				$handicap = 0;
				$match = Match::getNew(Match::TYPE_MATCH);
				$match->name = 'Match '.$this->competition->id.'.'.$count;
				$match->position = $position++;
				$match->save();
				Yii::trace(print_r($match->errors, true) , 'StandardForMatch::updateMatches');
			}
			$match->add($registration);
			$handicap += $registration->golfer->handicap;
			$count++;
		}
		if($match) {
			$match->handicap = $handicap;
			$match->save();
		}
	}
	
	public function create() {
		Yii::trace('in', 'StandardForMatch::create');
		$this->updateMatches($this->competition->getRegistrations());
	}
	
	public function update() {
		Yii::trace('in', 'StandardForMatch::update');
		$this->updateMatches($this->competition->getRegistrationsNotIn(Match::TYPE_MATCH));
	}
	
	public function updateFromJson($match, $json) {
		foreach($json as $registration_id) {
			if($registration = Registration::findOne($registration_id)) {
				$match->add($registration);
			}
		}
	}
}
