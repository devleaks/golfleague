<?php
/**
 * LatestMessages widget renders the last messages available on the website.
 *
 * @author Pierre M <devleaks.be@gmail.com>
 */

namespace common\widgets;

use Yii;
use yii\bootstrap\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class Scoreboard extends Scorecard {
	/**
	 * Main function.
	 *
	 * @return string HTML table
	 */
	public function run() {

		return $this->scorecard();
		
		return $this->render('_scores', [
			'dataProvider' => new ActiveDataProvider([
				'query' => $this->model->getScores(),
			]),		
		]);
	}
	
	public function init() {
		if($this->model->registration) { // competition
			$this->tees = $this->model->registration->tees;
		} else { // practice
			$this->tees = $this->model->tees;
		}
	}
	
	/**
	 * Option manipulation functions
	 */	
	public function getOption($v) {
		return ArrayHelper::getValue($this->options, $v);
	}

	public function setOption($o, $v) {
		$this->options[$o] = $v;
	}


	/**
	 *  Table structure
	 *
	 *    Position  Nom  1 .. 9 (f1) 10 .. 18 (f2) (b) Today  Thru  R1 .. RN  Total
	 *    1         2         match->holes             3      4      rounds   5
	 */
	protected function rules() {
		return self::STROKEPLAY;
	}
	protected function matches() {
		return null;
	}
	protected function colspan($offset = 0) {
		$colspan  = $offset;
		$colspan += 3; // pos name total, always shown
		if($this->getOption(self::HOLES)) 		$colspan += $this->tees->holes;
		if($this->getOption(self::ROUNDS))		$colspan += (count($this->matches()) + 1); // completed rounds
		if($this->getOption(self::TODAY))		$colspan += 2; // today, thru
		if($this->getOption(self::FRONTBACK))	$colspan += 2;
		return $colspan;
	}

	protected function span($text, $class) {
		return Html::tag('span', $text, ['class' => $class]);
	}

	/**
	 *	Table Headers & Footers
	 */
	private function row($data, $name, $display_name, $show_total = true, $rounds = false) {
		$t  = '<tr class="'.$name.'">';
		$t .= '<th class="labelr" colspan="2">'.$display_name.'</th>';
		
		$total = 0;
		for($i=0; $i<$this->tees->holes; $i++) {
			if($this->getOption(self::HOLES)) $t .= '<th class="hole">'.$data[$i].'</th>';
			$total += $data[$i];
		}
		
		if($name == self::PAR) {
			if($this->getOption(self::TODAY))
				$t .= '<th class="today" colspan="2"></th>';
			if($rounds > 1)
				$t .= '<th class="rounds" colspan="'.$rounds.'">'.Yii::t('igolf', 'ROUNDS').'</th>';
		} else
			$t .= '<th class="today" colspan="'.($rounds + ($this->getOption(self::TODAY) ? 2 : 0)).'"></th>';

		$t .= '<th class="total">'.($show_total ? $total : '').'</th>';
		$t .= '</tr>';
		return $t;	
	}
	
	protected function tees_info($rounds = false) {
		$lunit = "m.";//$lunit = $this->settings[GolfTurboSettings::UNIT_SYSTEM] == 'metric' ? 'm.' : 'yds';
		$r = '';
		if($this->getOption(self::HOLES)) {
			if($this->getOption(self::LENGTH))	$r .= $this->row($this->tees->lengths(), 'length', Yii::t('igolf', 'Length').'&nbsp;('.$lunit.')', true,  $rounds);
			if($this->getOption(self::SI))		$r .= $this->row($this->tees->sis(),     'si',     Yii::t('igolf', 'S.I.'),                   false, $rounds);
			if($this->getOption(self::PAR))		$r .= $this->row($this->tees->pars(),    'par',    Yii::t('igolf', 'Par'),                    true,  $rounds);
		}
		return $r;
	}
	
	protected function legend() {
		$p = $this->getOption(self::COLOR) ? 'c' : ($this->getOption(self::SHAPE) ? 's' : '');
		if($this->getOption(self::LEGEND)) $t .= self::legend(true, $p);		
	}
	
	protected function row_headings($abbrv = false, $vertical = false, $details = false) {
		$rounds = count($this->matches());
		$t  = '<tr class="headings">';
		if($vertical) {
			if($details) {
				$rules = ($this->rules() == self::STROKEPLAY);
				$t .= '<th>'.Yii::t('igolf', 'Hole').'</th>';
				if($rules) $t .= '<th class="gross">'.Yii::t('igolf', 'Gros').'</th>';
				$t .= '<th class="allowed">'.Yii::t('igolf', 'Alwd').'</th>';
				if($rules) $t .= '<th class="net">'.Yii::t('igolf', 'Net').'</th>';
				$t .= '<th class="stableford">'.Yii::t('igolf', 'Stbl').'</th>';
				if($rules) $t .= '<th class="to_par">'.Yii::t('igolf', 'To Par').'</th>';
				$t .= '<th>'.Yii::t('igolf', 'Par').'</th>';
				$t .= '</tr>';
			} else {
				$t .= '<th>'.Yii::t('igolf', 'Hole').'</th>';
				if($rounds > 1)
					for($r=0; $r<$rounds; $r++)
						$t .= '<th class="round">'.($r+1).'</th>';
				else
					$t .= '<th class="round">'.Yii::t('igolf', 'Score').'</th>';
				$t .= '<th>'.Yii::t('igolf', 'Par').'</th>';
				$t .= '</tr>';
			}
		} else { //horizontal
			if($abbrv) {
				$t .= '<th>'.Yii::t('igolf', 'Round').'</th>';
			} else {
				$t .= '<th>'.Yii::t('igolf', 'Pos').'</th>';
				$t .= '<th>'.Yii::t('igolf', 'Name').'</th>';
			}

			if($this->getOption(self::HOLES))
				for($i=0; $i<$this->tees->holes; $i++)
					$t .= '<th>'.($i+1).'</th>';
		
			if(!$abbrv) {
				if($this->getOption(self::TODAY)) {
					$t .= '<th>'.Yii::t('igolf', 'Today').'</th>';
					$t .= '<th>'.Yii::t('igolf', 'Thru').'</th>';
				}
				if($this->getOption(self::ROUNDS) && $rounds > 1) // do not show rounds if only one round...
					for($r=0; $r<$rounds; $r++)
						$t .= '<th class="round">'.($r+1).'</th>';
			}
			$t .= '<th class="total">Total</th>'; // always shown
		}//$vertical?
		$t .= '</tr>';
		return $t;	
	}



	protected function caption() {
		$competition = $this->model->registration->competition->getFullName().', '.Yii::$app->formatter->asDate($this->model->registration->competition->start_date);
		$golfer = $this->model->registration->golfer->name.' ('.$this->model->registration->golfer->handicap.')';
		$r  = '<caption>';
		$r .= $competition.' — '.$golfer;
		$r .= '</caption>';
		return $r;
	}

	protected function scores() {
		$t  = '<tr class="scores">';
		$t .= '<td>';
		$t .= 'ok';//print_r($this->tees, true);
		$t .= '</td>';
		$t .= '</tr>';
		return $t;
	}

	protected function scorecard() {
		$r = '<table class="table scorecard">';
		
		$r .= $this->caption();

		$r .= "<thead>";
		$this->setOption(self::HOLES, true); // force heading for scorecard
		$r .= $this->tees_info();
		$r .= $this->row_headings();
		$r .= '</thead>';
			
		$r .= '<tbody>';
		$r .= $this->scores();
		$r .= '</tbody>';
		
		$r .= '</table>';

		if(	$this->getOption(self::LEGEND) )
			$r .= $this->legend();

		return $r;
	}

}