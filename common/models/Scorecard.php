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
	public $golfer;

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

	/** Score types (i.e. column name) */
	const DIRECTION_ASC		= 'ASC';
	const DIRECTION_DESC	= 'DESC';
	
	/** Compute actions */
	const COMPUTE_GROSS_TO_NET = 'gross2net';


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
	            'id' => Yii::t('igolf', 'Scorecard'),
	        	'registration_id' => Yii::t('igolf', 'Registration'),
	            'practice_id' => Yii::t('igolf', 'Practice'),
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
		if( !$this->hasDetails() ) {
			$allowed = $this->golfer->allowed($this->tees);
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
		return Yii::t('igolf', 'Scorecard');
	}
	
	public function hasScore() { // opposed to isCompetition()
		return $this->thru > 0;
	}
	
	/**
	 * Utility function used for development, do not use.
	 */
	private function doAllowed() {
		$i = 0;
		$a = $this->golfer->allowed($this->tees);
		foreach($this->getScores()->joinWith('hole')->orderBy('hole.position')->each() as $score) {
			$score->allowed = $a[$i++];
			$score->save();
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
	
	public function score() {
		return $this->getHoleData('score');
	}

	public function score_net() {
		return $this->getHoleData('score', true);
	}

	public function allowed() {
		return $this->getHoleData('allowed');
	}

	public function stableford() {
		$rule = $this->registration ? $this->registration->competition->rule : new Rule(); // note: rule is required for matches
		$n = $this->score();
		$p = $this->tees->pars();
		$s = count($n) > 0 ? array_fill(0, count($n), null) : [];
		for($i = 0; $i< count($n); $i++) {
			if($n[$i] > 0) {
				$s[$i] = $rule->stablefordPoint($n[$i] - $p[$i]);
			}
			//Yii::trace($i.'=>net='.$n[$i].':par='.$p[$i].':stb='.$s[$i], 'Scorecard::stableford');
		}
		return $s;
	}

	public function stableford_net() {
		$rule = $this->registration ? $this->registration->competition->rule : new Rule(); // note: rule is required for matches
		$n = $this->score_net();
		$p = $this->tees->pars();
		$s = count($n) > 0 ? array_fill(0, count($n), null) : [];
		for($i = 0; $i< count($n); $i++) {
			if($n[$i] > 0) {
				$s[$i] = $rule->stablefordPoint($n[$i] - $p[$i]);
			}
			//Yii::trace($i.'=>net='.$n[$i].':par='.$p[$i].':stb='.$s[$i], 'Scorecard::stableford');
		}
		return $s;
	}

	public function toPar($start = 0) {
		$n = $this->score();
		$p = $this->tees->pars();
		$s = count($n) > 0 ? array_fill(0, count($n), null) : [];
		$topar = $start;
		for($i = 0; $i< count($n); $i++) {
			if($n[$i] > 0) {
				$topar += ($n[$i] - $p[$i]);
				$s[$i] = $topar;
			}
			//Yii::trace($i.'=>net='.$n[$i].':par='.$p[$i].':topar='.$s[$i], 'Scorecard::toPar');
		}
		return $s;
	}
	
	public function toPar_net($start = 0) {
		$n = $this->score_net();
		$p = $this->tees->pars();
		$s = count($n) > 0 ? array_fill(0, count($n), null) : [];
		$topar = $start;
		for($i = 0; $i< count($n); $i++) {
			if($n[$i] > 0) {
				$topar += ($n[$i] - $p[$i]);
				$s[$i] = $topar;
			}
			//Yii::trace($i.'=>net='.$n[$i].':par='.$p[$i].':topar='.$s[$i], 'Scorecard::toPar_net');
		}
		return $s;
	}
	
	public function lastToPar() {
		$to_par = $this->toPar();
		return $to_par[($this->thru() > 0 ? $this->thru() - 1 : 0)];
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
		if($this->upToDate()) return;
		
		$score = $this->score();
		$thru = 0;
		while($thru < count($score) && intval($score[$thru]) > 0)
			$thru++;
			
		$this->thru = $thru;
		if($thru > 0) {
			
			$this->score = array_sum($score);
			$this->score_net = array_sum($this->score_net());
			$this->stableford = array_sum($this->stableford());
			$this->stableford_net = array_sum($this->stableford_net());
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
				$allowed = array_sum($this->golfer->allowed($this->tees));
				$this->score_net = $this->score - $allowed;
				break;
			default:
				return $this->validate();
		}
		$this->save();
	}

}
