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
	const TYPE_COMPETITION = 'COMPETITION';
	const TYPE_PRACTICE = 'PRACTICE';

	/** Scorecard statuses */
	const STATUS_CREATED = 'CREATED';
	const STATUS_ONGOING = 'ONGOING';
	const STATUS_RETURNED = 'RETURNED';
	const STATUS_DISQUAL = 'DISQUA';
	const STATUS_NOSHOW = 'NOSHOW';
	
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
     *
     * Populates a couple more local vars
     */
	public function afterFind() {
		parent::afterFind();
		if($this->scorecard_type == self::TYPE_COMPETITION) {
			$this->tees = $this->registration->tees;
			$this->golfer = $this->registration->golfer;
		} else {
			$this->tees = $this->practice->tees;
			$this->golfer = $this->practice->golfer;
		}
		Yii::trace('init2: '.$this->id);
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
		$this->to_par = null;        
		$this->save();
	}

	public function delete() {
		$this->deleteScores();

		return parent::delete();
	}
	
	/**
	 * Creates Score entry for each hole of the scorecard if none exists.
	 */
	public function makeScores() {
		if( !$this->hasDetails() ) {
			$a = $this->golfer->allowed($this->tees);
			$i = 0;
			foreach($this->tees->getHoles()->orderBy('position')->each() as $hole) {
				$score = new Score([
					'scorecard_id' => $this->id,
					'hole_id' => $hole->id,
					'allowed' => $a[$i++],
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
		if($this->scorecard_type == self::TYPE_COMPETITION) {
			$where_str = $this->registration->competition->getFullName().', '.Yii::$app->formatter->asDate($this->registration->competition->start_date);
		} else {
			$where_str = $this->practice->course->getFullName();
		}
		$golfer_str = $this->golfer->name.' ('.$this->golfer->handicap.')';
		return $where_str.' — '.$golfer_str;
	}
	
	public function hasScore() { // opposed to isCompetition()
		return $this->thru > 0;
	}
	
	/**
	 * Utility function used for development, do not use.
	 */
	private function doAllowed() {
		$i = 0;
		$h = new HandicapEGA();
		$a = $h->allowed($this->tees, $this->golfer);
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
			}
			$s[$i] = $topar;
			Yii::trace($i.'=>net='.$n[$i].':par='.$p[$i].':topar='.$s[$i], 'Scorecard::to_par');
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
			}
			$s[$i] = $topar;
			Yii::trace($i.'=>net='.$n[$i].':par='.$p[$i].':topar='.$s[$i], 'Scorecard::to_par');
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
		return $last_score_date <= $this->updated_at;
	}

	public function compute() {
		if($this->upToDate()) return;
		

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

}
