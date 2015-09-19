<?php

namespace common\models\match;

use common\models\match\BuildMatch;
use common\models\Competition;
use common\models\Match;
use common\models\Golfer;
use common\models\Registration;

use Yii;

/**
 * This class builds matches according to time of registration (?).
 *
 */
class BuildMatchForTeam implements BuildMatch
{
	public function execute($competition) {
		$teams = $competition->getTeams();
		$level = $competition->getLevel();
		$position = 1;
		$flip = true;
		foreach($teams->each() as $team) {			
			if($flip) {
				$match = Match::getNew(Match::TYPE_MATCH);
				$match->name = 'Match '.$competition->id;
				$match->position = $position++;
				$match->save();
				Yii::trace(print_r($match->errors, true) , 'BuildMatchForTeams::execute');
				$match->refresh();
				$flip = false;
			} else
				$flip = true;

			$match->save();
			$match->refresh();
			Yii::trace('doing='.$team->id.'='.$match->id.' at='.$position, 'BuildMatchForTeams::execute');
			$match->add($team);
		}
	}

	public static function addMatches($competition, $registrations) {
		$position = $competition->getMatches()->max('position');
		if(! intval($position) > 0) $position = 0;
		$position++;
		$flip = true;
		foreach($registrations->each() as $registration) {
			if($team = $registration->getMatch()->one() ) {
				if(! $team->getMatch()->exists() ) {
					if($flip) {
						$match = Match::getNew(Match::TYPE_MATCH);
						$match->name = 'Match '.$competition->id;
						$match->position = $position++;
						$match->save();
						$flip = false;
					} else
						$flip = true;
					$match->add($team);
				}
			}
		}
	}
}
