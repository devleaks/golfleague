<?php

namespace common\models;

/**
 * This is the interface for competition participants, single or team.
 *
 */
interface Competitor
{
	/** returns handicap of camp */
	public function handicap($camp);

	/** returns allowed stroke array for camp from tees */
	public function allowed($tees, $camp);

	/** returns total allowed stroke for camp from tees */
	public function allowedTotal($tees, $camp);
}
