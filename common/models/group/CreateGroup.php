<?php

namespace common\models\group;

use common\models\Group;
use common\models\Competition;
use common\models\Registration;

/**
 * This is the implementation for group building algorithms.
 *
 */
class CreateGroup implements CreateGroupInterface
{
	protected function addRegistrations($competition, $regs, $time_start, $time_increment, $max_registrations, $group_type) {
		$registrations = $regs ? $regs : $competition->getRegistrations();		
		$position      = $competition->getGroups()->max('group.position'); // may return null??
		if(! intval($position) > 0) $position = 0;
		$position++;
		$count = $max_registrations;
		foreach($registrations->each() as $registration) {
			if(! $registration->getGroup()->exists() ) {
				if($count >= $max_registrations) {
					$group_time = strtotime("+".($position * $time_increment)." minutes", strtotime($time_start));
					$count = 0;
					$group = new Group();
					$group->group_type = $group_type;
					$group->name = $group_type.' '.$competition->id.'.'.$count;
					$group->position = $position++;
					$group->start_time = $group_time;
					$group->start_hole = $competition->start_hole;
					$group->save();
				}
				$group->add($registration);
				$count++;
			}
		}
	}

	public function execute($competition) {
		$this->addRegistrations(
			$competition,
			null,
			$competition->start_time,
			$competition->flight_time ? $competition->flight_time : Competition::FLIGHT_TIME_DEFAULT,
			$competition->flight_size ? $competition->flight_size : Competition::FLIGHT_SIZE_DEFAULT,
			'FLIGHT'
		);
	}

	public function add($competition, $registrations) {
		$this->addRegistrations(
			$competition,
			$registrations,
			$competition->start_time,
			$competition->flight_time ? $competition->flight_time : Competition::FLIGHT_TIME_DEFAULT,
			$competition->flight_size ? $competition->flight_size : Competition::FLIGHT_SIZE_DEFAULT,
			'FLIGHT'
		);
	}
	
}