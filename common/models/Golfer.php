<?php

namespace common\models;

use common\behaviors\Constant;
use common\models\User;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "golfers".
 */
class Golfer extends _Golfer implements Opponent {
	use Constant;

    /** Golfer genders */
    const GENDER_GENTLEMAN	= 'GENTLEMAN';
    const GENDER_LADY		= 'LADY';

    /** Golfer dexterity */
    const HAND_RIGHT	= 'RIGHT';
    const HAND_LEFT		= 'LEFT';

	const DEFAULT_HANDICAP = 36;

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
            'id' => Yii::t('golf', 'Golfer'),
        	'league_id' => Yii::t('golf', 'League'),
            'name' => Yii::t('golf', 'Name'),
            'email' => Yii::t('golf', 'Email'),
            'phone' => Yii::t('golf', 'Phone'),
            'handicap' => Yii::t('golf', 'Handicap'),
            'gender' => Yii::t('golf', 'Gender'),
            'hand' => Yii::t('golf', 'Hand'),
            'homecourse' => Yii::t('golf', 'Homecourse'),
            'user_id' => Yii::t('golf', 'User'),
            'facility_id' => Yii::t('golf', 'Facility'),
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

	public function allowed($tees) {
		Yii::trace('top', 'Golfer::allowed');
		return Yii::$app->golfleague->handicap_system->allowed($tees, $this);
	}

	public function playingHandicap($tees) {
		return array_sum($this->allowed($tees));
	}

	public function getHandicap() {
		return $this->handicap ? $this->handicap : self::DEFAULT_HANDICAP;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getHandicapHistory($count = 10) {
		$hh = [];
		foreach($this->getHandicapHistories()->orderBy('event_date desc')->limit($count)->each() as $h) {
			$hh[] = $h->new_handicap;
		}
		return count($hh) > 0 ? array_reverse($hh) : null;
	}
	
}
