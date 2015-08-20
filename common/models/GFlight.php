<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "group where group_type = 'flight'".
 */
class GFlight extends Group {
	const GROUP_TYPE = self::TYPE_FLIGHT;

    public static function defaultScope($query)
    {
		Yii::trace('GFlight::defaultScope');
        $query->andWhere(['group_type' => self::TYPE_FLIGHT]);
    }

	public static function find()
    {
        return new CompetitionQuery(get_called_class(), ['type' => self::GROUP_TYPE]);
    }

}
