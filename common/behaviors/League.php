<?php

namespace common\behaviors;

use Yii;

/**
 *	League behavior/trait filters models on league_id matching the user's league_id.
 *  Important: Read http://www.yiiframework.com/forum/index.php/topic/55466-user-identity-default-scope-problem/.
 *  Note: Read this to understand how it works: http://www.refulz.com/traits-method-precedence-and-conflict-resolution/.
 */
trait League {

    /**
     * @inheritdoc
     */
    public function prepare($builder)
    {
		if(Yii::$app->user) {
			if(! Yii::$app->user->getIsGuest()) {
				if(! Yii::$app->user->identity->isAdmin()) {
					$this->andWhere(['league_id' => Yii::$app->user->identity->league_id]);
					Yii::trace('league='.Yii::$app->user->identity->league_id, 'trait League::prepare');
				}
			}
		}
        return parent::prepare($builder);
    }

}
