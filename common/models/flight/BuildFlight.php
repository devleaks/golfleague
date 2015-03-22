<?php

namespace common\models\flight;

use common\models\Flight;
use common\models\Golfer;
use common\models\Registration;

/**
 * This is the interface for flight building algorithms.
 *
 */
interface BuildFlight
{
	public function execute($id);
}
