<?php

namespace common\models\rule;

use common\models\Rule;
use common\models\Scorecard;

use Yii;
use yii\db\ActiveRecord;

/**
 *
 */
class Matchplay extends Rule {
	
	public function apply($competition) {
		foreach($competition->getScorecards()->each() as $scorecard) {
			$scorecard->compute(Scorecard::COMPUTE_MATCHPLAY);
		}
	}	
	
}
