<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 *	ActiveQuery particularisation for users
 * Important: Read http://www.yiiframework.com/forum/index.php/topic/55466-user-identity-default-scope-problem/.
 */
class UserQuery extends ActiveQuery
{
    public function prepare($builder)
    {
		if(Yii::$app->user) {
			if(! Yii::$app->user->getIsGuest()) {
				if(! Yii::$app->user->identity->isA(User::ROLE_ADMIN)) {
					$this->andWhere(['league_id' => Yii::$app->user->identity->league_id]);
					Yii::trace('league='.Yii::$app->user->identity->league_id, 'CompetitionQuery::prepare');
				}
			}
		}
        return parent::prepare($builder);
    }
}