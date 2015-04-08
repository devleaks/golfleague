<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "recurrence".
 */
class Recurrence extends _Recurrence
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
            'id' => Yii::t('igolf', 'Recurrence'),
            'name' => Yii::t('igolf', 'Name'),
            'description' => Yii::t('igolf', 'Description'),
            'recurrence_start' => Yii::t('igolf', 'Recurrence Start'),
            'recurrence_end' => Yii::t('igolf', 'Recurrence End'),
            'offset' => Yii::t('igolf', 'Offset'),
            'recurrence' => Yii::t('igolf', 'Recurrence'),
            'recurrence_type' => Yii::t('igolf', 'Recurrence Type'),
            'status' => Yii::t('igolf', 'Status'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }
}
