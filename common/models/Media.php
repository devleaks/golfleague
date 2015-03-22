<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "media".
 */
class Media extends _Media
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
            'id' => Yii::t('golfleague', 'Media'),
            'object_type' => Yii::t('golfleague', 'Object Type'),
            'object_id' => Yii::t('golfleague', 'Object'),
            'name' => Yii::t('golfleague', 'Name'),
            'description' => Yii::t('golfleague', 'Description'),
            'created_at' => Yii::t('golfleague', 'Created At'),
            'updated_at' => Yii::t('golfleague', 'Updated At'),
            'filename' => Yii::t('golfleague', 'Filename'),
            'media_type' => Yii::t('golfleague', 'Media Type'),
            'mime_type' => Yii::t('golfleague', 'Mime Type'),
            'status' => Yii::t('golfleague', 'Status'),
        ];
    }
}
