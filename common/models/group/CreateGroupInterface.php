<?php

namespace common\models\group;

/**
 * This is the interface for creating groups from a set of registration.
 *
 */
interface CreateGroupInterface
{
	public function create();

	public function update();
}
