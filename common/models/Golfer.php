<?php

namespace common\models;

use Yii;
use common\behaviors\Constant;
use dektrium\user\models\User;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "golfers".
 */
class Golfer extends _Golfer
{
	use Constant;

    /** Golfer genders */
    const GENDER_GENTLEMAN	= 'GENTLEMAN';
    const GENDER_LADY		= 'LADY';

    /** Golfer dexterity */
    const HAND_RIGHT	= 'RIGHT';
    const HAND_LEFT		= 'LEFT';

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
	            [['hand'], 'in', 'range' => array_keys(self::getConstants('HAND_'))],
            	[['gender'], 'in', 'range' => array_keys(self::getConstants('GENDER_'))],
        	]
		);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'Golfer'),
            'name' => Yii::t('igolf', 'Name'),
            'email' => Yii::t('igolf', 'Email'),
            'phone' => Yii::t('igolf', 'Phone'),
            'handicap' => Yii::t('igolf', 'Handicap'),
            'gender' => Yii::t('igolf', 'Gender'),
            'hand' => Yii::t('igolf', 'Hand'),
            'homecourse' => Yii::t('igolf', 'Homecourse'),
            'user_id' => Yii::t('igolf', 'User'),
            'facility_id' => Yii::t('igolf', 'Facility'),
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
	
	public function allowed($tees) {
		Yii::trace('top', 'Golfer::allowed');
		return Yii::$app->golfleague->handicap_system->allowed($tees, $this);
	}

	public function playingHandicap($tees) {
		return array_sum($this->allowed($tees));
	}

}
