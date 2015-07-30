<?php

namespace common\models;

use common\behaviors\Constant;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "recurrence".
 */
class Recurrence extends Model
{
	use Constant;
	
	const FREQUENCY_NONE = 'NONE';
//	const FREQUENCY_HOURLY = 'HOURLY';
	const FREQUENCY_DAILY = 'DAILY';
	const FREQUENCY_WEEKLY = 'WEEKLY';
	const FREQUENCY_MONTHLY = 'MONTHLY';
	const FREQUENCY_YEARLY = 'YEARLY';
	
	const POSITION_FIRST = 'FIRST';
	const POSITION_SECOND = 'SECOND';
	const POSITION_THIRD = 'THIRD';
	const POSITION_FOURTH = 'FOURTH';
	const POSITION_LAST = 'LAST';

	const UNTIL_NEVER = 'NEVER';
	const UNTIL_COUNT = 'COUNT';
	const UNTIL_DATE = 'DATE';

	const DAY_DAY = 'DAY';
	const DAY_WEEKDAY = 'WEEKDAY';
	const DAY_WEEKEND = 'WEEKENDDAY';
	
	
	public $date_start;
	public $time_start;
	public $date_end;
	public $frequency;
	public $interval;
	public $count;
	public $until;

	public $weekstartday;
	
	public $bypos;
	public $byday;
//	public $byweekno;
	public $byweekday;
	public $bymonth;
	public $bymonthday;
	public $byyearday;
//	public $byhour;
//	public $byminute;
//	public $bysecond;
//	public $byeaster;

	// Working vairables for form
	public $option;
	public $repeat;
	public $bypos4week;
	public $bypos4month;
	public $bypos4year;
	public $weekday4month;
	public $weekday4year;
	public $monthday4year;
	public $month4year;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
				[
					'date_start', 'time_start', 'date_end', 'frequency', 'interval', 'count', 'until',
					'weekstartday', 'bypos', 'byday', 'byweekno', 'byweekday', 'bymonth',
					'bymonthday', 'byyearday', 'byhour', 'byminute', 'bysecond', 'byeaster',
					'option', 'repeat', 'bypos4week', 'bypos4month', 'bypos4year', 'weekday4month', 'weekday4year', 'monthday4year', 'month4year'
				],
				'safe'
			],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'date_start' => Yii::t('golf', 'Start Date'),
			'time_start' => Yii::t('golf', 'Start Time'),
			'date_end' => Yii::t('golf', 'End Date'),
			'frequency' => Yii::t('golf', 'Frequency'),
			'interval' => Yii::t('golf', 'Interval'),
			'count' => Yii::t('golf', 'Count'),
			'until' => Yii::t('golf', 'Until'),

			'weekstartday' => Yii::t('golf', 'Week Day'),

			'bypos' => Yii::t('golf', 'Position'),
			'byday' => Yii::t('golf', 'By Day'),
			'byweekno' => Yii::t('golf', 'By Week Number'),
			'byweekday' => Yii::t('golf', 'By Week Day'),
			'bymonth' => Yii::t('golf', 'bymonth'),
			'bymonthday' => Yii::t('golf', 'By Month Day'),
			'byyearday' => Yii::t('golf', 'By Year Day'),
			'byhour' => Yii::t('golf', 'By Hour'),
			'byminute' => Yii::t('golf', 'By Minute'),
			'bysecond' => Yii::t('golf', 'By Second'),
			'byeaster' => Yii::t('golf', 'By Easter Egg'),

			'option' => Yii::t('golf', 'Option'),
			'repeat' => Yii::t('golf', 'Repeat'),
        ];
    }
}
