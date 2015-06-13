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

class _Scoretable extends Widget {
	/**
	 * O P T I O N S
	 *
	 * Course */
	const PAR			= 'par';
	const SI			= 'si';
	const LENGTH		= 'length';

	/* Scores */
	const GROSS			= 'gross';
	const NET			= 'net';
	const STABLEFORD	= 'stableford';
	const STABLEFORD_NET	= 'stableford_net';
	const ALLOWED		= 'allowed';
	const TO_PAR		= 'to_par';
	const POINT			= 'points';

	/** Play */
	const STROKEPLAY	= 'strokeplay';
	const NINE			= 'nine';
	const BACK			= 'back';

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
	 ** array of name,value pairs of options */
	public $options;
	

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
	
	protected function print_header_split() {
	}

	protected function print_headers() {
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

	protected function td_allowed($val, $what) {
		if($what == 'total')
			return Html::tag('td', $val);
		$i = $this->getOption(self::ALLOWED_ICON);
		return Html::tag('td', $i ? str_repeat($i,$val) : $val);
	}
	
	protected function td_topar($val, $classname) {
		if(in_array($classname, ['hole','today']) && ($val !== "&nbsp;")) {
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
	
}