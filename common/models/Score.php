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
            'scorecard_id' => Yii::t('golfleague', 'Scorecard'),
            'hole_id' => Yii::t('golfleague', 'Hole'),
            'score' => Yii::t('golfleague', 'Score'),
            'putts' => Yii::t('golfleague', 'Putts'),
            'penalty' => Yii::t('golfleague', 'Penalty'),
            'sand' => Yii::t('golfleague', 'Sand'),
            'note' => Yii::t('golfleague', 'Note'),
            'drive' => Yii::t('golfleague', 'Drive'),
            'regulation' => Yii::t('golfleague', 'Regulation'),
            'approach' => Yii::t('golfleague', 'Approach'),
            'putt' => Yii::t('golfleague', 'Putt'),
            'created_at' => Yii::t('golfleague', 'Created At'),
            'updated_at' => Yii::t('golfleague', 'Updated At'),
        ];
    }
}
