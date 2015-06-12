<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "competitions" of type Tournament.
 *
 * @property Match[] $matches
 * @property Season $season
 */
class Tournament extends Competition
{
	const COMPETITION_TYPE = self::TYPE_TOURNAMENT;

    public static function defaultScope($query)
    {
		Yii::trace('Tournament::defaultScope');
         $query->andWhere(['competition_type' => self::TYPE_TOURNAMENT]);
    }

	public static function find()
    {
        return new CompetitionQuery(get_called_class(), ['type' => self::COMPETITION_TYPE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeason()
    {
        return $this->hasOne(Season::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatches()
    {
        return $this->hasMany(Match::className(), ['parent_id' => 'id']);
    }

	/**
	 * @inheritdoc
	 */
	public function currentMatch() {
		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function hasScores() {
		return $this;
	}
}
