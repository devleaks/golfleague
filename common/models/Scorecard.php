<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;

use common\models\handicap\HandicapEGA;

/**
 * This is the model class for table "scorecards".
 */
class Scorecard extends _Scorecard
{
	use Constant;

	/** Scorecard statuses */
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
            	[['tees_id'], 'required'],
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
	            'competition_id' => Yii::t('igolf', 'Match'),
        	]
		);
    }

	/**
	 * Cascade delete scores attached to this scorecard
	 */	
	public function delete() {
		foreach($this->getScores()->each() as $score)
			$score->delete();

		return parent::delete();
	}
	
	/**
	 * Creates Score entry for each hole of the scorecard if none exists.
	 */
	public function makeScores() {
		if( !$this->hasDetails() ) {
			$h = new HandicapEGA();
			$a = $h->allowed($this->tees, $this->golfer);
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
	 * Get array of values for different scoring data.
	 */
	private function getHoleData($data) {
		$r = [];
		foreach($this->getScores()->joinWith('hole')->orderBy('hole.position')->each() as $score) {
			$r[] = $score->$data;
		}
		return $r;
	}
	
	public function score() {
		return $this->getHoleData('score');
	}

	public function score_net() {
		$r = $this->getHoleData('score');
		$a = $this->allowed();
		for($i = 0; $i< count($r); $i++) {
			if($r[$i] > 0)
				$r[$i] -= $a[$i];
			//Yii::trace($i.'=>'.$r[$i].' -= '.$a[$i], 'Scorecard::net');
		}
		return $r;
	}


	public function doAllowed() {
		$i = 0;
		$h = new HandicapEGA();
		$a = $h->allowed($this->tees, $this->golfer);
		foreach($this->getScores()->joinWith('hole')->orderBy('hole.position')->each() as $score) {
			$score->allowed = $a[$i++];
			$score->save();
		}
		
	}
	public function allowed() {
		return $this->getHoleData('allowed');
	}

	public function stableford() {
		$rule = $this->registration ? $this->registration->competition->rule : new Rule(); // note: rule is required for matches
		$n = $this->score();
		$p = $this->tees->pars();
		$s = array_fill(0, count($n), null);
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
		$s = array_fill(0, count($n), null);
		for($i = 0; $i< count($n); $i++) {
			if($n[$i] > 0) {
				$s[$i] = $rule->stablefordPoint($n[$i] - $p[$i]);
			}
			//Yii::trace($i.'=>net='.$n[$i].':par='.$p[$i].':stb='.$s[$i], 'Scorecard::stableford');
		}
		return $s;
	}

	public function to_par() {
		$n = $this->score_net();
		$p = $this->tees->pars();
		$s = array_fill(0, count($n), null);
		$topar = 0;
		for($i = 0; $i< count($n); $i++) {
			if($n[$i] > 0) {
				$topar += ($n[$i] - $p[$i]);
			}
			$s[$i] = $topar;
			Yii::trace($i.'=>net='.$n[$i].':par='.$p[$i].':topar='.$s[$i], 'Scorecard::to_par');
		}
		return $s;
	}

	/**
	 * Get array of values for different scoring data.
	 */
	public function isPractice() { // opposed to isCompetition()
		return !($this->registration_id > 0);
	}

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

}
