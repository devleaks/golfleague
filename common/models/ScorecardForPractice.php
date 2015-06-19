<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;

/**
 * This is the model class for table "scorecards" when scorecard_type = PRACTICE.
 */
class ScorecardForPractice extends Scorecard
{
    /**
     * @inheritdoc
     */
	public function afterFind() {
		parent::afterFind();
		$this->tees = $this->practice->tees;
		$this->golfer = $this->practice->golfer;
	}

    /**
     * @inheritdoc
     */
	public function holes() {
		return $this->practice->holes;
	}
	
    /**
     * @inheritdoc
     */
	public function startHole() {
		return $this->practice->start_hole;
	}
	
    /**
     * @inheritdoc
     */
	public function getLabel() {
		$where_str = $this->practice->course->getFullName();
		$golfer_str = $this->golfer->name.' ('.$this->golfer->handicap.')';
		return $where_str.' — '.$golfer_str;
	}
	
}
