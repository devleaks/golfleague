<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;

/**
 * This is the model class for table "scorecards".
 */
class Scorecard extends _Scorecard
{
	use Constant;
	
	/** Local commodity variables. Initialized afterFind.*/
	public $tees;
	/** Player can either be a golfer or a team */
	public $player;

	/** Scorecard types */
	const TYPE_COMPETITION	= 'COMPETITION';
	const TYPE_PRACTICE		= 'PRACTICE';

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
	const SCORE_POINTS		= 'points';
	const SCORE_TOPAR		= 'topar';
	const SCORE_TOPAR_NET	= 'topar_net';

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


    /**
     * @inheritdoc
     */
	public static function instantiate($row)
	{
	    switch ($row['scorecard_type']) {
	        case self::TYPE_COMPETITION:
	            return new ScorecardForCompetition();
	        case self::TYPE_PRACTICE:
	            return new ScorecardForPractice();
	        default:
	           return new self;
	    }
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
		$this->save();
	}

    /**
     * @inheritdoc
     */
	public function delete() {
		$this->deleteScores();

		return parent::delete();
	}

	/**
	 * Returns number of holes to play
	 */
	public function holes() {
		return 0;
	}
	
	/**
	 * Returns first hole to play
	 */
	public function startHole() {
		return 0;
	}
	
	/**
	 * Creates Score entry for each hole of the scorecard if none exists.
	 */
	public function makeScores() {
		if( !$this->hasDetails() && $this->tees) {
			$allowed = $this->player->allowed($this->tees);
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
					'allowed' => $allowed[$hole_num - 1],
				]);
				$score->save();
			}
		}
	}
	
	/**
	 * Returns a scorecard title
	 *
	 * @return string Scorecard title/label/caption for display
	 */
	public function getLabel() {
		return Yii::t('golf', 'Scorecard');
	}
	
	public function hasScore() {
		return $this->thru > 0;
	}
	
	/**
	 * Utility function used for development, do not use.
	 */
	protected function doAllowed() {
		if($this->tees) {
			$i = 0;
			$a = $this->player->allowed($this->tees);
			foreach($this->getScores()->joinWith('hole')->orderBy('hole.position')->each() as $score) {
				$score->allowed = $a[$i++];
				$score->save();
			}	
		}		
	}

	/**
	 * Get array of values for different scoring data.
	 */
	private function getHoleData($data, $net = false) {
		$r = [];
		$a = $net ? $this->allowed() : array_fill(0, 18, 0);
		$i = 0;
		foreach($this->getScores()->joinWith('hole')->orderBy('hole.position')->each() as $score) {
			$r[$i] = intval($score->$data);
			if($net && $r[$i] > 0)
				$r[$i] -= $a[$i];
			$i++;
		}
		return $r;
	}
	
	public function allowed() {
		return $this->getHoleData('allowed');
	}

	public function score($net = false) {
		return $this->getHoleData('score', $net);
	}

	public function stableford($net = false) {
		$rule = $this->registration ? $this->registration->competition->rule : new Rule(); // note: rule is required for matches
		$n = $this->score($net);
		$s = count($n) > 0 ? array_fill(0, count($n), null) : [];
		if($this->tees) {
			$p = $this->tees->pars();
			for($i = 0; $i< count($n); $i++) {
				if($n[$i] > 0) {
					$s[$i] = $rule->stablefordPoint($n[$i] - $p[$i]);
				}
				//Yii::trace($i.'=>net='.$n[$i].':par='.$p[$i].':stb='.$s[$i], 'Scorecard::stableford');
			}			
		}
		return $s;
	}

	public function toPar($start = 0, $net = false) {
		$n = $this->score($net);
		$s = count($n) > 0 ? array_fill(0, count($n), null) : [];
		if($this->tees) {
			$p = $this->tees->pars();
			$topar = $start;
			for($i = 0; $i< count($n); $i++) {
				if($n[$i] > 0) {
					$topar += ($n[$i] - $p[$i]);
					$s[$i] = $topar;
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

	public function lastToPar($net = false) {
		if($this->hasDetails()) {
			$to_par = $net ? $this->toPar_net() : $this->toPar();
			return $to_par[ $this->thruIndex() ];
		} else {
			if($this->tees) {
				$par = array_sum(array_slice($this->tees->pars(), 0, $this->thru));
				$scr = $net ?  $this->score_net_total() :  $this->score_total(); 
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
	 * For matchplay, computeMatchplay() compute up and down from scores. For strokeplay, computeMatchplay() returns the score rounded according to the rule.
	 * In both cases, handicap is taken into account according to the rule.
	 *
	 * @return 	array()	Returns hole by hole points of matchplay, or hole by hole score for strokeplay.
	 */
	public function points() {
		$rule = $this->registration ? $this->registration->competition->rule : new Rule(); // note: rule is required for matches
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
			return array_map(function($a) use ($r) { return round($a, $r); }, $this->score($rule->handicap));
		}
	}
	
	/**
	 * For matchplay, isWinner() determines if scorecard is winner in regard to opponent'score.
	 *
	 * @return 	boolean|null	Returns true if winner, false if looser, or null if draw.
	 *
	 */
	public function isWinner() {
		$winner = null;
		$rule = $this->registration ? $this->registration->competition->rule : new Rule(); // note: rule is required for matches
		if($rule->rule_type == Rule::TYPE_MATCHPLAY) {
			// Get opponent scorecard
			if($opponent = $this->getOpponent()) {
				if($opponent_scorecard = $opponent->getScorecard()) {
					$this_total = $this->points_total($rule->handicap);
					$opponent_total = $opponent_scorecard->points_total($rule->handicap);
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
		return $winner;
	}

	public function points_total() {
		$rule = $this->registration ? $this->registration->competition->rule : new Rule(); // note: rule is required for matches
		// Yii::trace('rounding:'.$rule->getRounding());
		return $this->hasDetails() ? array_sum($this->points()) : round($this->points, $rule->getRounding());
	}

	/**
	 * Get array of values for different scoring data.
	 */
	public function hasDetails() { // opposed to isCompetition()
		return $this->getScores()->exists();
	}
	
	public function upToDate() {
		$last_score_date = $this->getScores()->max('updated_at');
		Yii::trace($last_score_date.' <= '.$this->updated_at, 'Scorecard::upToDate');
		return $last_score_date <= $this->updated_at;
	}

	/**
	 * Compute scorecard scores from detailed scores
	 */
	public function updateScorecard() {
		if( $this->upToDate() || !$this->hasDetails() ) return;
		
		$score = $this->score();
		$thru = 0;
		while($thru < count($score) && intval($score[$thru]) > 0)
			$thru++;
			
		$this->thru = $thru;
		if($this->thru > 0) {
			
			$this->score = array_sum($score);
			$this->score_net = array_sum($this->score(true));
			$this->stableford = array_sum($this->stableford());
			$this->stableford_net = array_sum($this->stableford(true));
			$this->topar = array_sum($this->toPar());
			$this->topar_net = array_sum($this->toPar_net());
		
			$this->putts = array_sum($this->getHoleData('putts'));
			$this->penalty = array_sum($this->getHoleData('penalty'));
		
			$this->teeshot = array_sum($this->getHoleData('teeshot')) / $thru;
			$this->regulation = array_sum($this->getHoleData('regulation')) / $thru;
			

			$sand = $this->getHoleData('sand');
			$ok = 0; $nok = 0;
			for($i = 0; $i < count($sand); $i++) {
				if($sand[$i] === 0)
					$nok++;
				else if ($sand[$i] === 1)
					$ok++;
			}
			$this->sand = ($ok + $nok) > 0 ? round($ok / ($ok + $nok), 2) : 0;	
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
	 *	Validates consistency of all golf scores on card.
	 */
	public function validate() {
		
		return true;
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
					$this->points = array_sum($this->points());
				break;
			default:
				return $this->validate();
		}
		return $this->save();
	}


	public function getOpponent() {
		$opponent = null;
		if($r = $this->getRegistration()->one()) {
			if($m = $r->getMatch()->one()) {
				foreach($m->getRegistrations()->each() as $r2) {
					if($r2->id != $r->id) {
						$opponent = $r2;
						//Yii::trace('opponent is '.$opponent->id, "Scorecard::getOpponent");
					}
				}
			}
		}
		return $opponent;
	}
}
