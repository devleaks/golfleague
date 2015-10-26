<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;

/**
 * This is the model class for table "scorecards".
 */
class Scorecard extends base\Scorecard
{
	use Constant;
	
	/** Scorecard statuses */
	const STATUS_OPEN 		= 'OPEN';
	const STATUS_RETURNED	= 'RETURNED';
	const STATUS_DISQUAL	= 'DISQUA';
	const STATUS_NOSHOW		= 'NOSHOW';

	/** Score types (i.e. column name) */
	const SCORE_GROSS		= 'score';
	const SCORE_NET			= 'score_net';
	const SCORE_STABLEFORD	= 'stableford';
	const SCORE_STABLEFORD_NET	= 'stableford_net';
	const SCORE_TOPAR		= 'topar';
	const SCORE_TOPAR_NET	= 'topar_net';
	const SCORE_POINTS		= 'points';
	const SCORE_TIEBREAK	= 'tie_break';
	
	/** allowed is not a score */
	const ALLOWED			= 'allowed';

	/** Score ordering, yeah, in golf sometimes the guy with the fewest points wins */
	const DIRECTION_ASC		= 'ASC';
	const DIRECTION_DESC	= 'DESC';
	
	/** Compute actions */
	const COMPUTE_GROSS_TO_NET	= 'gross2net';
	const COMPUTE_MATCHPLAY 	= 'matchplay';


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); /* mysql datetime format is ‘AAAA-MM-JJ HH:MM:SS’*/},
                ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
			parent::rules(),
			[
	            [['thru'], 'in', 'range' => Hole::validNumber()],
	            [['status'], 'in', 'range' => array_keys(self::getConstants('STATUS_'))],
        	]
		);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
			parent::attributeLabels(),
			[
	            'id' => Yii::t('golf', 'Scorecard'),
	        	'registration_id' => Yii::t('golf', 'Registration'),
	            'practice_id' => Yii::t('golf', 'Practice'),
        	]
		);
    }


	public function getLabelColors() {
		return [
			self::STATUS_OPEN => 'primary',
			self::STATUS_RETURNED => 'success',
			self::STATUS_NOSHOW => 'warning',
			self::STATUS_DISQUAL => 'danger',
		];
	}
	
    /**
     * @return common\models\Registration
     */
    public function getRegistration()
    {
        return $this->hasOne(Registration::className(), ['scorecard_id' => 'id']);
    }

    /**
     * @return common\models\Practice
     */
    public function getPractice()
    {
        return $this->hasOne(Practice::className(), ['scorecard_id' => 'id']);
    }

	/**
	 * Checks whether detail data exist for this scorecard.
	 * @return boolean
	 */
	public function hasDetails() { // opposed to isCompetition()
		return $this->getScores()->exists();
	}
	
	/**
	 * Checks whether detail data exist for this scorecard.
	 * @return boolean
	 */
	public function upToDate() {
		if($this->hasDetails()) {
			$last_score_date = $this->getScores()->max('updated_at');
			Yii::trace($last_score_date.' <= '.$this->updated_at, 'Scorecard::upToDate');
			return $last_score_date <= $this->updated_at;
		}
		return true;
	}

	/**
	 * Cascade delete scores attached to this scorecard
	 */	
	public function deleteScores() {
		foreach($this->getScores()->each() as $score)
			$score->delete();
		$this->score = null;
		$this->points = null;
		$this->putts = null;
		$this->teeshot = null;
		$this->thru = null;
		$this->penalty = null;
		$this->regulation = null;
		$this->score_net = null;
		$this->stableford = null;
		$this->stableford_net = null;
		$this->topar = null;        
		$this->topar_net = null;  
		$this->status = self::STATUS_OPEN;     
		return $this->save();
	}

    /**
     * @inheritdoc
     */
	public function delete() {
		$this->deleteScores();

		return parent::delete();
	}

	/**
	 * Returns first hole to play
	 */
	public function startHole() {
		return $this->registration ? $this->registration->competition->start_hole : $this->practice->start_hole;
	}
	
	/**
	 * Returns number of holes to play
	 */
	public function holes() {
		return $this->registration ? $this->registration->competition->holes : $this->practice->holes;
	}
	
	/**
	 * Returns tees from which competitor plays
	 */
	public function getTees() {
		return $this->registration ? $this->registration->tees : $this->practice->tees;
	}
	
	/**
	 * Returns scorecard "player". Returns something that implement Opponent interface (golfer, team).
	 *
	 * @return Opponent
	 */
	public function getPlayer() {
		if($this->practice)
			return $this->practice->golfer;

		return $this->registration->competition->rule->team_score ? $this->registration->team : $this->registration->golfer;
	}
	
	/**
	 * Get scores ordered by hole position
	 */
	public function getScoreWithHoles() {
		return $this->getScores()->joinWith('hole')->orderBy('hole.position');
	}

	/**
	 * Returns a scorecard title
	 *
	 * @return string Scorecard title/label/caption for display
	 */
	public function getLabel() {
		$label = '';
		if($this->registration) {
			$label = $this->registration->competition->getFullName().', '.Yii::$app->formatter->asDate($this->registration->competition->start_date);
			$golfer_str = $this->player->name.' ('.$this->player->handicap.')';
			$label .= ' — '.$golfer_str;
		} else {
			$label = $this->practice->course->getFullName();
			$golfer_str = $this->player->name.' ('.$this->player->handicap.')';
			$label .= ' — '.$golfer_str;
		}
		return $label;
	}
	
	/**
	 * Check if current scorecard uses handicap calculation.
	 */
	public function useHandicap() {
		return $this->practice || $this->registration->competition->rule->handicap;
	}


	/**
	 * Tries to guess if data is a valid score (can be 0 but cannot be null)
	 *
	 * @return boolean
	 */
	protected function isValidScore($s) { // tricky to detect 0 scores in Stableford
		return intval($s) > 0 || ($s === '0') || ($s === 0);
	}
	
	

	/**
	 * Creates Score entry for each hole of the scorecard if none exists.
	 */
	public function makeScores() {
		if( !$this->hasDetails() && $this->tees ) {
			$holes = $this->tees->getHoles()->orderBy('position')->indexBy('position')->all();
			$hole_count = count($holes);
			
			$holes_to_play = $this->holes();
			$start_hole = $this->startHole();

			Yii::trace('toplay='.$holes_to_play.', tees='.$this->tees->holes.', start='.$start_hole, 'Scorecard::makeScores');

			if($holes_to_play > $this->tees->holes) {
				return;
			}

			for($i = 0; $i < $holes_to_play; $i++) {
				$hole_num = $start_hole + $i % $hole_count;
				$score = new Score([
					'scorecard_id' => $this->id,
					'hole_id' => $holes[$hole_num]->id,
				]);
				$score->save();
			}
			if($this->useHandicap())
				$this->computeAllowed();
		}
	}
	
	/**
	 * Determine is scorecard has score.
	 *
	 */
	public function hasScore() {
		return $this->thru > 0;
	}
	
	
	public function getRule() {
		return $this->registration ? $this->registration->competition->rule : new Rule(); // note: rule is required for matches
	}
	/**
	 * Compute allowed strokes for scorecard and for each hole.
	 */
	protected function computeAllowed() {
		if($this->tees) {
			$this->exact_handicap = $this->player->handicap;
			$a = $this->player->allowed($this->tees);
			$this->handicap = array_sum($a); // playing handicap
			$this->save();
			if($this->hasDetails()) {				
				$i = 0;
				foreach($this->getScoreWithHoles()->each() as $score) {
					$score->allowed = $a[$i++];
					$score->save();
				}
			}
		}		
	}

	/**
	 * Get array of values for different scoring data.
	 */
	private function getHoleData($data, $net = false) {
		$column = $net ? $data.'_net' : $data;
		$r = [];
		foreach($this->getScoreWithHoles()->each() as $score) {
			$r[] = $score->$column;
		}
		return $r;
	}
	
	public function allowed() {
		return $this->getHoleData(self::ALLOWED);
	}

	public function score($net = false) {
		return $this->getHoleData(self::SCORE_GROSS, $net);
	}

	public function stableford($net = false) {
		return $this->getHoleData(self::SCORE_STABLEFORD, $net);
	}

	public function tie_break() {
		return $this->getHoleData(self::SCORE_TIEBREAK);
	}

	public function points() {
		return $this->getHoleData(self::SCORE_POINTS);
	}

	public function toPar($start = 0, $net = false) {
		$rule = $this->getRule();
		$stableford = $rule->data_type == Rule::DATA_STABLEFORD;
		$n = $stableford ? $this->stableford($net) : $this->score($net);
		$s = count($n) > 0 ? array_fill(0, count($n), null) : [];
		if($this->tees && (count($n) > 0)) {
			$p = $this->tees->pars();
			$topar = $start;
			for($i = 0; $i< count($n); $i++) {
				if($this->isValidScore($n[$i])) {
					if($stableford) {
						$topar += ($n[$i] - $rule->stablefordPoints[0]);
						$s[$i] = $topar;
					} else {
						$topar += ($n[$i] - $p[$i]);
						$s[$i] = $topar;
					}
				}
				//Yii::trace($i.'=>net='.$n[$i].':par='.$p[$i].':topar='.$s[$i], 'Scorecard::toPar');
			}
		}
		return $s;
	}
	
	public function toPar_net($start = 0) {
		return $this->toPar($start, true);
	}

	/**
	 * Returns array index for last played hole (thru). Returns 0 if not score yet.
	 *
	 * @return integer Array index of last hole played.
	 */
	public function thruIndex() {
		return $this->thru > 0 ? $this->thru - 1 : 0;
	}

	/**
	 * Total scores
	 */
	public function allowed_total($to = 18) {
		return $this->hasDetails() ? array_sum(array_slice($this->allowed(), 0, $to - 1)) : $this->allowed;
	}

	public function score_total() {
		return $this->hasDetails() ? array_sum($this->score()) : $this->score;
	}

	public function score_net_total() {
		return $this->hasDetails() ? array_sum($this->score(true)) : $this->score_net;
	}

	public function stableford_total() {
		return $this->hasDetails() ? array_sum($this->stableford()) : $this->stableford;
	}

	public function stableford_net_total() {
		return $this->hasDetails() ? array_sum($this->stableford(true)) : $this->stableford_net;
	}

	public function points_total() {
		$rule = $this->getRule();
		// Yii::trace('rounding:'.$rule->getRounding());
		return $this->hasDetails() ? array_sum($this->points()) : round($this->points, $rule->getRounding());
	}

	public function tie_break_total() {
		return $this->hasDetails() ? array_sum($this->tie_break()) : $this->tie_break;
	}

	public function lastToPar($net = false) {
		if($this->hasDetails()) {
			$to_par = $net ? $this->toPar_net() : $this->toPar();
			return $to_par[ $this->thruIndex() ];
		} else {
			if($this->tees) {
				$rule = $this->getRule();
				$stableford = $rule->data_type == Rule::DATA_STABLEFORD;
				if($stableford) {
					$par = $this->thru * $rule->stablefordPoints[0];
					$scr = $net ?  $this->stableford_net_total() :  $this->stableford_total(); 
				} else {
					$par = array_sum(array_slice($this->tees->pars(), 0, $this->thru));
					$scr = $net ?  $this->score_net_total() :  $this->score_total(); 
				}
				//Yii::trace('ThruIdx='.$this->thruIndex().', Par='.$par.', Score='.$scr, 'Scorecard::lastToPar');
				return $this->thru > 0 ? $scr - $par : null;
			}
		}
		return null;
	}
	
	public function lastToPar_net() {
		return $this->lastToPar(true);
	}
	
	/**
	 * @return 	array()	Returns hole by hole points of matchplay, or hole by hole score for strokeplay.
	 */
	public function matchPoints() {
		$rule = $this->getRule();
		$r = $rule->getRounding();
		if($rule->rule_type == Rule::TYPE_MATCHPLAY) {
			$this_ret = [];
			$opponent_ret = [];
			$winner = null;
			// Get opponent scorecard
			if($opponent = $this->getOpponent()) {
				if($opponent_scorecard = $opponent->getScorecard()) {
					$this_score = $this->score($rule->handicap);
					$opponent_score = $opponent_scorecard->score($rule->handicap);
					$score = 0;
					// Compute Up/Down array
					for($i = 0; $i < min($this->thru, $opponent_scorecard->thru); $i++) {
						$this_ret[$i] = $this_score[$i] == $opponent_score[$i] ? 0.5 :
									($this_score[$i] > $opponent_score[$i] ? 1 : 0);
						$opponent_ret[$i] = $this_score[$i] == $opponent_score[$i] ? 0.5 :
									($this_score[$i] < $opponent_score[$i] ? 1 : 0);
					}
				}
			}
			return $this_ret;
		} else { // Strokeplay
			return array_map(function($a) use ($r) { return round($a, $r); }, $this->getHoleData('points', $rule->handicap));
		}
	}
	
	/**
	 * For matchplay, isWinner() determines if scorecard is winner in regard to opponent'score.
	 *
	 * @return 	boolean|null	Returns true if winner, false if looser, or null if draw.
	 */
	public function isWinner() {
		$winner = null;
		$rule = $this->getRule(); // note: rule is required for matches
		if($rule->rule_type == Rule::TYPE_MATCHPLAY) {
			// Get opponent scorecard
			if($opponent = $this->getOpponent()) {
				if($opponent_scorecard = $opponent->getScorecard()) {
					$this_total = $this->points_total($rule->handicap);
					$opponent_total = $opponent_scorecard->points_total($rule->handicap);
					//Yii::trace('tie:'.$this->tie_break.'vs.'.$opponent_scorecard->tie_break, 'Scorecard::isWinner');
					$winner = ($this_total > $opponent_total) ?
								true :
								($opponent_total > $this_total ?
									false :
									($this->tie_break > $opponent_scorecard->tie_break ?
										true :
										($this->tie_break < $opponent_scorecard->tie_break ?
											false :
											null)));
				}
			}
		}
		//Yii::trace('return:'.($winner ? 't' : 'f'), 'Scorecard::isWinner');
		return $winner;
	}

	/**
	 * Compute scorecard scores from detailed scores
	 */
	protected function guessThru() {
		$score = $this->getScoreFromRule();
		//Yii::trace('Score '.print_r($score, true), 'Scorecard::updateScorecard');
		$thru = 0;
		while($thru < count($score) && $this->isValidScore($score[$thru]))
			$thru++;
			
		Yii::trace('Thru '.$thru, 'Scorecard::updateScorecard');
		return $thru;
	}
	
	public function updateScorecard() {
		if( $this->upToDate() || !$this->hasDetails() ) return;
		
		$this->thru = $this->guessThru();
		if($this->thru > 0) {	
			$this->score = array_sum($this->score());
			$this->score_net = array_sum($this->score(true));
			$this->stableford = array_sum($this->stableford());
			$this->stableford_net = array_sum($this->stableford(true));
			$this->topar = array_sum($this->toPar());
			$this->topar_net = array_sum($this->toPar_net());
			$this->points = array_sum($this->points());
			$this->tie_break = array_sum($this->tie_break());
		
			$this->putts = array_sum($this->getHoleData('putts'));
			$this->penalty = array_sum($this->getHoleData('penalty'));
		
			$this->teeshot = array_sum($this->getHoleData('teeshot')) / $thru;
			$this->regulation = array_sum($this->getHoleData('regulation')) / $thru;
			

			$sand = $this->getHoleData('sand');
			$ok = 0; $tot = 0;
			for($i = 0; $i < count($sand); $i++) {
				if(isValidScore($sand[$i]))
					$tot++;
				if ($sand[$i])
					$ok++;
			}
			$this->sand = $tot > 0 ? round($ok / $tot, 2) : 0;	
		}
		
		$this->validate();
		Yii::trace('errors='.print_r($this->errors, true), 'Scorecard::updateScorecard');

		return $this->save();
	}
	

	public function getStatistics() {
		if(!$this->tees)
			return [];

		$stat_min = -4;
		$stat_max = 4;
		$stats = array_fill($stat_min, $stat_max - $stat_min + 1, 0);
		$n = $this->score();
		$p = $this->tees->pars();
		for($i = 0; $i < $scorecard->holes(); $i++) {			
			if($score[$i] > 0) {
				$id = $n[$i] - $p[$i];
				if($id >= $stat_min && $id <= $stat_max) {
					$stats[$id]++;
				}
			}
		}
		return $stats;
	}
	
	/**
	 *	Returns matchplay's opponent.
	 */
	public function getOpponent() {
		if($r = $this->getRegistration()->one()) {
			return $r->getOpponent();
		}
		return null;
	}

    /**
     *	Returns the score column corresponding to the competition's rule.
     *
     *	@param boolean $total_only Whether to return hole per hole score or total only
	 *	@return number|array()	Returns requested total score or array of hole per hole score.
     */
	protected function getScoreFromRuleInternal($rule, $total_only) {
		$scores = null;
		Yii::trace('Source '.$rule->source_type.($rule->handicap ? ' WITH handicap' : ' NO handicap'), 'Scorecard::getScoreFromRuleInternal');
		switch($rule->source_type) {
			case self::ALLOWED:					$scores = $total_only ? $this->allowed_total()			: $this->allowed();				break;
			case self::SCORE_GROSS:				$scores = $total_only ? $this->score_total()			: $this->score();				break;
			case self::SCORE_NET:				$scores = $total_only ? $this->score_net_total()		: $this->score(true);			break;
			case self::SCORE_STABLEFORD:		$scores = $total_only ? $this->stableford_total()		: $this->stableford();			break;
			case self::SCORE_STABLEFORD_NET:	$scores = $total_only ? $this->stableford_net_total()	: $this->stableford(true);		break;
			case self::SCORE_TOPAR:				$scores = $total_only ? $this->lastToPar()				: $this->toPar( 0 );			break;
			case self::SCORE_TOPAR_NET:			$scores = $total_only ? $this->lastToPar_net()			: $this->toPar_net( 0 );		break;
			case self::SCORE_POINTS:			$scores = $total_only ? $this->points_total()			: $this->points();				break;
		}
		return $scores;
	}

    /**
     *	Returns the score column corresponding to the competition's rule.
     *
	 *	@return array()	Returns array of hole per hole score.
     */
	public function getScoreFromRule() {
		return $this->getScoreFromRuleInternal($this->registration->competition->rule, false);
	}

    /**
     *	Returns the score column corresponding to the competition's rule.
     *
	 *	@return number	Returns requested total score.
     */
	public function getTotalFromRule() {
		return $this->getScoreFromRuleInternal($this->registration->competition->rule, true);
	}

    /**
     *	Returns the score column corresponding to the competition's post-rule computation.
     *  If there is no 'post-rule', there is not computed score.
     *
	 *	@return number	Returns computed total score.
     */
	public function getScoreFromFinalRule($total_only = false) {
		if($rule = Rule::findOne($this->registration->competition->final_rule_id))
			return $this->getScoreFromRuleInternal($rule, $total_only);
		return null;
	}

	/**
	 *
	 */
	public function compute($what = null) {
		switch($what) {
			case self::COMPUTE_GROSS_TO_NET:
				$allowed = array_sum($this->player->allowed($this->tees));
				$this->score_net = $this->score - $allowed;
				break;
			case self::COMPUTE_MATCHPLAY:
				if($this->hasDetails())
					$this->points = array_sum($this->matchPoints());
				break;
			default:
				return $this->validate();
		}
		return $this->save();
	}


}
