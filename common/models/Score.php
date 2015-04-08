<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "score".
 */
class Score extends _Score
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
            'scorecard_id' => Yii::t('igolf', 'Scorecard'),
            'hole_id' => Yii::t('igolf', 'Hole'),
            'score' => Yii::t('igolf', 'Score'),
            'putts' => Yii::t('igolf', 'Putts'),
            'penalty' => Yii::t('igolf', 'Penalty'),
            'sand' => Yii::t('igolf', 'Sand'),
            'note' => Yii::t('igolf', 'Note'),
            'drive' => Yii::t('igolf', 'Drive'),
            'regulation' => Yii::t('igolf', 'Regulation'),
            'approach' => Yii::t('igolf', 'Approach'),
            'putt' => Yii::t('igolf', 'Putt'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }
}
