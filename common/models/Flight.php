<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "group where group_type = 'flight'".
 */
class Flight extends Group {
	const GROUP_TYPE = self::TYPE_FLIGHT;

    public static function defaultScope($query)
    {
		Yii::trace('Flight::defaultScope');
        $query->andWhere(['group_type' => self::TYPE_FLIGHT]);
    }

	public static function find()
    {
        return new GroupQuery(get_called_class(), ['type' => self::GROUP_TYPE]);
    }

    /**
     * @inheritdoc
     */
	public function beforeSave($insert)
	    {
	        $this->group_type = self::GROUP_TYPE;
	        return parent::beforeSave($insert);
	    }
	

}
