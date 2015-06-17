<?php

namespace common\models\rule;

use Yii;
use yii\db\ActiveRecord;
use common\models\Rule;

/**
 *
 */
class Rank extends Rule {
	
	public function apply($competition) {
		if($points = $this->getPoints()->indexBy('position')->all()) {
			$position = 1;
			$tie = 0;
			$latest = null;
			foreach($competition->getScorecards()->andWhere(['status' => Scorecard::STATUS_RETURNED])->orderBy([$this->source_type => $this->source_direction])->each() as $scorecard) {
				if(!$latest) {
					$scorecard->{$this->destination_type} = $points[$position]->points;
					$scorecard->save();
				} else {
					if($scorecard->$score == $latest->$score) { // we have a tie
						// get the same points
						$scorecard->{$this->destination_type} = $points[$position]->points;
						$scorecard->save();
						$tie++;
					} else {
						$position += $tie + 1;
						$tie = 0;
						$scorecard->{$this->destination_type} = $points[$position]->points;
						$scorecard->save();
					}
				}
				$latest = $scorecard;
			}			
		}
	}	
	
}
