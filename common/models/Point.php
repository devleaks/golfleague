<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "points".
 *
 * @property integer $id
 * @property integer $rule_id
 * @property integer $position
 * @property integer $points
 *
 * @property Rules $rules
 */
class Point extends _Point
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
            'id' => Yii::t('igolf', 'Point'),
            'rule_id' => Yii::t('igolf', 'Rules'),
            'position' => Yii::t('igolf', 'Position'),
            'points' => Yii::t('igolf', 'Points'),
        ];
    }

}
