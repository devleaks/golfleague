<?php

namespace common\models\rule;

use Yii;
use yii\db\ActiveRecord;
use common\models\Rule;

/**
 * Copies the score from one column to another
 */	
class SumChildren extends Rule {
	
	public function apply($competition) {
		$params = $this->getParameters();
		$bestOf =  isset($params['bestOf']) ? intval($params['bestOf']) : null;
		Yii::trace('bestOf='.$bestOf);

		$children_competitions = $competition->getCompetitions()->select('id');
		
		foreach($competition->getScorecards()->andWhere(['status' => Scorecard::STATUS_RETURNED])->each() as $scorecard) {
			$registrations = intval($bestOf) > 0 ?
				Registration::find()
					->andWhere(['competition_id' => $children_competitions])
					->andWhere(['golfer_id' => $scorecard->registration->golfer_id])
					->orderBy([$this->source_type => $this->source_direction])
					->limit($bestOf) // limits to the best top n results
			:
				Registration::find()
					->andWhere(['competition_id' => $children_competitions])
					->andWhere(['golfer_id' => $scorecard->registration->golfer_id])
			;
			$sum_children = Scorecard::find()
						->andWhere(['registration_id' => $registrations])
						->sum($this->source_type);
			$scorecard->{$this->destination_type} = $sum_children;
			$scorecard->save();
		}			
	}
	
}
