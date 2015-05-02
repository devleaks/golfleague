<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;
use yii2fullcalendar\models\Event as FullCalendarEvent;
use yii\helpers\Url;

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
            'id' => Yii::t('igolf', 'Event'),
            'object_type' => Yii::t('igolf', 'Object Type'),
            'object_id' => Yii::t('igolf', 'Object'),
            'name' => Yii::t('igolf', 'Name'),
            'description' => Yii::t('igolf', 'Description'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'event_type' => Yii::t('igolf', 'Event Type'),
            'status' => Yii::t('igolf', 'Status'),
            'event_start' => Yii::t('igolf', 'Event Start'),
            'event_end' => Yii::t('igolf', 'Event End'),
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
	
	
	/**
	 * Transform Event into CalendarEvent for FullCalendar
	 */
	public function getFullCalendarEvent() {
		if($this->event_start) $start = \DateTime::createFromFormat('Y-m-d H:i:s', $this->event_start);
		if($this->event_end  ) $end   = \DateTime::createFromFormat('Y-m-d H:i:s', $this->event_end);

		switch($this->object_type) {
			case Competition::TYPE_MATCH:
			case Competition::TYPE_TOURNAMENT:
			case Competition::TYPE_SEASON:
			case 'COMPETITION':
				$url = Url::to(['/competition/view', 'id'=>$this->object_id]);
				break;
			default:
				$url = Url::to(['view', 'id'=>$this->id]);
				break;
		}

		return new FullCalendarEvent([
			'id' => $this->id,
			'title' => $this->name,
			'url' => $url,
			'className' => 'btn-'.$this->getColor(),
			'start' => isset($start) ? date('Y-m-d\TH:m:s\Z',$start->getTimestamp()) : null,
			'end' => isset($end) ? date('Y-m-d\TH:m:s\Z',$end->getTimestamp()) : null,
		]);
	}
}
