<?php

namespace common\models\rule;

use common\models\Rule;
use common\models\Scorecard;

use Yii;
use yii\db\ActiveRecord;

/**
 *
 */
class Rank extends Rule {
	
	public function apply($competition) {
		if($points = $this->getPoints()->indexBy('position')->all()) {
			$position = 1;
			$max_position = count($points);
			$tie = 0;
			$latest = null;
			foreach($competition->getScorecards()->andWhere(['status' => Scorecard::STATUS_RETURNED])->orderBy($this->source_type.' '.$this->source_direction)->each() as $scorecard) {
				if(!$latest) {
					$scorecard->{$this->destination_type} = $points[$position]->points;
					$scorecard->save();
				} else {
					if($scorecard->{$this->source_type} == $latest->{$this->source_type}) { // we have a tie
						// get the same points
						$scorecard->{$this->destination_type} = $position < $max_position ? $points[$position]->points : 0;
						$scorecard->save();
						$tie++;
					} else {
						$position += $tie + 1;
						$tie = 0;
						$scorecard->{$this->destination_type} = $position < $max_position ? $points[$position]->points : 0;
						$scorecard->save();
					}
				}
				Yii::trace('doing '.$scorecard->golfer->name);
				$latest = $scorecard;
			}			
		}
	}	
	
}
