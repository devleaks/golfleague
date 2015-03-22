<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\components\Constant;

/**
 * This is the model class for table "events".
 *
 * @property integer $id
 * @property string $object_type
 * @property integer $object_id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $event_type
 * @property string $status
 * @property string $event_start
 * @property string $event_end
 */
class Event extends _Event
{
	use Constant;
	
    /** Competition date */
    const TYPE_COMPETITION = 'COMPETITION';
    /** Competition registration date */
    const TYPE_REGISTRATION = 'REGISTRATION';

    /** Dinner date */
    const TYPE_DINNER = 'DINNER';
    /** Competition prize ceremony */
    const TYPE_CEREMONY = 'CEREMONY';

    /** Status */
    const STATUS_ACTIVE = 'ACTIVE';
    /** Status */
    const STATUS_CLOSED = 'CLOSED';


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
            'id' => Yii::t('golfleague', 'Event'),
            'object_type' => Yii::t('golfleague', 'Object Type'),
            'object_id' => Yii::t('golfleague', 'Object'),
            'name' => Yii::t('golfleague', 'Name'),
            'description' => Yii::t('golfleague', 'Description'),
            'created_at' => Yii::t('golfleague', 'Created At'),
            'updated_at' => Yii::t('golfleague', 'Updated At'),
            'event_type' => Yii::t('golfleague', 'Event Type'),
            'status' => Yii::t('golfleague', 'Status'),
            'event_start' => Yii::t('golfleague', 'Event Start'),
            'event_end' => Yii::t('golfleague', 'Event End'),
        ];
    }

	public function getColor() {
		switch($this->event_type) {
			case self::TYPE_COMPETITION: return 'info'; break;
			case self::TYPE_REGISTRATION: return 'info'; break;
			case self::TYPE_DINNER: return 'warning'; break;
			case self::TYPE_CEREMONY: return 'danger'; break;
			default:
				return 'success';
		}
	}
}
