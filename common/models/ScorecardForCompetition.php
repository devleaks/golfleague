<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;

/**
 * This is the model class for table "scorecards".
 */
class ScorecardForCompetition extends Scorecard
{
    /**
     * @inheritdoc
     */
	public function afterFind() {
		parent::afterFind();
		$this->tees = $this->registration->tees;
		$this->golfer = $this->registration->golfer;
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
		$golfer_str = $this->golfer->name.' ('.$this->golfer->handicap.')';
		return $where_str.' — '.$golfer_str;
	}

}
