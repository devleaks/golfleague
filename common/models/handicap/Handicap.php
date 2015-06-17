<?php

namespace common\models\handicap;

/**
 * This is the interface for computing handicap related values depending on the handicap system.
 *
 */
interface Handicap
{
	public function allowed($tees, $golfer);
}
