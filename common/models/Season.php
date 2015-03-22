<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "competitions" of type Season.
 *
 * @property Tournaments[] $tournaments
 */
class Season extends Competition
{
	const COMPETITION_TYPE = Competition::TYPE_SEASON;

    public static function defaultScope($query)
    {
        $query->andWhere(['competition_type' => Competition::TYPE_SEASON]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTournaments()
    {
        return $this->hasMany(Tournament::className(), ['parent_id' => 'id']);
    }
}
