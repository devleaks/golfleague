<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 *	Gii Extension class
 */
class Start extends _Start
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
            'id' => Yii::t('igolf', 'ID'),
            'gender' => Yii::t('igolf', 'Gender'),
            'age_min' => Yii::t('igolf', 'Age Min'),
            'age_max' => Yii::t('igolf', 'Age Max'),
            'handicap_min' => Yii::t('igolf', 'Handicap Min'),
            'handicap_max' => Yii::t('igolf', 'Handicap Max'),
            'tees_id' => Yii::t('igolf', 'Tees'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'competition_id' => Yii::t('igolf', 'Competition ID'),
        ];
    }


}