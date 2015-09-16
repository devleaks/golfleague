<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class to capture recurrences.
 */
class CaptureRecurrence extends Model
{
	public $date_start;
	public $date_end;
	public $frequency;
	public $interval;
	public $count;
	public $byday;
	public $bymonth;
	public $bymonthday;
	public $bypos;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'action'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'date' => Yii::t('golf', 'Date'),
        	'action' => Yii::t('golf', 'Action'),
        ];
    }
}
