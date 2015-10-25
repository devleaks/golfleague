<?php
/**
 * Matchplay widget renders scores for a matchplay competition.
 *
 * @author Pierre M <devleaks.be@gmail.com>
 */

namespace common\widgets;

use Yii;
use common\assets\ScoreboardAsset;
use common\models\Rule;
use yii\base\Model;
use yii\bootstrap\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


class Matchboard extends _Scoretable {
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
		if(! $this->match->isMatchCompetition())
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

		if($this->match->holes == 9) {
			$this->setOption(self::FRONTBACK, false);
		}

		$this->setOption(self::TOTAL, true);

		return $this->print_table(['class' => 'scoreboard scorecard']);
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
		return Html::tag('caption', $this->match->getFullName().', '.Yii::$app->formatter->asDate($this->match->start_date));
	}

	protected function print_header_split() {
		$output =  Html::beginTag('tr', ['class' => 'scorecard-split']);
		$output .= Html::tag('th', $this->tees ? $this->tees->name : '');
		if($this->getOption(self::HOLES)) {
			for($i=0; $i<$this->match->holes; $i++) {
				$output .= Html::tag('th', $i+1);
			}
		}
		if($this->getOption(self::TODAY)) {
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
			$output .= Html::tag('th', $display['label'], ['class' => 'labelr']);
			if($this->getOption(self::HOLES)) {
				for($i=0; $i<$this->match->holes; $i++) {
					$output .= Html::tag('th', $display['data'][$i]);
				}
			}
			if($display['total']) {
				$output .= Html::tag('th', array_sum($display['data']), ['colspan' => $this->getOption(self::TODAY) ? 2 : 1]);
			} else {
				$output .= Html::tag('th', '', ['colspan' => $this->getOption(self::TODAY) ? 2 : 1]);
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

		$output .= Html::tag('td', $str, ['colspan' => $this->colspan(-1)]);
		$output .=  Html::endTag('tr');
		return $output;
	}

	private function print_score($match) {
		$output = '';
		$debug = '';
		
		$cnt = 0;
		foreach($match->getRegistrations()->each() as $opponent) {
			$pid = $opponent->scorecard->player->id;		
			$output .= Html::beginTag('tr', ['class' => 'golfleague-match-'.$cnt++]); // -0, -1 allows to distinguish opponent for styling

			/* name */
			$output .= Html::tag('td', $opponent->scorecard->player->name, ['class' => 'golfleague-name']);

			/* hole details */
			if($this->getOption(self::HOLES) && $opponent->scorecard->hasDetails()) {
				$details = $opponent->scorecard->getScoreFromRule();
				$score = 0;
				for($i=0; $i<min($this->match->holes,$opponent->scorecard->thru); $i++) {
					$score += 2*$details[$i] - 1;
					$output .= $this->td(self::MATCH, self::HOLE.'-'.$i.'-'.$pid, $score);
				}
				for(; $i<$this->match->holes; $i++) {
					$output .= $this->td(self::MATCH, self::HOLE.'-'.$i.'-'.$pid, '');
				}
			} else if($this->getOption(self::HOLES)) {
				$output .= Html::tag('td', Yii::t('golf', 'No detailed scorecard.'), ['colspan' => $this->match->holes]);
			}
			

			/* total, always displayed */
			$points = $opponent->scorecard->getTotalFromRule();
			$thru   = $opponent->scorecard->thru;
			$score  = 2 * $points - $thru;
			
			if($this->getOption(self::TODAY)) {
				$output .= $this->td(self::TO_PAR, self::TODAY.'-'.$pid, $thru);
			}
			$output .= $this->td(self::MATCH, self::TOTAL.'-'.$pid, $score);

			$output .= Html::endTag('tr');
		}
		return $output . $debug;
	}

	protected function print_scores() {
		$output = '';
		foreach($this->match->getMatches()->orderBy('position')->each() as $match) {
			$output .= $this->print_score($match);
		}
		return $output;
	}

}