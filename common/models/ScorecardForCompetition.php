<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;

/**
 * This is the model class for table "scorecards" when scorecard_type = COMPETITION.
 */
class ScorecardForCompetition extends Scorecard
{
    /**
     * @inheritdoc
     */
	public function afterFind() {
		parent::afterFind();
		if($this->registration) {
			if(! ($this->tees = $this->registration->tees)) {
				if($competition = $this->registration->competition) {
					if($competition->course) {
						$this->tees = $competition->course->getFirstTees();
					}
				}
			}
			if($competition = $this->registration->competition) {
				$this->player = $this->registration->competition->isTeamCompetition() ? $this->registration->team : $this->registration->golfer;
			} else { // @todo ?
				$this->player = $this->registration->golfer;
			}
		}
	}

    /**
     * @inheritdoc
     */
	public function holes() {
		return $this->registration->competition->holes;
	}
	
    /**
     * @inheritdoc
     */
	public function startHole() {
		return 1; // $this->registration->flight->start_hole;
	}
	
    /**
     * @inheritdoc
     */
	public function getLabel() {
		$where_str = $this->registration->competition->getFullName().', '.Yii::$app->formatter->asDate($this->registration->competition->start_date);
		$golfer_str = $this->player->name.' ('.$this->player->handicap.')';
		return $where_str.' — '.$golfer_str;
	}

}
