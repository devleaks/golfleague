<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property string $subject
 * @property string $body
 * @property string $message_start
 * @property string $message_end
 * @property string $message_type
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class _Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['body'], 'string'],
            [['message_start', 'message_end', 'created_at', 'updated_at'], 'safe'],
            [['subject'], 'string', 'max' => 80],
            [['message_type'], 'string', 'max' => 40],
            [['status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golfleague', 'ID'),
            'subject' => Yii::t('golfleague', 'Subject'),
            'body' => Yii::t('golfleague', 'Body'),
            'message_start' => Yii::t('golfleague', 'Message Start'),
            'message_end' => Yii::t('golfleague', 'Message End'),
            'message_type' => Yii::t('golfleague', 'Message Type'),
            'status' => Yii::t('golfleague', 'Status'),
            'created_at' => Yii::t('golfleague', 'Created At'),
            'updated_at' => Yii::t('golfleague', 'Updated At'),
        ];
    }
}
