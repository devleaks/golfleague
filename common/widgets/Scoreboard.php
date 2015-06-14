<?php
/**
 * LatestMessages widget renders the last messages available on the website.
 *
 * @author Pierre M <devleaks.be@gmail.com>
 */

namespace common\widgets;

use Yii;
use common\assets\ScoreboardAsset;
use common\models\Competition;
use common\models\Rule;
use yii\base\Model;
use yii\bootstrap\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Scoreline extends Model {
	public $scorecard;
	public $golfer;
	public $pos;
	public $curr;
	public $rounds;
	public $round;
	public $thru;
	public $today;
	public $total;
	public $stats;

	public static function compareGolfScoreToPar($a, $b) {
		$sa = intval($a->topar);
		$sb = intval($b->topar);
		return ($sa == $sb) ? ($a->thru < $b->thru) : ($sa > $sb);
	}
	
	public static function compareGolfScore($a, $b) {
		$sa = intval($a->total);
		$sb = intval($b->total);
		return ($sa == $sb) ? ($a->thru < $b->thru) : ($sa < $sb);
	}
	
}


class Scoreboard extends _Scoretable {
	/** common\models\Competition Competition to display. */
	public $competition;
	
	/** integer Maximum number of golfer displayed on scoreboard */
	public $maxGolfer = 10;
	
	/** common\models\Match Current match of competition. */
	protected $match;

	/** Starting tee set */
	protected $tees;
	
	/** common\widgets\Scoreline[] */
	protected $scoreline;
	
	/**
	 * Main function.
	 *
	 * @return string HTML table
	 */
	public function run() {
		$this->match = $this->competition->currentMatch();
		// check options:
		// if 9 holes, FRONTBACK is irrelevant.
		if($this->competition->status != Competition::STATUS_READY )
			return Yii::t('igolf', 'Competition has not started yet.');
		
		if($this->competition->competition_type == Competition::TYPE_MATCH && !$this->tees = $this->competition->getTees())
			return Yii::t('igolf', 'Competition has no starting tees set.');
			
		$this->prepare_scorecards();
			
		if($this->getOption(self::AUTO_REFRESH))
			$refresh_data = json_encode([
				'competition' => $this->id(),
				'refresh' => $this->show(self::AUTO_REFRESH_RATE),
				'options' => implode(',', $this->valid_options)
			]);
		else
			$refresh_data = $this->getId();

		$options = ['class' => 'scoreboard'];
		if($this->getOption(self::AUTO_REFRESH))
			Html::addCssClass($options, 'scoreboard-auto-refresh');
		if($this->getOption(self::SPLITFLAP))
			Html::addCssClass($options, 'scoreboard-splitflap');
		if($this->getOption(self::CARDS))
			Html::addCssClass($options, 'scoreboard-cards');
		if($this->scorecard->holes() == 9)
			Html::addCssClass($options, 'scoreboard-nine');
		
		$options['data-holes'] = $this->competition->holes;
		$options['data-ajaxgolfleague'] = $refresh_data;		
		
		$r = Html::beginTag('table', $options);
		
		$r .= $this->caption();

		$r .= Html::beginTag('thead');
		$r .= $this->print_headers();
		$r .= $this->print_header_split();
		$r .= Html::endTag('thead');
			
		$r .= Html::beginTag('tbody');
		$r .= $this->print_scores();
		$r .= Html::endTag('tbody');;

		if(	$this->getOption(self::FOOTER) ) {
			$r .= Html::tag('tfoot', $this->print_footer());
		}
		
		$r .= Html::endTag('table');

		if(	$this->getOption(self::LEGEND) ) {
			$r .= $this->print_legend();
		}

		return $r;
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
        ScoreboardAsset::register($view);
    }

	protected function print_header_split() {
		$output =  Html::beginTag('tr', ['class' => 'scorecard-split']);
		$output .= Html::tag('th', $this->competition->tees->name, ['colspan' => 2]);
		for($i=0; $i<$this->competition->tees->holes; $i++) {
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
			self::LENGTH => [
				'label'=> Yii::t('igolf', 'Length'),
				'data' => $this->competition->tees->lengths(),
				'total' => true
			],
			self::SI => [
				'label'=> Yii::t('igolf', 'S.I.'),
				'data' => $this->competition->tees->sis(),
				'total' => false
			],
			self::PAR => [
				'label'=> Yii::t('igolf', 'Par'),
				'data' => $this->competition->tees->pars(),
				'total' => true
			],
		];

		$output = '';
		foreach($displays as $key => $display) {
			if($this->getOption($key)) {			
				$output .=  Html::beginTag('tr');
				$output .= Html::tag('th', $display['label'], ['class' => 'labelr', 'colspan' => 2]);
				for($i=0; $i<$this->competition->tees->holes; $i++) {
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

	protected function print_footer() {
		$output  =  Html::beginTag('tr', ['class' => 'last-updated']);

		$str = Yii::t('igolf', 'Last updated at {0}.', date('G:i:s'));
		$str .= $this->getOption(self::AUTO_REFRESH) ? '' : ' '.Yii::t('igolf', '(Automatic refresh disabled.)');

		$output .= Html::tag('td', $str, ['colspan' => $this->colspan(-2)]);
		$output .=  Html::endTag('tr');
		return $output;
	}

	protected function caption() {
		$competition = $this->competition->getFullName().', '.Yii::$app->formatter->asDate($this->competition->start_date);
		return Html::tag('caption', $competition);
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

	private function totals($name, $display_name, $position, $scoreline) {
		$output = '';
		return $output;
	}

	private function print_score($name, $position, $scoreline, $label = '') {
		$output = '';
		if(!$scoreline) {
			$output .= Html::beginTag('tr', ['class' => 'player-error '.$name]);
			$output .= Html::tag('td', self::pretty_name($name), ['class' => 'igolf-label']);
			$output .= Html::tag('td', Yii::t('igolf', 'No scorecard'), ['colspan' => 9]);
			$output .= Html::tag('td', 0, ['class' => 'total']);
			$output .= Html::endTag('tr');;
			return $output;
		}
		$output .= Html::beginTag('tr', ['class' => 'player '.$name]);
		
		
		/* header */
		$display_name = self::pretty_name($name);
		switch($name) {
			case self::STABLEFORD:	$scores = $scoreline->scorecard->stableford();	break;
			case self::NET:			$scores = $scoreline->scorecard->score_net();	break;
			case self::ALLOWED:		$scores = $scoreline->scorecard->allowed();	break;
//			case self::TO_PAR:		$scores = $scoreline->to_par( $this->competition->rounds() > 1 ? $this->lite[$pid]['topar'][$scoreline->match()->id()] : 0 ); break;
			case self::TO_PAR:		$scores = $scoreline->scorecard->toPar( 0 ); break;
			case self::GROSS:
			default:				$scores = $scoreline->scorecard->score();		break;
		}
		
		$pid = $scoreline->scorecard->golfer->id;

		$debug = print_r($scores, true);		
		//$output .= $debug;

		$old_use_splitflap = $this->getOption(self::SPLITFLAP);
		if($position < 0) {
			$output .= Html::tag('td', (($position == -2) ? $display_name : $label), ['class' => 'igolf-label']);
			$this->options->setOption(self::SPLITFLAP, false); // temporaily
		} else {
			$output .= $this->td($name, 'pos-'.$pid, $position);
			$output .= Html::tag('td', $scoreline->scorecard->golfer->name.' '.
						$score_type = Html::tag('span', Yii::t('igolf', $display_name), ['class' => 'score-type']), ['class' => 'igolf-name']);
		}

		/* holes */
		$refs   = ($name != self::STABLEFORD) ? $scores : $scoreline->scorecard->score_net(); //FROM_GROSS: $scoreline->scorecard->score()

		$pars = $scoreline->scorecard->tees->pars();
		$stableford_points = $this->competition->rule->getStablefordPoints();

		$total = 0;
		for($i=0; $i<min($this->competition->holes,count($scores)); $i++) { // @todo
			$total += $scores[$i];
			if($this->getOption(self::HOLES)) {
				if($this->competition->rule->isStableford()) {					
					$topar = array_search($scores[$i], $stableford_points);
					$output .= $this->td($name, 'hole-'.$i.'-'.$pid, $scores[$i], $topar);
				} else
					$output .= $this->td($name, 'hole-'.$i.'-'.$pid, $scores[$i], ($refs[$i] - $pars[$i]));
			}
		}

		/* today */
		if($this->getOption(self::TODAY) && $position > 0) {
			if($name == self::TO_PAR)	
				$output .= $this->td(self::TO_PAR, 'today-'.$pid, $scoreline->scorecard->lastToPar());
			else
				$output .= $this->td(self::TO_PAR, 'today-'.$pid, $total);
		
			/* thru */
			if($name != self::ALLOWED) {
				$output .= $this->td(self::TO_PAR, 'thru-'.$pid, $scoreline->scorecard->thru);
			}
		}

		/* totals */
		$this->totals($name, $display_name, $position, $scoreline);
		
		if($position < 0) // restore original setting (watch out if settings are read-only)
			$this->options->setOption(self::SPLITFLAP, $old_use_splitflap);
		
		$output .= Html::endTag('tr');;
		return $output;
	}

	
	private function prepare_scorecards() {
		$this->scoreline = [];
		foreach($this->competition->getScorecards()->each() as $scorecard) {
			$this->scoreline[] = new Scoreline([
				'scorecard' => $scorecard,
				'pos' => 0, // will be computed
				'curr' => $this->competition->rule->isStableford() ? $scorecard->stableford() : $scorecard->toPar(),
				'rounds' => $this->competition->getRounds(),
				'round' => $this->competition->getRound(),
				'thru' => $scorecard->thru,
				'today'	=> ($this->competition->rule->isStableford()) ? array_sum($scorecard->stableford()) : $scorecard->lastToPar(),
				'total'	=> array_sum($this->competition->rule->isStableford() ? $scorecard->stableford() : $scorecard->score()),
				'stats' => [],
			]);
		}
		
		// sort array depending on rule
		uasort($this->scoreline, array(Scoreline::className(), $this->competition->rule->isStableford() ? 'compareGolfScore' : 'compareGolfScoreToPar'));
	}
	

	protected function print_scores() {
		$output = '';
		$count = 0;
		$previous = null;
		$position = 1;
		$position_accumulator = 0;

		foreach($this->scoreline as $scoreline) {
			if( $count++ < $this->maxGolfer ) {
				
			if($previous != null) { // first elem
				if($this->competition->rule->isStableford()) {	
					if(Scoreline::compareGolfScore($scoreline, $previous)) {
						$position += $position_accumulator;
						$position_accumulator = 0;
					}
				} else { // Stableford
					if(Scoreline::compareGolfScoreToPar($scoreline, $previous)) {
						$position += $position_accumulator;
						$position_accumulator = 0;
					}
				}
			}
			$previous = $scoreline;
			$position_accumulator++;
			
			$scoreline->pos = $position;
			
			foreach([	self::GROSS,
						self::ALLOWED,
						self::NET,
						self::STABLEFORD,
						self::TO_PAR] as $what)
				if($this->getOption($what)) $output .= $this->print_score($what, $position, $scoreline);
	
			if($this->getOption(self::CARDS))
				$this->cards($scoreline, false/*vertical*/);
				
			}//max_players
		}//foreach

		return $output;
	}
	
	
}