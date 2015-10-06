<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * User model
 */
class User extends _User
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
	const DEFAULT_ROLE = 'golfer';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                'timestamp' => [
                        'class' => TimestampBehavior::className(),
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); /* mysql datetime format is ‘AAAA-MM-JJ HH:MM:SS’*/},
                ],
        ];
    }

	/**
	 * Returns "league" role of user, from roles attributiion. Default is golfer. Null if not loggued in.
	 */
    static public function getRole() {
		if(!Yii::$app->user->isGuest) {
			if($user = User::findOne(Yii::$app->user->identity->id))
				if($key = array_search($user->role, Yii::$app->golfleague->league_roles))
					return $key;
			return self::DEFAULT_ROLE;
		}
		return null;
	}


}
