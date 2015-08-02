<?php

namespace common\models\match;

/**
 * This is the interface for match building algorithms.
 *
 */
interface BuildMatch
{
	public function execute($id);
}
