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
				$match = new Match();
				$match->position = $position++;
				$flip = false;
			} else
				$flip = true;

			$match->save();
			$match->refresh();
			Yii::trace('doing='.$registration->id.'='.$match->id.' at='.$position, 'BuildMatchByHandicap::execute');
			$registration->match_id = $match->id;
			$registration->save();
		}
	}
}
