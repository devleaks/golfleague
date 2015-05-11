<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use common\behaviors\Constant;

/**
 * This is the model class for table "registrations".
 */
class Registration extends _Registration
{
	use Constant;
	
    /** Golfer has not registered to the competition, registration does not exist */
    const STATUS_UNREGISTERED = 'UNREGISTERED';

    /** Golfer has just registered to the competition */
    const STATUS_PENDING = 'PENDING';

    /** Registered: Golfer is registered to the competition */
    const STATUS_REGISTERED = 'REGISTERED';

    /** Golfer registration is rejected */
    const STATUS_REJECTED = 'REJECTED';

    /** Starter has confirmed registration for next event */
    const STATUS_CONFIRMED = 'CONFIRMED';

    /** Golfer cancelled registration before event */
    const STATUS_CANCELLED = 'CANCELLED';

    /** Golfer is confirmed but does not participage to competition */
    const STATUS_FORFEIT = 'FORFEIT';

    /** Golfer played but is eliminated by scoring position */
    const STATUS_MISSEDCUT = 'MISSEDCUT';

    /** Golfer played but is eliminated by competitor */
    const STATUS_ELIMINATED = 'ELIMINATED';

    /** Golfer is disqualified form compeition, reason in comments */
    const STATUS_DISQUALIFIED = 'DISQUALIFIED';

    /** Golfer has legitimately widthdrawn form competition */
    const STATUS_WITHDRAWN = 'WITHDRAWN';

    /** Golfer is invited to next stage */
    const STATUS_QUALIFIED = 'QUALIFIED';

    /** Special status keyword */
    const ACTION_DELETE = 'DELETE';


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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'Registration'),
            'competition_id' => Yii::t('igolf', 'Competition'),
            'golfer_id' => Yii::t('igolf', 'Golfer'),
            'status' => Yii::t('igolf', 'Status'),
            'flight_id' => Yii::t('igolf', 'Flight'),
            'tees_id' => Yii::t('igolf', 'Tees'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'position' => Yii::t('igolf', 'Position'),
            'score' => Yii::t('igolf', 'Score'),
            'points' => Yii::t('igolf', 'Points'),
            'note' => Yii::t('igolf', 'Note'),
            'team_id' => Yii::t('igolf', 'Team'),
            'score_net' => Yii::t('igolf', 'Score Net'),
        ];
    }

}
