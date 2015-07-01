<?php
/**
 * Scoreboard widget renders a competition scoreboard for one or more rounds.
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
	public $player;
	public $pos;
	public $curr;
	public $rounds;
	public $round;
	public $thru;
	public $topar;
	public $today;
	public $total;
	public $totals;
	public $stats;

	public static function compareGolfScoreToPar($a, $b) {
		$sa = intval($a->topar);
		$sb = intval($b->topar);
		return ($sa == $sb) ? ($a->thru < $b->thru) : ($sa > $sb);
	}
	
	public static function compareGolfScore($a, $b) {
		$sa = intval(array_sum($a->totals));
		$sb = intval(array_sum($b->totals));
		return ($sa == $sb) ? ($a->thru < $b->thru) : ($sa > $sb);
	}
	
}


class Scoreboard extends _Scoretable {
	/** common\models\Competition Competition to display. */
	public $competition;
	
	/** integer Maximum number of player displayed on scoreboard */
	public $maxPlayers = 10;
	
	/** common\models\Match Current match of competition. */
	protected $match;

	/** One starting tee set */
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

		if(!$this->match) {
			$this->setOption(self::TODAY, false);
		}

		if(!$this->competition->hasScores())
			return Yii::t('igolf', 'Competition has not started yet.');
		
		if(!$this->tees = $this->competition->course->getFirstTees())
			return Yii::t('igolf', 'Competition has no starting tees set.');
			
		//Yii::trace('tees='.$this->tees->id);
			
		if(!$this->tees->hasDetails()) { // if does not have hole details, we do not display hole by hole data.
			$this->setOption(self::HOLES, false);
			$this->setOption(self::FRONTBACK, false);
		}
		if($this->competition->holes == 9) {
			$this->setOption(self::FRONTBACK, false);
		}

		$this->prepare_scorecards();
			
		if($this->getOption(self::AUTO_REFRESH))
			$refresh_data = json_encode([
				'competition' => $this->id(),
				'refresh' => $this->show(self::AUTO_REFRESH_RATE),
				'options' => implode(',', $this->valid_options)
			]);
		else
			$refresh_data = $this->getId();

		$options = ['class' => 'scoreboard scorecard'];
		if($this->getOption(self::AUTO_REFRESH))
			Html::addCssClass($options, 'scoreboard-auto-refresh');
		if($this->getOption(self::SPLITFLAP))
			Html::addCssClass($options, 'scoreboard-splitflap');
		if($this->getOption(self::CARDS))
			Html::addCssClass($options, 'scoreboard-cards');
		if($this->competition->holes == 9)
			Html::addCssClass($options, 'scoreboard-nine');
		
		$options['data-holes'] = $this->competition->holes;
		$options['data-ajaxgolfleague'] = $refresh_data;		
		
		return $this->print_table($options);
	}
	
	public function init() {
		$this->registerAssets();
	}
	
    /**
     * Register client assets
     */
    protected function registerAssets() {
        $view = $this->getView();
        ScoreboardAsset::register($view);
    }

	/**
	 *	Table Headers & Footers
	 */
	protected function caption() {
		$competition = $this->competition->getFullName();
		// $competition .= ' ('.$this->competition->getRound().'/'.$this->competition->getRounds().')';
		if($this->competition->getRounds() > 1) {
			$competition .= ', '.str_replace(' ', '&nbsp;', $this->competition->getDateRange());
		} else {
			$competition .= ', '.Yii::$app->formatter->asDate($this->competition->start_date);
		}
		return Html::tag('caption', $competition);
	}

	protected function print_header_split() {
		$output =  Html::beginTag('tr', ['class' => 'scorecard-split']);
		$output .= Html::tag('th', $this->tees->name, ['colspan' => 2]);
		if($this->getOption(self::HOLES)) {
			for($i=0; $i<$this->competition->holes; $i++) {
				$output .= Html::tag('th', $i+1);
			}
		}
		if($this->getOption(self::TODAY)) {
			$output .= Html::tag('th', Yii::t('igolf', 'Today'));
			$output .= Html::tag('th', Yii::t('igolf', 'Thru'));
		}
		if($this->getOption(self::FRONTBACK)) {
			$output .= Html::tag('th', Yii::t('igolf', 'Front'));
			$output .= Html::tag('th', Yii::t('igolf', 'Back'));
		}
		if($this->getOption(self::ROUNDS) && (($rounds = $this->competition->getRounds()) > 1)) {// do not show rounds if only one round...
			for($r=0; $r<$rounds; $r++) {
				$output .= Html::tag('th', ($r+1), ['class' => 'round']);
			}
		}
		$output .= Html::tag('th', Yii::t('igolf', 'Total'));
		$output .= Html::endTag('tr');
		return $output;
	}

	protected function print_headers() {
		$displays = [
			self::LENGTH => [
				'label'=> Yii::t('igolf', 'Length'),
				'data' => $this->tees->lengths(),
				'total' => true
			],
			self::PAR => [
				'label'=> Yii::t('igolf', 'Par'),
				'data' => $this->tees->pars(),
				'total' => true
			],
		];
		if($this->getOption(self::HOLES)) {
			$displays[self::SI] = [
				'label'=> Yii::t('igolf', 'S.I.'),
				'data' => $this->tees->sis(),
				'total' => false
			];
		}

		$output = '';
		foreach($displays as $key => $display) {
			if($this->getOption($key)) {			
				$output .=  Html::beginTag('tr');
				$output .= Html::tag('th', $display['label'], ['class' => 'labelr', 'colspan' => 2]);
				if($this->getOption(self::HOLES)) {
					for($i=0; $i<$this->competition->holes; $i++) {
						$output .= Html::tag('th', $display['data'][$i]);
					}
				}
				if($this->getOption(self::TODAY)) {
					$output .= Html::tag('th', '');
					$output .= Html::tag('th', '');
				}
				if($this->getOption(self::ROUNDS) && (($rounds = $this->competition->getRounds()) > 1)) {// do not show rounds if only one round...
					$output .= Html::tag('th', /*$key == self::PAR ? Yii::t('igolf', 'Rounds') :*/ '', ['colspan' => $rounds]);
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

	protected function print_header() {
		$r  = $this->print_headers();
		$r .= $this->print_header_split();
		return $r;
	}

	protected function print_footer() {
		$output  =  Html::beginTag('tr', ['class' => 'last-updated']);

		$str = Yii::t('igolf', 'Last updated at {0}.', date('G:i:s'));
		$str .= $this->getOption(self::AUTO_REFRESH) ? '' : ' '.Yii::t('igolf', '(Automatic refresh disabled.)');

		$output .= Html::tag('td', $str, ['colspan' => $this->colspan(-2)]);
		$output .=  Html::endTag('tr');
		return $output;
	}

	private function totals($name, $display_name, $position, $scoreline) {
		$output = '';
		if($this->getOption(self::ROUNDS) && (($rounds = $this->competition->getRounds()) > 1)) {// do not show rounds if only one round...
			for($r=0; $r<$rounds; $r++) {
				$output .= Html::tag('td', $scoreline->totals[$r]);
			}
		}
		$output .= $this->td(self::SCORE, 'total-'.$scoreline->scorecard->player->id, array_sum($scoreline->totals));
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
		$local_total = 0;
		switch($name) {
			case self::ALLOWED:			$scores = $scoreline->scorecard->allowed();			$local_total = $scoreline->scorecard->allowed_total();			$refs = $scores; break;
			case self::SCORE_NET:		$scores = $scoreline->scorecard->score(true);		$local_total = $scoreline->scorecard->score_net_total();		$refs = $scores; break;
			case self::STABLEFORD:		$scores = $scoreline->scorecard->stableford();		$local_total = $scoreline->scorecard->stableford_total();		$refs = $scoreline->scorecard->score(); break;
			case self::STABLEFORD_NET:	$scores = $scoreline->scorecard->stableford(true);	$local_total = $scoreline->scorecard->stableford_net_total();	$refs = $scoreline->scorecard->score(true); break;
//			case self::TO_PAR:			$scores = $scoreline->to_par( $this->competition->rounds() > 1 ? $this->lite[$pid]['topar'][$scoreline->match()->id()] : 0 ); break; // @todo
			case self::TO_PAR:			$scores = $scoreline->scorecard->toPar( 0 );		$local_total = $scoreline->scorecard->lastToPar();				$refs = $scores; break;
			case self::TO_PAR_NET:		$scores = $scoreline->scorecard->toPar_net( 0 );	$local_total = $scoreline->scorecard->lastToPar_net();			$refs = $scores; break;
			case self::SCORE:
			default:					$scores = $scoreline->scorecard->score();			$local_total = $scoreline->scorecard->allowed_total();			$refs = $scores; break;
		}

		$pars = $scoreline->scorecard->tees->pars();
		$stableford_points = $this->competition->rule->getStablefordPoints();
		$pid = $scoreline->scorecard->player->id;

		$old_use_splitflap = $this->getOption(self::SPLITFLAP);
		if($position < 0) {
			$output .= Html::tag('td', (($position == -2) ? $display_name : $label), ['class' => 'igolf-label']);
			$this->options->setOption(self::SPLITFLAP, false); // temporaily
		} else {
			$output .= $this->td($name, 'pos-'.$pid, $position);
			$output .= Html::tag('td', $scoreline->scorecard->player->name.' '.
						$score_type = Html::tag('span', Yii::t('igolf', $display_name), ['class' => 'score-type']), ['class' => 'igolf-name']);
		}

		//$debug = print_r($refs, true);		
		//$output .= $debug;

		/* holes */
		$total = 0;
		if($this->getOption(self::HOLES)) {
			for($i=0; $i<min($this->competition->holes,count($scores)); $i++) { // @todo
				$total += $scores[$i];
					if(in_array($name, [self::STABLEFORD, self::STABLEFORD_NET])) {
						$output .= $this->td($name, 'hole-'.$i.'-'.$pid, $scores[$i], array_search($scores[$i], $stableford_points));
					} else {
						$output .= $this->td($name, 'hole-'.$i.'-'.$pid, $scores[$i], ($refs[$i] - $pars[$i]));
					}
			}
		} else {
			$total = $local_total;
		}

		/* today */
		if($this->getOption(self::TODAY) && $position > 0) {
			if(in_array($name, [self::TO_PAR, self::TO_PAR_NET]))	
				$output .= $this->td(self::TO_PAR, 'today-'.$pid, $scoreline->scorecard->lastToPar());
			else
				$output .= $this->td(self::TO_PAR, 'today-'.$pid, $total);
		
			/* thru */
			if($name != self::ALLOWED) {
				$output .= $this->td(self::TO_PAR, 'thru-'.$pid, $scoreline->scorecard->thru);
			}
		}

		/* totals */
		$output .= $this->totals($name, $display_name, $position, $scoreline);
		
		if($position < 0) // restore original setting (watch out if settings are read-only)
			$this->options->setOption(self::SPLITFLAP, $old_use_splitflap);
		
		$output .= Html::endTag('tr');;
		return $output;
	}

	private function prepare_scorecards() {
		$this->scoreline = [];
		if($this->match) {
			foreach($this->match->getScorecards()->each() as $scorecard) {
				// previous rounds
				$rounds = [];
				foreach($this->competition->getCompetitions()->orderBy('start_date')->each() as $round) {
					$rounds[] = $round->getTotal($scorecard->player);
				}
				// current round
				$this->scoreline[] = new Scoreline([
					'scorecard' => $scorecard,
					'pos' => 0, // will be computed
					'curr' => $this->competition->rule->isStableford() ? $scorecard->stableford() : $scorecard->toPar_net(),
					'rounds' => $this->competition->getRounds(),
					'round' => $this->competition->getRound(),
					'thru' => $scorecard->thru,
					'today'	=> ($this->competition->rule->isStableford()) ? array_sum($scorecard->stableford()) : $scorecard->lastToPar(),
					'totals' => $rounds,
					'total'	=> array_sum($this->competition->rule->isStableford() ? $scorecard->stableford() : $scorecard->score()),
					'stats' => [],
				]);
			}
		} else {
			foreach($this->competition->getScorecards()->each() as $scorecard) {
				// previous rounds
				$rounds = [];
				foreach($this->competition->getCompetitions()->orderBy('start_date')->each() as $round) {
					$rounds[] = $round->getTotal($scorecard->player);
				}
				// current round
				$this->scoreline[] = new Scoreline([
					'scorecard' => $scorecard,
					'pos' => 0, // will be computed
					'curr' => null,
					'rounds' => $this->competition->getRounds(),
					'round' => $this->competition->getRound(),
					'thru' => 0,
					'today'	=> null,
					'totals' => $rounds,
					'total'	=> array_sum($rounds),
					'stats' => [],
				]);
			}
		}
		
		// sort array depending on rule
		$method = 'compareGolfScore'; // $this->competition->rule->isStableford() ? 'compareGolfScore' : 'compareGolfScoreToPar';
		uasort($this->scoreline, array(Scoreline::className(), $method));
	}

	protected function print_scores() {
		$output = '';
		$count = 0;
		$previous = null;
		$position = 1;
		$position_accumulator = 0;

		foreach($this->scoreline as $scoreline) {
			if( $count++ < $this->maxPlayers ) {
				
			if($previous != null) { // first elem
				if($this->competition->rule->isStableford()) {	
					if(Scoreline::compareGolfScore($scoreline, $previous)) {
						$position += $position_accumulator;
						$position_accumulator = 0;
					}
				} else { // Stableford
					if(Scoreline::compareGolfScore($scoreline, $previous)) {
						$position += $position_accumulator;
						$position_accumulator = 0;
					}
				}
			}
			$previous = $scoreline;
			$position_accumulator++;
			
			$scoreline->pos = $position;
			
			foreach([	self::ALLOWED,
						self::SCORE,
						self::SCORE_NET,
						self::STABLEFORD,
						self::STABLEFORD_NET,
						self::TO_PAR,
						self::TO_PAR_NET
					] as $what)
				if($this->getOption($what)) $output .= $this->print_score($what, $position, $scoreline);
	
			if($this->getOption(self::CARDS))
				$this->cards($scoreline, false/*vertical*/);
				
			}//max_players
		}//foreach

		return $output;
	}
	
	
}