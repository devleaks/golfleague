<?php

namespace common\models\match;

use common\models\Competition;
use common\models\Match;
use common\models\Team;

use Yii;
use yii\base\Model;

/**
 * This is the interface for match building algorithms.
 *
 */
class StandardForTeamMatch extends Model implements BuildMatchInterface
{
	public $competition;
		
	private function updateMatches($teams){
		$position = $this->competition->getMatches()->max('group.position');
		if(! intval($position) > 0) $position = 0;
		$position++;
		$count = 0;
		$match = null;
		foreach($teams->each() as $team) {
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
			foreach($team->getRegistrations()->each() as $registration) {
				$match->add($registration);
				$handicap += $registration->golfer->handicap;
			}
			$count++;
		}
		if($match) {
			$match->handicap = $handicap;
			$match->save();
		}
	}
	
	protected function getTeamsNotInMatches() { //@todo
		return Team::find()->andWhere(['id' => 0]);
	}
	
	public function create() {
		Yii::trace('in', 'StandardForTeamMatch::create');
		$this->updateMatches($this->competition->getTeams());
	}
	
	public function update() {
		Yii::trace('in', 'StandardForTeamMatch::update');
		$this->getTeamsNotInMatches();
	}
	
	public function updateFromJson($match, $json) { //@toto: need to update handicap as well?
		Yii::trace(print_r($json, true), 'StandardForTeamMatch::updateFromJson');
		foreach($json as $team_id) {
			if($team = Team::findOne($team_id)) {
				foreach($team->getRegistrations()->each() as $registration) {
					$match->add($registration);
				}
			}
		}
	}
}
