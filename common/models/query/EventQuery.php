<?php

namespace common\models\query;

use Yii;
use yii\db\ActiveQuery;

/**
 *	ActiveQuery particularisation for events
 */
class EventQuery extends ActiveQuery
{
    public function prepare($builder)
    {
		if(Yii::$app->user) {
			if(! Yii::$app->user->getIsGuest()) {
				if(! Yii::$app->user->identity->isAdmin()) {
					$this->andWhere(['league_id' => Yii::$app->user->identity->league_id]);
					Yii::trace('league='.Yii::$app->user->identity->league_id, 'CompetitionQuery::prepare');
				}
			}
		}
        return parent::prepare($builder);
    }
}