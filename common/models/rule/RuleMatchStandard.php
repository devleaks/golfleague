<?php

namespace common\models\rule;

use common\models\Point;
use common\models\Registration;
use yii\helpers\ArrayHelper;

/**
 * This is the interface for applying rules to competitions.
 *
 */
class RuleMatchStandard implements RuleInterface
{
	public $competition;
	
	public function __construct($competition) {
		$this->competition = $competition;
	}
		
	public function doPositions() {
		$regs = $this->competition->getRegistrations()->where(['status' => Registration::STATUS_CONFIRMED])->orderBy('score');
		
		$position_accumulator = 1;
		$position = 1;
		$previous = null;
		foreach($regs->each() as $player) {
			if($previous != null) { // first elem
				if($player->score == $previous->score) {
					$position_accumulator++;
				} else {
					$position += $position_accumulator;
					$position_accumulator = 1;
				}
			}
			$player->position = $position;
			$player->points = null; // invalidate
			$player->save();		
			$previous = $player;
		}//foreach
		return true;
	}

	public function doPoints() {
		$points = ArrayHelper::map( Point::find()->where(['rule_id' => $this->competition->rule_id])->orderBy('position')->all() , 'position', 'points');
		$regs = $this->competition->getRegistrations();
		foreach($regs->each() as $player) {
			$player->points = $points[$player->position];
			$player->save();
		}		
		return true;
	}
}
