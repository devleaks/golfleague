<?php
/**
 * Matchplay widget renders scores for a matchplay competition.
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


class Matchplay extends _Scoretable {
	/** common\models\Match Current matchplay */
	public $match;

	/** common\models\Tees starting tee set */
	protected $tees;

	/**
	 * Main function.
	 *
	 * @return string HTML table
	 */
	public function run() {
		if(! $this->match->isMatchplay())
			return Yii::t('golf', 'Competition is not a matchplay.');

		if(!$this->match->hasScores())
			return Yii::t('golf', 'Competition has not started yet.');

		if(!$this->tees = $this->match->course->getFirstTees())
			return Yii::t('golf', 'Competition has no starting tees set.');

		if(!$this->tees->hasDetails()) { // if does not have hole details, we do not display hole by hole data.
			$this->setOption(self::HOLES, false);
			$this->setOption(self::FRONTBACK, false);
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

		return $this->print_table();
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
				$r = $this->match->getRoundNumber();
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
		if($this->getOption(self::THRU)) {
			$output .= Html::tag('th', Yii::t('golf', 'Thru'));
		}
		$output .= Html::tag('th', Yii::t('golf', 'Match'));
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
			$output .= Html::tag('th', array_sum($display['data']), ['colspan' => $this->getOption(self::THRU) ? 2 : 1]);
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

		$output .= Html::tag('td', $str, ['colspan' => $this->colspan(-1)]);
		$output .=  Html::endTag('tr');
		return $output;
	}

	private function print_score($match) {
		$output = '';
		$debug = '';
		
		$cnt = 0;
		foreach($match->getRegistrations()->each() as $opponent) {			
			$output .= Html::beginTag('tr', ['class' => 'golfleague-match-'.$cnt++]); // -0, -1 allows to distinguish opponent for styling

			/* name */
			$output .= Html::tag('td', $scoreline->scorecard->player->name, ['class' => 'golfleague-name']);

			/* hole details */
			if($this->getOption(self::HOLES)) {
				$detail = $opponent->scorecard->getScoreFromRule();
				$score = 0;
				for($i=0; $i<min($this->competition->holes,count($scores)); $i++) {
					$score += $scores[$i];
					$output .= $this->td($name, self::HOLE.'-'.$i.'-'.$pid, $score);
				}
			}

			/* total, always displayed */
			$points = $opponent->scorecard->getScoreFromRule(true);
			$thru   = $opponent->scorecard->thru;
			$score  = 2 * $points - $thru;
			
			if($this->getOption(self::THRU)) {
				$output .= $this->td(self::TO_PAR, self::THRU.'-'.$opponent->scorecard->player->id, $thru);
			}
			$output .= $this->td(self::MATCH, self::TOTAL.'-'.$opponent->scorecard->player->id, $score);

			$output .= Html::endTag('tr');
		}
		return $output . $debug;
	}

	protected function print_scores() {
		foreach($this->match->getMatches()->orderBy('position')->each() as $match) {
			$output .= $this->print_score($match);
		}
		return $output;
	}

}