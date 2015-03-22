<?php

namespace common\models;

use Yii;
use common\components\Constant;
use dektrium\user\models\User;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "golfers".
 */
class Golfer extends _Golfer
{
	use Constant;

    /** Golfer is a man */
    const GENDER_GENTLEMAN = 'GENTLEMAN';
    /** Golfer is a woman */
    const GENDER_LADY = 'LADY';

    const HAND_RIGHT = 'RIGHT';
    const HAND_LEFT = 'LEFT';

	const DEFAULT_HANDICAP = 54;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); /* mysql datetime format is ‘AAAA-MM-JJ HH:MM:SS’*/},
                ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
			parent::rules(),
			[
	            [['hand'], 'in','range' => ['left','right']],
	            [['gender'], 'in','range' => [Golfer::GENDER_GENTLEMAN,Golfer::GENDER_LADY]],
        	]
		);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golfleague', 'Golfer'),
            'name' => Yii::t('golfleague', 'Name'),
            'email' => Yii::t('golfleague', 'Email'),
            'phone' => Yii::t('golfleague', 'Phone'),
            'handicap' => Yii::t('golfleague', 'Handicap'),
            'gender' => Yii::t('golfleague', 'Gender'),
            'hand' => Yii::t('golfleague', 'Hand'),
            'homecourse' => Yii::t('golfleague', 'Homecourse'),
            'user_id' => Yii::t('golfleague', 'User'),
        ];
    }

    /**
     * @return  Golfer Currently logged in user as Golfer
     */
    public static function me()
    {
        return Golfer::findOne(['user_id' => Yii::$app->user->id]);
    }

	public function age() {
		return $this->birthdate ? floor((time() - strtotime($this->birthdate))/31556926) : 0;
	}

	public function handicap() {
		return $this->handicap ? $this->handicap : self::DEFAULT_HANDICAP;
	}

}
