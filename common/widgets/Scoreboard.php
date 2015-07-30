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
use common\models\Scorecard;
use yii\base\Model;
use yii\bootstrap\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Scoreline extends Model {
	/** Player */
	public $pos;
	public $player;
	public $scorecard;

	/** Details for display */
	public $curr;

	/** Current hole (may depend on round...) */
	public $round;
	public $thru;

	/** Totals */
	public $topar;
	public $today;
	public $total;
	public $totals;
	public $stats;

	/** */
	public $rule;

	public static function compare($a, $b) {
		if($a->rule) {
			$sa = 0;
			$sb = 0;
			if($a->rule->competition_type == Competition::TYPE_MATCH) {
				$sa = intval($a->total);
				$sb = intval($b->total);
			} else {
				$sa = intval(array_sum($a->totals));
				$sb = intval(array_sum($b->totals));
			}
			if($a->rule->source_direction == Scorecard::DIRECTION_ASC) {
				return ($sa == $sb) ? ($a->thru > $b->thru) : ($sa < $sb);
			} else {
				return ($sa == $sb) ? ($a->thru < $b->thru) : ($sa > $sb);
			}
		}
	}
	
	public static function compareToPar($a, $b) {
		$sa = intval($a->topar);
		$sb = intval($b->topar);
		return ($sa == $sb) ? ($a->thru < $b->thru) : ($sa > $sb);
	}
	
}


class Scoreboard extends _Scoretable {
	/** integer Maximum number of player displayed on scoreboard */
	public $maxPlayers = 10;
	
	/** common\models\Competition Competition to display. */
	public $competition;
	

	/** common\models\Match Current match in multi-match competition. */
	protected $match;

	/** common\models\Tees starting tee set */
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
		} else if($this->match->status == Competition::STATUS_CLOSED) {
			$this->setOption(self::TODAY, false);
		}

		if(!$this->competition->hasScores())
			return Yii::t('golf', 'Competition has not started yet.');
		
		if($this->match) {
			if(!$this->tees = $this->match->course->getFirstTees())
				return Yii::t('golf', 'Competition has no starting tees set.');

			if(!$this->tees->hasDetails()) { // if does not have hole details, we do not display hole by hole data.
				$this->setOption(self::HOLES, false);
				$this->setOption(self::FRONTBACK, false);
			}
		}
		//Yii::trace('tees='.$this->tees->id);
			
		if(!$this->tees) {
			$this->setOption(self::PAR, false);
			$this->setOption(self::SI, false);
			$this->setOption(self::LENGTH, false);
			$this->setOption(self::TO_PAR, false);
		} else if (!$this->tees->hasDetails()) {
			$this->setOption(self::PAR, false);
			$this->setOption(self::SI, false);
			$this->setOption(self::LENGTH, false);
			$this->setOption(self::TO_PAR, false);
		}

		if($this->competition->holes == 9) {
			$this->setOption(self::FRONTBACK, false);
		}

		$this->setOption(self::TOTAL, true);

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
		
		return count($this->scoreline) ? $this->print_table($options)
				: Yii::t('golf', 'There is no scorecard for this competition.');
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
		if($this->competition->getRounds() > 1) {
			if($this->match) {
				$r = $this->match->getRound();
				$competition .= ' ('.$r.'/'.$this->competition->getRounds().')';
			}
			$competition .= ', '.str_replace(' ', '&nbsp;', $this->competition->getDateRange());
		} else {
			$competition .= ', '.Yii::$app->formatter->asDate($this->competition->start_date);
		}
		return Html::tag('caption', $competition);
	}

	protected function print_header_split() {
		$output =  Html::beginTag('tr', ['class' => 'scorecard-split']);
		$output .= Html::tag('th', $this->tees ? $this->tees->name : '', ['colspan' => 2]);
		if($this->getOption(self::HOLES)) {
			for($i=0; $i<$this->competition->holes; $i++) {
				$output .= Html::tag('th', $i+1);
			}
		}
		if($this->getOption(self::TODAY)) {
			$output .= Html::tag('th', Yii::t('golf', 'Today'));
			$output .= Html::tag('th', Yii::t('golf', 'Thru'));
		}
		if($this->getOption(self::FRONTBACK)) {
			$output .= Html::tag('th', Yii::t('golf', 'Front'));
			$output .= Html::tag('th', Yii::t('golf', 'Back'));
		}
		if($this->getOption(self::ROUNDS) && (($rounds = $this->competition->getRounds()) > 1)) {// do not show rounds if only one round...
			for($r=0; $r<$rounds; $r++) {
				$output .= Html::tag('th', ($r+1), ['class' => 'round']);
			}
		}
		$output .= Html::tag('th', Yii::t('golf', 'Total'), ['colspan' => $this->getOption(self::TO_PAR) ? 2 : 1]);
		$output .= Html::endTag('tr');
		return $output;
	}

	protected function print_headers() {
		$displays = [];
		if($this->getOption(self::LENGTH)) {
			$displays[self::LENGTH] = [
				'label'=> Yii::t('golf', 'Length'),
				'data' => $this->tees->lengths(),
				'total' => true
			];
		}
		if($this->getOption(self::PAR)) {
			$displays[self::PAR] = [
				'label'=> Yii::t('golf', 'Par'),
				'data' => $this->tees->pars(),
				'total' => true
			];
		}
		if($this->getOption(self::HOLES) || $this->getOption(self::SI)) {
			$displays[self::SI] = [
				'label'=> Yii::t('golf', 'S.I.'),
				'data' => $this->tees->sis(),
				'total' => false
			];
		}

		$output = '';
		foreach($displays as $key => $display) {
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
				$output .= Html::tag('th', /*$key == self::PAR ? Yii::t('golf', 'Rounds') :*/ '', ['colspan' => $rounds]);
			}
			if($this->getOption(self::TOTAL)) {
				$output .= Html::tag('th', array_sum($display['data']), ['colspan' => $this->getOption(self::TO_PAR) ? 2 : 1]);
				if($this->getOption(self::FRONTBACK)) {
					$output .= Html::tag('th', array_sum(array_slice($display['data'], 0, 9)));
					$output .= Html::tag('th', array_sum(array_slice($display['data'], 9, 9)));
				}
			} else {
				$output .= Html::tag('th', '', ['colspan' => $this->getOption(self::FRONTBACK) ? 3 : 1]);
			}
			$output .= Html::endTag('tr');
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

		$str = Yii::t('golf', 'Last updated at {0}.', date('G:i:s'));
		$str .= $this->getOption(self::AUTO_REFRESH) ? '' : ' '.Yii::t('golf', '(Automatic refresh disabled.)');

		$output .= Html::tag('td', $str, ['colspan' => $this->colspan(-2)]);
		$output .=  Html::endTag('tr');
		return $output;
	}

	private function print_score($scoreline) {
		$name = $this->competition->rule->source_type;
		
		$output = '';
		$debug = '';
		if(!$scoreline) {
			$output .= Html::beginTag('tr', ['class' => 'player-error '.$name]);
			$output .= Html::tag('td', Yii::t('golf', $name), ['class' => 'igolf-label']);
			$output .= Html::tag('td', Yii::t('golf', 'No scorecard'), ['colspan' => 9]);
			$output .= Html::tag('td', 0, ['class' => 'total']);
			$output .= Html::endTag('tr');;
			return $output;
		}
		$output .= Html::beginTag('tr', ['class' => 'player '.$name]);
		
		
		/* header */
		$scores = $scoreline->scorecard->getScoreFromRule();
		$refs   = $scores;
		if($name == self::STABLEFORD) {
			$refs = $scoreline->scorecard->score();
		} else if ($name == self::STABLEFORD_NET) {
			$refs = $scoreline->scorecard->score(true);
		}

		$stableford_points = $this->competition->rule->getStablefordPoints();
		$pid = $scoreline->scorecard->player->id;

		/* position & name (& score type if necessary) */
		$output .= $this->td($name, 'pos-'.$pid, $scoreline->pos);
		$output .= Html::tag('td', $scoreline->scorecard->player->name.' '.
					$score_type = Html::tag('span', Yii::t('golf', $name), ['class' => 'score-type']), ['class' => 'igolf-name']);

		//$debug = print_r($scores, true);		

		/* hole details */
		if($this->getOption(self::HOLES)) {
			$pars = $scoreline->scorecard->tees->pars();
			for($i=0; $i<min($this->competition->holes,count($scores)); $i++) { // @todo
					if(in_array($name, [self::STABLEFORD, self::STABLEFORD_NET])) {
						$output .= $this->td($name, self::HOLE.'-'.$i.'-'.$pid, $scores[$i], array_search($scores[$i], $stableford_points));
					} else {
						$output .= $this->td($name, self::HOLE.'-'.$i.'-'.$pid, $scores[$i], ($refs[$i] - $pars[$i]));
					}
			}
		}

		/* today */
		if($this->getOption(self::TODAY) && $scoreline->pos > 0) {
			if($this->getOption(self::TO_PAR))	
				$output .= $this->td(self::TO_PAR, self::TODAY.'-'.$pid, $scoreline->scorecard->lastToPar());
			else
				$output .= $this->td(self::TODAY, self::TODAY.'-'.$pid, $scoreline->today);
		
			/* thru */
			$output .= $this->td(self::TO_PAR, self::THRU.'-'.$pid, $scoreline->scorecard->thru);
		}

		/* totals for rounds */
		if($this->getOption(self::ROUNDS) && (($rounds = $this->competition->getRounds()) > 1)) {// do not show rounds if only one round...
			for($r=0; $r<$rounds; $r++) {
				$output .= Html::tag('td', $scoreline->totals[$r]);
			}
		}

		/* grand total, always displayed */
		$output .= $this->td(self::SCORE, self::TOTAL.'-'.$scoreline->scorecard->player->id, $scoreline->total);
		
		if($this->getOption(self::TO_PAR))
			$output .= $this->td(self::TO_PAR, self::TO_PAR.'-'.$scoreline->scorecard->player->id, $scoreline->topar);
		
		$output .= Html::endTag('tr');;

		$output .= $debug;

		return $output;
	}

	protected function print_scores() {
		$output = '';
		$count = 0;
		$previous = null;
		$position = 1;
		$position_accumulator = 0;
		
		// sort lines according to rule
		$method = $this->getOption(self::TO_PAR) ? 'compareToPar' : 'compare';

		uasort($this->scoreline, array(Scoreline::className(), $method));

		foreach($this->scoreline as $scoreline) {
			if( $count++ < $this->maxPlayers ) {
			
			if($previous != null) { // first elem
				if(Scoreline::$method($scoreline, $previous)) {
					$position += $position_accumulator;
					$position_accumulator = 0;
				}
			}
			$previous = $scoreline;
			$position_accumulator++;
			
			$scoreline->pos = $position;
			
			$output .= $this->print_score($scoreline);
	
			if($this->getOption(self::CARDS))
				$this->cards($scoreline, false/*vertical*/);
				
			}//max_players
		}//foreach

		return $output;
	}
	
	/**
	 * Scoreboard content
	 */
	private function prepare_scorecards() {
		$this->scoreline = [];
		if($this->competition->competition_type == Competition::TYPE_MATCH) {
			foreach($this->match->getScorecards()->each() as $scorecard) {
				// current round
				$this->scoreline[] = new Scoreline([
					'rule' => $this->match->rule,
					'scorecard' => $scorecard,
					'pos' => 0, // will be computed
					'curr' => $scorecard->getScoreFromRule(),
					'round' => $this->competition->getRound(),
					'thru' => $scorecard->thru,
					'today'	=> $this->getOption(self::TO_PAR) ? $scorecard->getScoreFromRule(true) : $scorecard->lastToPar(),
					'total'	=> $scorecard->getScoreFromRule(true),
					'totals' => [],
					'topar' => $scorecard->lastToPar(),
					'stats' => [],
				]);
			}
		} else {
			foreach($this->competition->getScorecards()->andWhere(['status' => Scorecard::STATUS_RETURNED])->each() as $scorecard) {
				// previous rounds
				$rounds = [];
				$total_topar = 0;
				foreach($this->competition->getCompetitions()->orderBy('start_date')->each() as $round) {
					$rounds[] = $round->getTotal($scorecard->player);
					//echo $round->name.'='.$round->getTotal($scorecard->player);
					$total_topar += $round->getToPar($scorecard->player);
				}

				$current = $this->match ? $this->match->getScorecard($scorecard->player) : null;
				// current round
				$this->scoreline[] = new Scoreline([
					'rule' => $this->competition->rule,
					'scorecard' => $current ? $current : $scorecard,
					'pos' => 0, // will be computed
					'curr' => $current ? $current->getScoreFromRule() : null,
					'round' => $this->competition->getRound(),
					'thru' => $current ? $current->thru : 0,
					'today'	=> $current ? $current->getScoreFromRule(true) : $scorecard->getScoreFromFinalRule(),
					'total'	=> array_sum($rounds),
					'totals' => $rounds,
					'topar' => $total_topar,
					'stats' => [],
				]);
			}
		}
	}

	
}