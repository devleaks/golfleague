<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;
use common\behaviors\MediaBehavior;
use yii2fullcalendar\models\Event as FullCalendarEvent;
use yii\helpers\Url;

/**
 * This is the model class for table "events".
 *
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
    /** Golfer birthday */
    const TYPE_BIRTHDAY = 'BIRTHDAY';
    /** Other event */
    const TYPE_MISC = 'MISCELLANEOUS';

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
				'uploadFile' => [
	                'class' => MediaBehavior::className(),
	                'mediasAttributes' => ['media']
	            ]
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
	            [['status'], 'in', 'range' => array_keys(self::getConstants('STATUS_'))],
        	]
		);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'Event'),
	    	'league_id' => Yii::t('golf', 'League'),
	        'facility_id' => Yii::t('golf', 'Facility'),
            'object_type' => Yii::t('golf', 'Object Type'),
            'object_id' => Yii::t('golf', 'Object'),
            'name' => Yii::t('golf', 'Name'),
            'description' => Yii::t('golf', 'Description'),
            'event_type' => Yii::t('golf', 'Event Type'),
            'event_start' => Yii::t('golf', 'Event Start'),
            'event_end' => Yii::t('golf', 'Event End'),
            'media' => Yii::t('golf', 'Pictures'),
            'status' => Yii::t('golf', 'Status'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
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
			case Competition::TYPE_ROUND:
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
