<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "flights".
 *
 * @property integer $id
 * @property integer $competition_id
 * @property integer $position
 * @property string $note
 *
 * @property Matches $match
 * @property Registrations[] $registrations
 */
class Flight extends _Flight
{
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
            'id' => Yii::t('igolf', 'Flight'),
            'competition_id' => Yii::t('igolf', 'Match'),
            'position' => Yii::t('igolf', 'Position'),
            'note' => Yii::t('igolf', 'Note'),
        ];
    }

    /**
     * Delete model after removing from registrations
     */
    public function cleanRegistrations($delete = false)
    {
        foreach($this->getRegistrations()->each() as $r) {
			$r->flight_id = null;
			$r->save();
		}
		if($delete)
			$this->delete();
    }
}
