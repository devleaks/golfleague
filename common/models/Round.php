<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "competitions" of type Match.
 *
 * @property Tournament $tournament
 */
class Round extends Competition
{
	const COMPETITION_TYPE = self::TYPE_ROUND;

    public static function defaultScope($query)
    {
		Yii::trace('Round::defaultScope');
        $query->andWhere(['competition_type' => self::TYPE_ROUND]);
    }

	public static function find()
    {
        return new query\CompetitionQuery(get_called_class(), ['type' => self::COMPETITION_TYPE]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
			parent::rules(),
			[
	            [['course_id', 'holes', 'rule_id', 'start_date'], 'required'],
        	]
		);
    }

    /**
     * @inheritdoc
     */
	public function getParentCandidates($add_empty = true) {
		return ArrayHelper::map([''=>''] + Tournament::find()->asArray()->all(), 'id', 'name');
	}
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTournament()
    {
        return $this->hasOne(Tournament::className(), ['id' => 'parent_id']);
    }

	/**
	 * Return number of matches in Matchplay competition
	 */
	public function getNumberOfMatches() {
		return intval(pow(2, $this->getLevel()));
	}

	/**
	 * @inheritdoc
	 */
	public function currentRound() {
		return $this;
	}

}
