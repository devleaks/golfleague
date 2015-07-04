<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "competitions" of type Season.
 *
 */
class Season extends Competition
{
	const COMPETITION_TYPE = Competition::TYPE_SEASON;

    public static function defaultScope($query)
    {
		Yii::trace('Season::defaultScope');
        $query->andWhere(['competition_type' => Competition::TYPE_SEASON]);
    }

	public static function find()
    {
        return new CompetitionQuery(get_called_class(), ['type' => self::COMPETITION_TYPE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTournaments()
    {
        return $this->hasMany(Tournament::className(), ['parent_id' => 'id']);
    }

	/**
	 * @inheritdoc
	 */
	public function currentMatch() {
		return null;
	}

}
