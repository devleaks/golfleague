<?php

namespace common\models\rule;

use common\models\Competition;
use common\models\Registration;
use common\models\Rule;
use common\models\Scorecard;

use Yii;
use yii\db\ActiveRecord;

/**
 * Sum points from all direct children competitions. Children competition must be in CLOSED state to be taken into account.
 *
 * @param common\models\Competition Competition to apply rule on
 *
 */	
class SumChildren extends Rule {
	
	/** @param integer $bestOf Sort score by rule'source_direction and only keeps "bestOf" scores (example: only keep best 3 scores out of all rounds played). */
	public $bestOf;
	
	
	public function apply($competition) {		
		$params = $this->getParameters();
		$this->bestOf =  isset($params['bestOf']) ? intval($params['bestOf']) : null;

		$children_competitions = $competition->getCompetitions()
									->andWhere(['status' => Competition::STATUS_CLOSED])
									->select('id');
		Yii::trace('children comp='.print_r($children_competitions->asArray()->all(), true));

		foreach($competition->getScorecards()->andWhere(['status' => Scorecard::STATUS_OPEN])->each() as $scorecard) {
			Yii::trace('scorecard='.$scorecard->id);
			$registrations = Registration::find()
				->andWhere(['competition_id' => $children_competitions])
				->andWhere(['golfer_id' => $scorecard->registration->golfer_id]);
				
			$children_scorecards = Scorecard::find()
						->andWhere(['registration_id' => $registrations->select('id')])
						->andWhere(['status' => Scorecard::STATUS_RETURNED]);

			if(intval($this->bestOf) > 0) {
				$children_scorecards->orderBy([$this->source_type => $this->source_direction])
									->limit($this->bestOf);
			}

			$count_children = clone $children_scorecards;
			
			$sum_children = $children_scorecards->sum($this->source_type);
						
			$scorecard->{$this->destination_type} = $sum_children;
			$scorecard->rounds = $count_children->count();
			$scorecard->save();
		}
		Yii::trace('bestOf='.$this->bestOf);
	}
	
}
