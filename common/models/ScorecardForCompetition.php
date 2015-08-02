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
	public function hasScore() {
		return $this->registration->competition->competition_type == Competition::TYPE_ROUND ? (($this->thru > 0) || ($this->status != self::STATUS_OPEN)) : true;
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

    /**
     *	Returns the score column corresponding to the competition's rule.
     *
     *	@param boolean $total_only Whether to return hole per hole score or total only
	 *	@return number|array()	Returns requested total score or array of hole per hole score.
     */
	public function getScoreFromRule($total_only = false) {
		$scores = null;
		switch($this->registration->competition->rule->source_type) {
			case self::ALLOWED:					$scores = $total_only ? $this->allowed_total()		: $this->allowed();			break;
			case self::SCORE_GROSS:				$scores = $total_only ? $this->score_total()		: $this->score();			break;
			case self::SCORE_NET:				$scores = $total_only ? $this->score_net_total()	: $this->score(true);		break;
			case self::SCORE_STABLEFORD:		$scores = $total_only ? $this->stableford_total()	: $this->stableford();		break;
			case self::SCORE_STABLEFORD_NET:	$scores = $total_only ? $this->stableford_net_total() : $this->stableford(true);	break;
			case self::SCORE_TOPAR:				$scores = $total_only ? $this->lastToPar()			: $this->toPar( 0 );		break;
			case self::SCORE_TOPAR_NET:			$scores = $total_only ? $this->lastToPar_net()		: $this->toPar_net( 0 );	break;
			case self::SCORE_POINTS:			$scores = $total_only ? $this->points_total()		: $this->points();			break;
		}
		//echo print_r($scores, true);
		return $scores;
	}

    /**
     *	Returns the score column corresponding to the competition's post-rule computation.
     *  If there is no 'post-rule', there is not computed score.
     *
	 *	@return number	Returns computed total score.
     */
	public function getScoreFromFinalRule() {
		$scores = null;
//		echo 'yep '.$this->registration->competition->finalRule->id.', '; // @bug?
		if($rule = Rule::findOne($this->registration->competition->final_rule_id))
			switch($rule->destination_type) {
				case self::SCORE_GROSS:				$scores = $this->score;				break;
				case self::SCORE_NET:				$scores = $this->score_net;			break;
				case self::SCORE_STABLEFORD:		$scores = $this->stableford;		break;
				case self::SCORE_STABLEFORD_NET:	$scores = $this->stableford_net; 	break;
				case self::SCORE_TOPAR:				$scores = $this->lastToPar();		break;
				case self::SCORE_TOPAR_NET:			$scores = $this->lastToPar_net();	break;
				case self::SCORE_POINTS:			$scores = $this->points;			break;
			}
		return $scores;
	}


}
