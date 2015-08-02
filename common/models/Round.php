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
        return new CompetitionQuery(get_called_class(), ['type' => self::COMPETITION_TYPE]);
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
	 * Return Level of a matchplay competition: 1 = final, 2 = semi-final, 3 = quarter final, etc.
	 */
	public function getLevel() {
		if($this->rule->rule_type == Rule::TYPE_MATCHPLAY) {
			if(($numRegs = $this->getRegistrations()->andWhere(['status' => Registration::STATUS_REGISTERED])->count()) > 0) {
				Yii::trace('count='.$numRegs.', log='.log($numRegs, 2), 'getLevel');
				return $numRegs > 0 ? intval(log($numRegs, 2)) - 1 : 0;				
			}
		}
		return 0;
	}

	/**
	 * @inheritdoc
	 */
	public function currentRound() {
		return $this;
	}

}
