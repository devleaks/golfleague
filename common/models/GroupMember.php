<?php

namespace common\models;

use Yii;

/**
 * This is a model class for table "group_member".
 */
class GroupMember extends _GroupMember
{
	/** Object Types */
	const PRACTICE = 'PRACTICE';
	const REGISTRATION = 'REGISTRATION';
	const TEAM = 'TEAM';
	
	public function getObject() {
		switch($this->object_type) {
			case self::PRACTICE:
				return Practice::findOne($this->object_id);
				break;
			case self::REGISTRATION:
				return Registration::findOne($this->object_id);
				break;
			case self::TEAM:
				return Team::findOne($this->object_id);
				break;
		}
		return null;
	}
}
