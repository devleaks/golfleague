<?php

namespace common\models;

/**
 * This is the interface for computing handicap related values depending on the handicap system.
 *
 */
interface Opponent
{
	/**
	 * Return player name
	 */
	public function getName();

	/**
	 * Return player handicap
	 */
	public function getHandicap();
}
