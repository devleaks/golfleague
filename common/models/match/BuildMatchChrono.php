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
class BuildMatchChrono implements BuildMatch
{
	public function execute($competition) {
		$registrations = $competition->getRegistrations()->andWhere(['status' => Registration::STATUS_REGISTERED])->orderBy('created_at');
		$level = $competition->getLevel();
		$position = 1;
		$flip = true;
		foreach($registrations->each() as $registration) {			
			if($flip) {
				$match = Match::getNew(Match::TYPE_MATCH);
				$match->name = 'Match '.$competition->id;
				$match->position = $position++;
				$match->save();
				Yii::trace(print_r($match->errors, true) , 'BuildFlightChrono::execute');
				$match->refresh();
				$flip = false;
			} else
				$flip = true;

			$match->save();
			$match->refresh();
			Yii::trace('doing='.$registration->id.'='.$match->id.' at='.$position, 'BuildMatchByHandicap::execute');
			$match->add($registration);
		}
	}

	public static function addMatches($competition, $registrations) {
		$position = $competition->getFlights()->max('group.position');
		if(! intval($position) > 0) $position = 0;
		$position++;
		$flip = true;
		foreach($registrations->each() as $registration) {
			if(! $registration->getFlight()->exists() ) {
				if($flip) {
					$match = Match::getNew(Match::TYPE_MATCH);
					$match->name = 'Match '.$competition->id;
					$match->position = $position++;
					$match->save();
					$flip = false;
				} else
					$flip = true;
				$match->add($registration);
			}
		}
	}
}
