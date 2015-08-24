<?php
/**
 * Brackets widget renders a matchplay tournament in bracket format.
 *
 * @author Pierre M <devleaks.be@gmail.com>
 */

namespace common\widgets;

use Yii;
use common\assets\BracketsAsset;
use common\models\Competition;
use common\models\Rule;
use common\models\Scorecard;
use yii\base\Model;
use yii\bootstrap\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


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
		
		return $this->print_table([]);
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
		$output = '';
		$debug = '';
		
		$level = $round->getLevelString();
		
		foreach($round->getMatches()->each() as $match) {
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
				echo $level.': '.$scorecard->player->name.' ('.$scorecard->points_total().') '.$updowns.'<br/>';
			}
		}

		return $this->render('_brackets', [
			'brackets' => $level,
		]);
	}

	protected function print_scores() {
		$output = '';
		$count = 0;
		
		foreach($this->competition->getCompetitions()->orderBy('created_at')->each() as $round) {
			$this->print_score($round);
		}
		

		return $output;
	}
	
}