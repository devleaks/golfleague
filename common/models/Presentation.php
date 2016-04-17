<?php

namespace common\models;

use common\behaviors\Constant;
use common\behaviors\ListAll;

use Yii;
use \common\models\base\Presentation as BasePresentation;

/**
 * This is the model class for table "presentation".
 */
class Presentation extends BasePresentation
{
	use Constant;
	use ListAll;

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

	/**
	 * returns associative array of status, color for all possible status values
	 * Bootstrap colors are: default  primary  success  info  warning  danger
	 *
	 * @param $what Attribute to get color for.
	 *
	 * @return array()
	 */
	public static function getLabelColors($what) {
		$colors = [];
		switch($what) {
			case 'status':
				$colors = [
					self::STATUS_ACTIVE => 'success',
					self::STATUS_INACTIVE => 'warning',
				];
				break;
		}
		return $colors;
	}
}
