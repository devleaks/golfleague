<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 *	ActiveQuery particularisation for competitions
 */
class CompetitionQuery extends ActiveQuery
{
    public $type;

    public function prepare($builder)
    {
        if ($this->type !== null) {
            $this->andWhere(['competition_type' => $this->type]);
			Yii::trace($this->type, 'CompetitionQuery::prepare');
        }
		if(! Yii::$app->user->isGuest) {
			if(! Yii::$app->user->identity->isA(User::ROLE_ADMIN)) {
				$this->andWhere(['league_id' => Yii::$app->user->identity->league_id]);
				Yii::trace('league='.Yii::$app->user->identity->league_id, 'CompetitionQuery::prepare');
			}
		}
        return parent::prepare($builder);
    }

	public function openForRegistration() {
		$now = date('Y-m-d H:i:s');
		return $this->andWhere(['>','registration_begin', $now])
					->andWhere(['<','registration_end', $now]);
	}


	public function status($statuses) {
		return $this->andWhere(['status' => $statuses]);
	}


	public function type($types) {
		return $this->andWhere(['competition_type' => $types]);
	}
}