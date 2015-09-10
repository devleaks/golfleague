<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "group where group_type = 'team'".
 */
class Team extends Group {
	const GROUP_TYPE = self::TYPE_TEAM;

    public static function defaultScope($query)
    {
		Yii::trace('Team::defaultScope');
        $query->andWhere(['group_type' => self::TYPE_TEAM]);
    }

	public static function find()
    {
        return new GroupQuery(get_called_class(), ['type' => self::GROUP_TYPE]);
    }

}
