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
	public $id;
	public $label;
	public $winner;
	public $url;
	public $level;	
}

class Brackets extends _Scoretable {
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

	private function print_score($round) {
		$output = '[';
		
		$url   = Url::to(['matchboard', 'id' => $round->id]);
		$first = true;
		
		foreach($round->getMatches()->each() as $match) {
			if($first) {
				$first = false;
			} else {
				$output .= ',
';
			}
			$last = null;
			$player = 1;
			$output .= '
{';
			foreach($match->getScorecards()->orderBy('created_at')->each() as $scorecard) {
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
					$output .= ',
';
				}
				$label = $scorecard->player->name.' ('.$scorecard->points_total().') '.$updowns;
                // player1: { name: "Player 111", winner: true, ID: 111, url: 'http://www.google.com' },
				$output .= 'player'.($player++).
					': { name: "'.$label.
					'", ID: "'.$scorecard->player->id.
					'", winner: '.($scorecard->isWinner()?'true':'false').
					', url: "'.$url.'" }
';
				if($scorecard->isWinner() && $round->level == 1) {
					$last = '[{ player1 : { name: "'.$scorecard->player->name.' ('.$scorecard->points_total().') '.$updowns.
						'", ID: "'.$scorecard->player->id.'", winner: true, url: "'.$url.'" }}
';
				}
			}
			$output .= '}
';
		}
		if($last) $output .= '],'.$last;
		$output .= ']
';
		return $output;
	}

	protected function print_scores() {
		$rounds = null;
		$titles = null;
		
		foreach($this->competition->getCompetitions()->orderBy('created_at')->each() as $round) {
			if(!$rounds) {
				$rounds = '[';
				$titles = '[';
			} else {
				$rounds .= ',';
				$titles .= ',';
			}
		    $titles .= '"'.$round->getLevelString().'"';
			$rounds .= $this->print_score($round);
		}
	    $titles .= ',"'.Yii::t('golf','Winner').'"]';
		$rounds .= ']';
		
		return $this->render('_brackets', [
			'rounds' => $rounds,
			'titles' => $titles,
		]);
	}
	
}