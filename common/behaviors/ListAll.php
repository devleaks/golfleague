<?php

namespace common\behaviors;

use yii\helpers\ArrayHelper;

/**
 *	Constant behavior/trait inspects a class and extract all constants whose name match a supplied pattern.
 */
trait ListAll {

	/**
	 * getList returns list of all of this entity.
	 *
	 * @param  $constant_prefix first characters of constant name
	 *
	 * @return array of key,localized value.
	 */
    static function getList($key = 'id', $display = 'display_name', $orderBy = 'display_name') {
		$q = self::find();
		if($orderBy) {
			$q->orderBy($orderBy);
		}
		return ArrayHelper::map($q->asArray()->all(), $key, $display);
    }

}
