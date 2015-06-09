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
use yii\bootstrap\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Scorecard extends Widget {
	/**
	 * O P T I O N S
	 *
	 * Course */
	const PAR			= 'par';
	const SI			= 'si';
	const LENGTH		= 'length';

	/* Scores */
	const NINE			= 'nine';
	const BACK			= 'back';
	const GROSS			= 'gross';
	const NET			= 'net';
	const STABLEFORD	= 'stableford';
	const ALLOWED		= 'allowed';
	const TO_PAR		= 'to_par';
	const STROKEPLAY	= 'strokeplay';

	/* Columns */
	const TOTAL			= 'total';
	const TODAY			= 'today';
	const ROUND			= 'round';
	const ROUNDS		= 'rounds';
	const FRONTBACK		= 'fb';
	const HOLE			= 'hole';
	const HOLES			= 'holes';
	const CARDS			= 'cards';

	/* Appearance */
	const SPLITFLAP		= 'splitflap';
	const COLOR			= 'color';
	const SHAPE			= 'shape';
	const ALLOWED_ICON	= 'allowed_icon';
	const LEGEND		= 'legend';
	const FOOTER		= 'footer';
	const MATCH_NAME	= 'match_name';
	
	/* Other */
	const AUTO_REFRESH	= 'auto_refresh';
	const AUTO_REFRESH_RATE = 'auto_refresh_rate';

	/**
	 * V A R I A B L E S
	 *
	 * common\models\Scorecard Scorecard to display.
	 *
	 */
	public $model;
	
	/** array of name,value pairs of options */
	public $options;
	

	/**
	 * Main function.
	 *
	 * @return string HTML table
	 */
	public function run() {

		return $this->scorecard();
	}
	
	public function init() {
		$this->registerAssets();
		$this->setOption(self::HOLES, true); // force heading for scorecard, otherwise nothing might be displayed
	}
	
    /**
     * Register client assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        ScorecardAsset::register($view);
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
	 *	Table Headers & Footers
	 */
	public static function score_names() {
		return [
			-4 => 'condor',
			-3 => 'albatross',
			-2 => 'eagles',
			-1 => 'birdie',
			 0 => 'par',
			 1 => 'bogey',
			 2 => 'double_bogey',
			 3 => 'triple_bogey',
		];
	}

	public static function stat_name($id) {
		switch ($id) {
			case -4: return Yii::t('igolf', 'Condor'); break;
			case -3: return Yii::t('igolf', 'Albatross'); break;
			case -2: return Yii::t('igolf', 'Eagle'); break;
			case -1: return Yii::t('igolf', 'Birdy'); break;
			case  0: return Yii::t('igolf', 'Par'); break;
			case  1: return Yii::t('igolf', 'Bogey'); break;
			case  2: return Yii::t('igolf', 'Double bogey'); break;
			case  3: return Yii::t('igolf', 'Tripple bogey or worse'); break;
		}
	}

	public static function pretty_name($name) {
		switch($name) {
			case 'stableford':	$display_name = Yii::t('igolf', 'Stableford');	break;
			case 'net':			$display_name = Yii::t('igolf', 'Net');			break;
			case 'allowed':		$display_name = Yii::t('igolf', 'Allowed');		break;
			case 'to_par':		$display_name = Yii::t('igolf', 'To Par');		break;
			case 'gross':		$display_name = Yii::t('igolf', 'Gross');		break;
			default:			$display_name = $name;							break;
		}
		return $display_name;
	}

	public static function legend($numeric = false, $m = 'c') {
		$p = ($m == 'c') ? 'color c' : 'shape s';
		return $numeric ? '
			<table class="scorecard-legend">
			  <tr>
				<td><div class="'.$p.'-3">-3</div> '.Yii::t('igolf', 'or less').'</td>
				<td><div class="'.$p.'-2">-2</div></td>
				<td><div class="'.$p.'-1">-1</div></td>
				<td><div class="'.$p.'0">Par</div></td>
				<td><div class="'.$p.'1">+1</div></td>
				<td><div class="'.$p.'2">+2</div></td>
				<td><div class="'.$p.'3">+3</div> '.Yii::t('igolf', 'or more').'</td>
			  </tr>
			</table>' : '
			<table class="scorecard-legend">
			  <tr>
				<td class="'.$p.'-3">'.self::stat_name(-3).' '.Yii::t('igolf', 'or less').'</td>
				<td class="'.$p.'-2">'.self::stat_name(-2).'</td>
				<td class="'.$p.'-1">'.self::stat_name(-1).'</td>
				<td class="'.$p.'0">'. self::stat_name(0) .'</td>
				<td class="'.$p.'1">'. self::stat_name(1) .'</td>
				<td class="'.$p.'2">'. self::stat_name(2) .'</td>
				<td class="'.$p.'3">'. self::stat_name(3) .' '.Yii::t('igolf', 'or more').'</td>
			  </tr>
			</table>' ;
	}

	protected function print_legend() {
		$p = $this->getOption(self::COLOR) ? 'c' : ($this->getOption(self::SHAPE) ? 's' : '');
		if($this->getOption(self::LEGEND)) return self::legend(true, $p);		
	}
	
	private function print_header_split() {
		$output =  Html::beginTag('tr', ['class' => 'scorecard-split']);
		$output .= Html::tag('th', $this->model->tees->name);
		for($i=0; $i<$this->model->tees->holes; $i++) {
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

	private function print_headers() {
		$displays = [
			self::LENGTH => [
				'label'=> Yii::t('igolf', 'Length'),
				'data' => $this->model->tees->lengths(),
				'total' => true
			],
			self::SI => [
				'label'=> Yii::t('igolf', 'S.I.'),
				'data' => $this->model->tees->sis(),
				'total' => false
			],
			self::PAR => [
				'label'=> Yii::t('igolf', 'Par'),
				'data' => $this->model->tees->pars(),
				'total' => true
			],
		];

		$output = '';
		foreach($displays as $key => $display) {
			if($this->getOption($key)) {			
				$output .=  Html::beginTag('tr');
				$output .= Html::tag('th', $display['label'], ['class' => 'labelr']);
				for($i=0; $i<$this->model->tees->holes; $i++) {
					$output .= Html::tag('th', $display['data'][$i]);
				}
				if($display['total']) {
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
		$competition = $this->model->registration->competition->getFullName().', '.Yii::$app->formatter->asDate($this->model->registration->competition->start_date);
		$golfer = $this->model->registration->golfer->name.' ('.$this->model->registration->golfer->handicap.')';
		return Html::tag('caption', $competition.' — '.$golfer);
	}

	private function td_allowed($val, $what) {
		if($what == 'total')
			return Html::tag('td', $val);
		$i = $this->getOption(self::ALLOWED_ICON);
		return Html::tag('td', $i ? str_repeat($i,$val) : $val);
	}
	
	private function td_topar($val, $classname) {
		if(in_array($classname, ['hole','today']) && ($val !== "&nbsp;")) {
			$color = $this->getOption(self::COLOR);
			$dsp = $color ? abs($val) : $val;
		} else {
			$color = false;
			$dsp = $val;
		}
		return Html::tag('td', $dsp, ['class' => ( $color && ($val < 0) ) ? 'red' : null]);
	}
	
	private function td_score_color($score, $topar) {
		$prefix = $this->getOption(self::COLOR) ? 'color c' : ($this->getOption(self::SHAPE) ? 'shape s' : '');
		if($this->getOption(self::COLOR)||$this->getOption(self::SHAPE)) {
			$class = (abs($topar) > 4) ? (($topar > 0) ? $prefix."3" : $prefix."-4") : $prefix.$topar;
			$output =  Html::tag('td', $score, ['class' => $class]);
		} else
			$output =  Html::tag('td', $score);
		return $output;			
	}
	
	private function td_score_highlight($score, $topar, $name) {
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
	
	private function print_scores() {
		$displays = [
			self::ALLOWED => [
				'label' => Yii::t('igolf', 'Allowed'),
				'data' => $this->model->allowed(),
				'total' => true,
				'color' => true/*note:true displays as '• •', false displays as '2'*/,
			],
			self::GROSS => [
				'label' => Yii::t('igolf', 'Score'),
				'data' => $this->model->score(),
				'total' => true,
				'color' => true,
			],
			self::NET => [
				'label' => Yii::t('igolf', 'Net'),
				'data' => $this->model->score_net(),
				'total' => true,
				'color' => true,
			],
			self::STABLEFORD => [
				'label' => Yii::t('igolf', 'Stableford Net'),
				'data' => $this->model->stableford_net(),
				'total' => true,
				'color' => true,
			],
			self::TO_PAR => [
				'label' => Yii::t('igolf', 'To Par'),
				'data' => $this->model->to_par(),
				'total' => true,
				'color' => false
			],
		];
		
		$stableford_points = $this->model->registration ? $this->model->registration->competition->rule->stableford() : Rule::stableford();

		$output = '';
		foreach($displays as $key => $display) {
			if( $this->getOption($key) ) { 
				$output .=  Html::beginTag('tr', ['class' => $key]);
				$output .= Html::tag('td', $display['label'], ['class' => 'scorecard-label']);

				for($i=0; $i<$this->model->tees->holes; $i++)
					if($key == self::STABLEFORD)
						$output .= $this->td($key, 'hole', $display['data'][$i], array_search($display['data'][$i], $stableford_points));
					else
						$output .= $this->td($key, 'hole', $display['data'][$i], $display['data'][$i] - $this->model->tees->pars()[$i]);

				if($display['total']) {
					if($key == self::TO_PAR) {
						$output .= $this->td($key, 'today', $display['data'][$this->model->tees->holes - 1]);
						if($this->getOption(self::FRONTBACK)) {
							$output .= $this->td($key, 'today', $display['data'][8]);
							$output .= $this->td($key, 'today', $display['data'][17]);
						}
					} else {
						$output .= $this->td($key, 'total', array_sum($display['data']));
						if($this->getOption(self::FRONTBACK)) {
							$output .= $this->td($key, 'total', array_sum(array_slice($display['data'], 0, 9)));
							$output .= $this->td($key, 'total', array_sum(array_slice($display['data'], 9, 9)));
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

	protected function scorecard() {
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

}