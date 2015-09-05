<?php
/**
 * Brackets widget renders a matchplay tournament in bracket format.
 *
 * @author Pierre M <devleaks.be@gmail.com>
 */

namespace common\widgets;

use common\assets\BracketsAsset;
use common\models\Competition;
use common\models\Rule;
use common\models\Scorecard;

use Yii;
use yii\base\Model;
use yii\bootstrap\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;


class BracketLine extends Model {
	public $top;
	public $bot;
	public $match;
	public $sequence;

	public static function compare($a, $b) {
		if ($a->sequence == $b->sequence) {
		        return 0;
		    }
	    return ($a->sequence < $b->sequence) ? -1 : 1;
	}	
}

class BracketRound extends Model {
	public $round;
	public $level;
}


class Brackets extends _Scoretable {
	protected $brackets;
	protected $rounds;
	protected $levels;

	/** integer Maximum level of brackets displayed */
	public $maxBrackets = 20;
	
	/** common\models\Competition Competition to display. */
	public $competition;
	
	/**
	 * Main function.
	 *
	 * @return string HTML table
	 */
	public function run() {
		if(!$this->competition->hasScores())
			return Yii::t('golf', 'Competition has not started yet.');
		
		return $this->print_scores();
	}
	
	public function init() {
		$this->brackets = [];
		$this->rounds = [];
		$this->levels = 0;
		$this->registerAssets();
	}
	
    /**
     * Register client assets
     */
    protected function registerAssets() {
        $view = $this->getView();
        BracketsAsset::register($view);
    }

	/**
	 *	Table Headers & Footers
	 */
	protected function caption() {
		$competition = $this->competition->getFullName();
		if($this->competition->getNumberOfRounds() > 1) {
			$competition .= ', '.str_replace(' ', '&nbsp;', $this->competition->getDateRange());
		} else {
			$competition .= ', '.Yii::$app->formatter->asDate($this->competition->start_date);
		}
		return $competition;
	}

	protected function print_header() {
	}

	protected function print_footer() {
		$output = Yii::t('golf', 'Last updated at {0}.', date('G:i:s'));
		return $output;
	}

	private function make_bracket_lines($round) {
		$url   = Url::to(['matchboard', 'id' => $round->id]);
		$level = $round->getLevel();

		$this->rounds[$level] = new BracketRound([
			'level' => '"'.$round->getLevelString().'"'
		]);

		foreach($round->getMatches()->each() as $match) {
			$player = 1;
			$top = null;
			$looser = null;
			if($this->levels < $level) $this->levels = $level;
			$text = '
{';
			foreach($match->getScorecards()->each() as $scorecard) {
				$points = $scorecard->getScoreFromRule(true);
				$thru   = $scorecard->thru;
				$score  = 2 * $points - $thru;
				$updowns = '';
				if($score) {
					$updowns = abs($score).' '.($score < 0 ? $this->getOption(self::DOWNS) : $this->getOption(self::UPS));
				} else if($score === floatval(0)) {
					$updowns = $this->getOption(self::ALLSQUARE);
				}
				if($player > 1) {
					$text .= ',
';
				}
				$label = $scorecard->player->name.' ('.$scorecard->points_total().') '.$updowns;
                // player1: { name: "Player 111", winner: true, ID: 111, url: 'http://www.google.com' },
				$text .= 'player'.($player++).
					': { name: "'.$label.
					'", ID: "'.$scorecard->player->id.
					'", winner: '.($scorecard->isWinner()?'true':'false').
					', url: "'.$url.'" }
';
				if($top) {
					$bot = $scorecard->player->id;
				} else {
					$top = $scorecard->player->id;
				}
				if($scorecard->isWinner() && $level == 1) {
					$this->brackets[0] = ['[{ player1 : { name: "'.$scorecard->player->name.
						'", ID: "'.$scorecard->player->id.'", winner: true, url: "'.$url.'" }}
'];
				}
			}
			$text .= '}
';
			if(!isset($this->brackets[$level])) $this->brackets[$level] = [];
			$this->brackets[$level][] = new BracketLine([
				'top' => $top,
				'bot' => $bot,
				'match' => $text
			]);
		}
	}
	
	private function getBracketLine($level, $player_id, $seq) {
		foreach($this->brackets[$level] as $bracket_line) {
			if($bracket_line->top == $player_id || $bracket_line->bot == $player_id) {
				$bracket_line->sequence = $seq;
				return $bracket_line->match;
			}
		}
		return null;
	}

	private function make_bracket_rounds() {
		$this->rounds[1]->round = '['.$this->brackets[1][0]->match.'],'.$this->brackets[0][0].']';
		$this->brackets[1][0]->sequence = 0;
		
		for($level = 2; $level <= $this->levels; $level++) {
			$seq = 0;			
			$first = true;
			$output = '[';

			usort($this->brackets[$level - 1], array(BracketLine::className(), 'compare'));

			foreach($this->brackets[$level - 1] as $bracket_line) {
				if($first) {
					$first = false;
				} else {
					$output .= ',
';
				}
				$output .= $this->getBracketLine($level, $bracket_line->top, $seq++);
				$output .= ',
';
				$output .= $this->getBracketLine($level, $bracket_line->bot, $seq++);
			}
			$output .= '] // level:'.$level.'
';
			$this->rounds[$level]->round = $output;
		}
	}

	protected function print_scores() {
		$rounds = null;
		$titles = null;

		// pass 1: Make matches
		foreach($this->competition->getCompetitions()->each() as $round) {
			$this->make_bracket_lines($round);
		}

		// pass 2: Make rounds, starting from winner, ordering top/bot for each match.
		$this->make_bracket_rounds();

		for($level = $this->levels; $level > 0; $level--) {
			if(!$rounds) {
				$rounds = '[';
				$titles = '[';
			} else {
				$rounds .= ',';
				$titles .= ',';
			}
			$titles .= $this->rounds[$level]->level;
			$rounds .= $this->rounds[$level]->round;
		}
	    $titles .= ',"'.Yii::t('golf','Winner').'"]';
		$rounds .= ']';
		
		return $this->render('_brackets', [
			'rounds' => $rounds,
			'titles' => $titles,
			'data' => $this->rounds
		]);
	}
	
}