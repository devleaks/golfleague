<?php
/**
 * Scorecard widget renders a player's scorecard for a single round.
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
	
	public $colors;
	
	/**
	 * Main function.
	 *
	 * @return string HTML table
	 */
	public function run() {
		if(!$this->scorecard->hasDetails())
			return Yii::t('igolf', 'No detailed scorecard.');
		
		return $this->print_table(['class' => 'table scorecard']);
	}
	
	public function init() {
		$this->registerAssets();
		$this->setOption(self::HOLES, true); // force heading for scorecard, otherwise nothing might be displayed
	}
	
    /**
     * Register client assets
     */
    protected function registerAssets() {
		$this->registerCss();
        $view = $this->getView();
        ScorecardAsset::register($view);
    }

	protected function registerCss() {
		if($this->colors) {
			$view = $this->getView();
			$css = <<<ENDofCSS
table.scorecard .c3,table.scorecard-legend .c3{background-color: {$this->colors[3]};}
table.scorecard .c2,table.scorecard-legend .c2{background-color: {$this->colors[2]};}
table.scorecard .c1,table.scorecard-legend .c1{background-color: {$this->colors[1]};}
table.scorecard .c0,table.scorecard-legend .c0{background-color: {$this->colors[0]};}
table.scorecard .c-1,table.scorecard-legend .c-1{background-color: {$this->colors[-1]};}
table.scorecard .c-2,table.scorecard-legend .c-2{background-color: {$this->colors[-2]};}
table.scorecard .c-3,table.scorecard-legend .c-3{background-color: {$this->colors[-3]};}
table.scorecard .c-4,table.scorecard-legend .c-4{background-color: {$this->colors[-4]};}
ENDofCSS;
			$view->registerCss($css);
		}
	}

	/**
	 *	Table Headers & Footers
	 */
	protected function caption() {
		return Html::tag('caption', $this->scorecard->getLabel());
	}

	protected function print_header_split() {
		$output =  Html::beginTag('tr', ['class' => 'scorecard-split']);
		$output .= Html::tag('th', $this->scorecard->tees->name);
		for($i=0; $i<$this->scorecard->holes(); $i++) {
			$output .= Html::tag('th', $i+1);
		}
		$output .= Html::tag('th', Yii::t('igolf', 'Total'));
		if($this->getOption(self::FRONTBACK)) {
			$output .= Html::tag('th', Yii::t('igolf', 'Front'));
			if($this->scorecard->holes() > 9)
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
				for($i=0; $i<$this->scorecard->holes(); $i++) {
					$output .= Html::tag('th', $display->data[$i]);
				}
				if($display->total) {
					$output .= Html::tag('th', array_sum($display->data));
					if($this->getOption(self::FRONTBACK)) {
						$output .= Html::tag('th', array_sum(array_slice($display->data, 0, 9)));
						if($this->scorecard->holes() > 9)
							$output .= Html::tag('th', array_sum(array_slice($display->data, 9, 9)));
					}
				} else {
					$output .= Html::tag('th', '', ['colspan' => $this->getOption(self::FRONTBACK) ? 3 : 1]);
				}
				$output .= Html::endTag('tr');
			}
		}		
		return $output;
	}

	protected function print_header() {
		$r  = $this->print_headers();
		$r .= $this->print_header_split();
		return $r;
	}

	protected function print_scores() {
		
		$displays = array(
			self::ALLOWED => new Scoredisplay([
				'label' => Yii::t('igolf', 'Allowed'),
				'data' => $this->scorecard->allowed(),
				'total' => true,
				'color' => true/*note:true displays as '• •', false displays as '2'*/,
			]),
			self::SCORE => new Scoredisplay([
				'label' => Yii::t('igolf', 'Score'),
				'data' => $this->scorecard->score(),
				'total' => true,
				'color' => true,
			]),
			self::SCORE_NET => new Scoredisplay([
				'label' => Yii::t('igolf', 'Net'),
				'data' => $this->scorecard->score(true),
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
				'data' => $this->scorecard->stableford(true),
				'total' => true,
				'color' => true,
			]),
			self::TO_PAR => new Scoredisplay([
				'label' => Yii::t('igolf', 'To Par'),
				'data' => $this->scorecard->toPar(),
				'total' => true,
				'color' => false
			]),
			self::TO_PAR_NET => new Scoredisplay([
				'label' => Yii::t('igolf', 'To Par Net'),
				'data' => $this->scorecard->toPar_net(),
				'total' => true,
				'color' => false
			]),
		);
		
		$rule = $this->scorecard->registration ? $this->scorecard->registration->competition->rule : new Rule();
		$stableford_points = $rule->getStablefordPoints();

		$output = print_r($this->scorecard->score(), true); // '';
		foreach($displays as $key => $display) {
			if( $this->getOption($key) ) { 
				$output .=  Html::beginTag('tr', ['class' => $key]);
				$output .= Html::tag('td', $display->label, ['class' => 'scorecard-label']);

				for($i=0; $i<$this->scorecard->holes(); $i++) {					
					if(in_array($key, [self::STABLEFORD, self::STABLEFORD_NET])) {
						$output .= $this->td($key, self::HOLE, $display->data[$i], array_search($display->data[$i], $stableford_points));
					} else {
						$output .= $this->td($key, self::HOLE, $display->data[$i], $display->data[$i] - $this->scorecard->tees->pars()[$i]);
					}
				}

				if($display->total) { // @TODO: Not correct: play with start_hole and holes to get proper index! Front might be the back if started on hole 10.
					if(in_array($key, [self::TO_PAR, self::TO_PAR_NET])) {
						$thru_idx = $this->scorecard->thru > 0 ? $this->scorecard->thru - 1 : 0;
						$output .= $this->td($key, self::TODAY, $display->data[$thru_idx]);
						if($this->getOption(self::FRONTBACK)) {
							$output .= $this->td($key, self::TODAY, $display->data[8]);
							if($this->scorecard->holes() > 9)
								$output .= $this->td($key, self::TODAY, $display->data[17]);
						}
					} else {
						$output .= $this->td($key, self::TOTAL, $rule->stablefordNine($display->data));
						if($this->getOption(self::FRONTBACK)) {
							if(in_array($key, [self::STABLEFORD, self::STABLEFORD])) {
								$output .= $this->td($key, self::TOTAL, array_sum(array_slice($display->data, 0, 9)));
							} else {
								$output .= $this->td($key, self::TOTAL, array_sum(array_slice($display->data, 0, 9)));
							}
							if($this->scorecard->holes() > 9)
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