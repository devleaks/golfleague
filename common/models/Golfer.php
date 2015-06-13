<?php

namespace common\models;

use Yii;
use common\behaviors\Constant;
use dektrium\user\models\User;
use yii\db\ActiveRecord;

use common\models\handicap\HandicapEGA;

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
	            [['hand'], 'in','range' => [Golfer::HAND_LEFT,Golfer::HAND_RIGHT]],
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
	
	public function allowed($tees, $handicap_system = null) {
		if(! $handicap_system)
			$handicap_system = new HandicapEGA();
		return $handicap_system->allowed($tees, $this);
	}

	public function playingHandicap($tees, $handicap_system = null) {
		if(! $handicap_system)
			$handicap_system = new HandicapEGA();
		return array_sum($ees, $handicap_system);
	}

}
