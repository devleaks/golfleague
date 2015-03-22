<?php

namespace common\models\rule;

/**
 * This is the interface for applying rules to competitions.
 *
 */
interface RuleInterface
{
	public function doPositions();

	public function doPoints();
}
