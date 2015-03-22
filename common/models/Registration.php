<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\Constant;

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
            'id' => Yii::t('golfleague', 'Registration'),
            'competition_id' => Yii::t('golfleague', 'Competition'),
            'golfer_id' => Yii::t('golfleague', 'Golfer'),
            'status' => Yii::t('golfleague', 'Status'),
            'flight_id' => Yii::t('golfleague', 'Flight'),
            'tees_id' => Yii::t('golfleague', 'Tees'),
            'created_at' => Yii::t('golfleague', 'Created At'),
            'updated_at' => Yii::t('golfleague', 'Updated At'),
            'position' => Yii::t('golfleague', 'Position'),
            'score' => Yii::t('golfleague', 'Score'),
            'points' => Yii::t('golfleague', 'Points'),
            'note' => Yii::t('golfleague', 'Note'),
            'team_id' => Yii::t('golfleague', 'Team'),
            'score_net' => Yii::t('golfleague', 'Score Net'),
        ];
    }

    public function registerButton($url, $action)
    {
        switch($action) {
            case 'approve':
                $icon    = 'thumbs-up';
                break;
            case 'reject':
                $icon    = 'thumbs-down';
                break;
            default:
                return null;
                break;
        }
        return Html::a('<span class="glyphicon glyphicon-'.$icon.'"></span>', $url);
    }

    public function createActionUrl($action, $key, $index)
    {
        return Url::to(['registration/'.strtolower($action), 'id' => $this->id ]);
    }
}
