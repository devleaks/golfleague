<?php

namespace common\models\match;

/**
 * This is the interface for match building algorithms.
 *
 */
interface BuildMatchInterface
{
	public function create();
	public function update();
}
