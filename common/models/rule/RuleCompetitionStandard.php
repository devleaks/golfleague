<?php

namespace common\models\rule;

use common\models\Point;
use common\models\Competition;
use common\models\Registration;
use yii\helpers\ArrayHelper;

/**
 * This is the interface for applying rules to competitions.
 *
 */
class RuleCompetitionStandard implements RuleInterface
{
	public $competition;
	
	public function __construct($competition) {
		$this->competition = $competition;
	}
		
	public function doPositions() {
		return false;
	}

	public function doPoints() { // sum points for each children competition that has points
		$registrations = $this->competition->getRegistrations();
		$competitions = $this->competition->getCompetitions()
								->andWhere(['status' => [Competition::STATUS_READY, Competition::STATUS_CLOSED, Competition::STATUS_OPEN]])
								->select(['id'])
								->asArray()
								->all();
								
		foreach($registrations->each() as $reg) {
			$points = 0;
			$score = 0;
			$score_net = 0;
			foreach($competitions as $comp) {
				$compReg = Registration::find()
							->andWhere(['competition_id' => $comp])
							->andWhere(['golfer_id' => $reg->golfer_id])
							->one();

				if($compReg) {
					$points += $compReg->points;
					$score += $compReg->score;
					$score_net += $compReg->score_net;
				}
			}
			
			$reg->points = $points;
			$reg->score = $score;
			$reg->score_net = $score_net;
			$reg->save();
		}		
		return true;
	}
}
