<?php

namespace common\models\query;

use Yii;
use yii\db\ActiveQuery;

/**
 *	ActiveQuery particularisation for leagues
 * Important: Read http://www.yiiframework.com/forum/index.php/topic/55466-user-identity-default-scope-problem/.
 */
class LeagueQuery extends ActiveQuery
{
    public function prepare($builder)
    {
		if(Yii::$app->user) {
			if(! Yii::$app->user->getIsGuest()) {
				if(! Yii::$app->user->identity->isAdmin()) {
					$this->andWhere(['id' => Yii::$app->user->identity->league_id]);
					Yii::trace('league='.Yii::$app->user->identity->league_id, 'CompetitionQuery::prepare');
				}
			}
		}
        return parent::prepare($builder);
    }
}