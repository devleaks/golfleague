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
			'date_start' => Yii::t('igolf', 'Start Date'),
			'time_start' => Yii::t('igolf', 'Start Time'),
			'date_end' => Yii::t('igolf', 'End Date'),
			'frequency' => Yii::t('igolf', 'Frequency'),
			'interval' => Yii::t('igolf', 'Interval'),
			'count' => Yii::t('igolf', 'Count'),
			'until' => Yii::t('igolf', 'Until'),

			'weekstartday' => Yii::t('igolf', 'Week Day'),

			'bypos' => Yii::t('igolf', 'Position'),
			'byday' => Yii::t('igolf', 'By Day'),
			'byweekno' => Yii::t('igolf', 'By Week Number'),
			'byweekday' => Yii::t('igolf', 'By Week Day'),
			'bymonth' => Yii::t('igolf', 'bymonth'),
			'bymonthday' => Yii::t('igolf', 'By Month Day'),
			'byyearday' => Yii::t('igolf', 'By Year Day'),
			'byhour' => Yii::t('igolf', 'By Hour'),
			'byminute' => Yii::t('igolf', 'By Minute'),
			'bysecond' => Yii::t('igolf', 'By Second'),
			'byeaster' => Yii::t('igolf', 'By Easter Egg'),

			'option' => Yii::t('igolf', 'Option'),
			'repeat' => Yii::t('igolf', 'Repeat'),
        ];
    }
}
