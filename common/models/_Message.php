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
 * @property integer $facility_id
 * @property string $status
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Facility $facility
 * @property User $createdBy
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
            [['subject', 'body'], 'required'],
            [['body'], 'string'],
            [['message_start', 'message_end', 'created_at', 'updated_at'], 'safe'],
            [['facility_id', 'created_by'], 'integer'],
            [['subject'], 'string', 'max' => 80],
            [['message_type', 'status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'ID'),
            'subject' => Yii::t('igolf', 'Subject'),
            'body' => Yii::t('igolf', 'Body'),
            'message_start' => Yii::t('igolf', 'Message Start'),
            'message_end' => Yii::t('igolf', 'Message End'),
            'message_type' => Yii::t('igolf', 'Message Type'),
            'facility_id' => Yii::t('igolf', 'Facility ID'),
            'status' => Yii::t('igolf', 'Status'),
            'created_by' => Yii::t('igolf', 'Created By'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacility()
    {
        return $this->hasOne(Facility::className(), ['id' => 'facility_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
