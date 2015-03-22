<?php

namespace common\models\rule;

use common\models\Point;
use common\models\Registration;
use yii\helpers\ArrayHelper;

/**
 * This is the interface for applying rules to competitions.
 *
 */
class TournamentSimpleSum implements RuleInterface
{
	public $competition;
	
	public function __construct($competition) {
		$this->competition = $competition;
	}
		
	public function doPositions() {
		return false;
	}

	public function doPoints() {
		$regs = $this->competition->getRegistrations();
		$matches = $this->competition->getCompetitions(); // or Tournament::getMatches would be nicer
		
		return true;
	}
}
