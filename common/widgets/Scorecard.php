<?php
/**
 * LatestMessages widget renders the last messages available on the website.
 *
 * @author Pierre M <devleaks.be@gmail.com>
 */

namespace common\widgets;

use Yii;
use common\assets\ScorecardAsset;
use common\models\Rule;
use yii\base\Model;
use yii\bootstrap\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Scoredisplay extends Model {
	public $name;
	public $label;
	public $data;
	public $total;
	public $color;
}

class Scorecard extends _Scoretable {
	/**
	 * common\models\Scorecard Scorecard to display.
	 */
	public $scorecard;
	
	/**
	 * Main function.
	 *
	 * @return string HTML table
	 */
	public function run() {
		$r = Html::beginTag('table', ['class' => 'table scorecard']);
		
		$r .= $this->caption();

		$r .= Html::beginTag('thead');
		$r .= $this->print_headers();
		$r .= $this->print_header_split();
		$r .= Html::endTag('thead');
			
		$r .= Html::beginTag('tbody');
		$r .= $this->print_scores();
		$r .= Html::endTag('tbody');;
		
		$r .= Html::endTag('table');

		if(	$this->getOption(self::LEGEND) )
			$r .= $this->print_legend();

		return $r;
	}
	
	public function init() {
		$this->registerAssets();
		$this->setOption(self::HOLES, true); // force heading for scorecard, otherwise nothing might be displayed
	}
	
    /**
     * Register client assets
     */
    protected function registerAssets() {
        $view = $this->getView();
        ScorecardAsset::register($view);
    }

	/**
	 *	Table Headers & Footers
	 */
	protected function print_header_split() {
		$output =  Html::beginTag('tr', ['class' => 'scorecard-split']);
		$output .= Html::tag('th', $this->scorecard->tees->name);
		for($i=0; $i<$this->scorecard->tees->holes; $i++) {
			$output .= Html::tag('th', $i+1);
		}
		$output .= Html::tag('th', Yii::t('igolf', 'Total'));
		if($this->getOption(self::FRONTBACK)) {
		$output .= Html::tag('th', Yii::t('igolf', 'Front'));
		$output .= Html::tag('th', Yii::t('igolf', 'Back'));
		}
		$output .= Html::endTag('tr');
		return $output;
	}

	protected function print_headers() {
		$displays = [
			self::LENGTH => new Scoredisplay([
				'label'=> Yii::t('igolf', 'Length'),
				'data' => $this->scorecard->tees->lengths(),
				'total' => true
			]),
			self::SI => new Scoredisplay([
				'label'=> Yii::t('igolf', 'S.I.'),
				'data' => $this->scorecard->tees->sis(),
				'total' => false
			]),
			self::PAR => new Scoredisplay([
				'label'=> Yii::t('igolf', 'Par'),
				'data' => $this->scorecard->tees->pars(),
				'total' => true
			]),
		];

		$output = '';
		foreach($displays as $key => $display) {
			if($this->getOption($key)) {			
				$output .=  Html::beginTag('tr');
				$output .= Html::tag('th', $display->label, ['class' => 'labelr']);
				for($i=0; $i<$this->scorecard->tees->holes; $i++) {
					$output .= Html::tag('th', $display->data[$i]);
				}
				if($display->total) {
					$output .= Html::tag('th', array_sum($display['data']));
					if($this->getOption(self::FRONTBACK)) {
						$output .= Html::tag('th', array_sum(array_slice($display['data'], 0, 9)));
						$output .= Html::tag('th', array_sum(array_slice($display['data'], 9, 9)));
					}
				} else {
					$output .= Html::tag('th', '', ['colspan' => $this->getOption(self::FRONTBACK) ? 3 : 1]);
				}
				$output .= Html::endTag('tr');
			}
		}		
		return $output;
	}

	protected function caption() {
		return Html::tag('caption', $this->scorecard->getLabel());
	}

	protected function td_allowed($val, $what) {
		if($what == self::TOTAL)
			return Html::tag('td', $val);
		$i = $this->getOption(self::ALLOWED_ICON);
		return Html::tag('td', $i ? str_repeat($i,$val) : $val);
	}
	
	protected function td_topar($val, $classname) {
		if(in_array($classname, [self::HOLE,self::TODAY]) && ($val !== "&nbsp;")) {
			$color = $this->getOption(self::COLOR);
			$dsp = $color ? abs($val) : $val;
		} else {
			$color = false;
			$dsp = $val;
		}
		return Html::tag('td', $dsp, ['class' => ( $color && ($val < 0) ) ? 'red' : null]);
	}
	
	protected function td_score_color($score, $topar) {
		$prefix = $this->getOption(self::COLOR) ? 'color c' : ($this->getOption(self::SHAPE) ? 'shape s' : '');
		if($this->getOption(self::COLOR)||$this->getOption(self::SHAPE)) {
			$class = (abs($topar) > 4) ? (($topar > 0) ? $prefix."3" : $prefix."-4") : $prefix.$topar;
			$output =  Html::tag('td', $score, ['class' => $class]);
		} else
			$output =  Html::tag('td', $score);
		return $output;			
	}
	
	protected function td_score_highlight($score, $topar, $name) {
		if( ($name != 'stableford') && (intval($score) != 0) ) {
				$output = $this->td_score_color($score, $topar);
		} else if ( ($name == "stableford") && ($score !== null) ) {
				$output = $this->td_score_color($score, $topar);
		} else // nothing to display
				$output = Html::tag('td', '&nbsp;');
		return $output;
	}
		
    protected function td($name, $str, $val, $topar = 0) {
		switch($name) {
			case self::TO_PAR:
				$output = $this->td_topar( ($val === '' ? '&nbsp;' : $val), $str);
				break;
			case self::ALLOWED:
				$output =$this->td_allowed($val, $str);
				break;
			default:
				$output = $this->td_score_highlight($val, $topar, $name);
		}
		return $output;
	}
	
	protected function print_scores() {
		$displays = [
			self::ALLOWED => new Scoredisplay([
				'label' => Yii::t('igolf', 'Allowed'),
				'data' => $this->scorecard->allowed(),
				'total' => true,
				'color' => true/*note:true displays as '• •', false displays as '2'*/,
			]),
			self::GROSS => new Scoredisplay([
				'label' => Yii::t('igolf', 'Score'),
				'data' => $this->scorecard->score(),
				'total' => true,
				'color' => true,
			]),
			self::NET => new Scoredisplay([
				'label' => Yii::t('igolf', 'Net'),
				'data' => $this->scorecard->score_net(),
				'total' => true,
				'color' => true,
			]),
			self::STABLEFORD => new Scoredisplay([
				'label' => Yii::t('igolf', 'Stableford'),
				'data' => $this->scorecard->stableford(),
				'total' => true,
				'color' => true,
			]),
			self::STABLEFORD_NET => new Scoredisplay([
				'label' => Yii::t('igolf', 'Stableford Net'),
				'data' => $this->scorecard->stableford_net(),
				'total' => true,
				'color' => true,
			]),
			self::TO_PAR => new Scoredisplay([
				'label' => Yii::t('igolf', 'To Par'),
				'data' => $this->scorecard->toPar(),
				'total' => true,
				'color' => false
			]),
		];
		
		$stableford_points = $this->scorecard->registration ? $this->scorecard->registration->competition->rule->getStablefordPoints() : Rule::getStablefordPoints();

		$output = '';
		foreach($displays as $key => $display) {
			if( $this->getOption($key) ) { 
				$output .=  Html::beginTag('tr', ['class' => $key]);
				$output .= Html::tag('td', $display->label, ['class' => 'scorecard-label']);

				for($i=0; $i<$this->scorecard->tees->holes; $i++)
					if(in_array($key, [self::STABLEFORD, self::STABLEFORD_NET]))
						$output .= $this->td($key, self::HOLE, $display->data[$i], array_search($display->data[$i], $stableford_points));
					else
						$output .= $this->td($key, self::HOLE, $display->data[$i], $display->data[$i] - $this->scorecard->tees->pars()[$i]);

				if($display->total) {
					if($key == self::TO_PAR) {
						$output .= $this->td($key, self::TODAY, $display->data[$this->scorecard->tees->holes - 1]);
						if($this->getOption(self::FRONTBACK)) {
							$output .= $this->td($key, self::TODAY, $display->data[8]);
							$output .= $this->td($key, self::TODAY, $display->data[17]);
						}
					} else {
						$output .= $this->td($key, self::TOTAL, array_sum($display->data));
						if($this->getOption(self::FRONTBACK)) {
							$output .= $this->td($key, self::TOTAL, array_sum(array_slice($display->data, 0, 9)));
							$output .= $this->td($key, self::TOTAL, array_sum(array_slice($display->data, 9, 9)));
						}
					}
				} else {
					$output .= Html::tag('td', '', ['colspan' => $this->getOption(self::FRONTBACK) ? 3 : 1]);
				}

				$output .= Html::endTag('tr');
			} 
		}//foreach
		return $output;
	}

}