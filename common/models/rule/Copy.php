<?php

namespace common\models\rule;

use Yii;
use yii\db\ActiveRecord;
use common\models\Rule;

/**
 * Copies the score from one column to another
 */	
class Copy extends Rule {
	
	public function apply($competition) {
		foreach($competition->getScorecards()->andWhere(['status' => Scorecard::STATUS_RETURNED])->each() as $scorecard) {
			$scorecard->{$this->destination_type} = $scorecard->{$this->source_type};
		}			
	}
	
}
