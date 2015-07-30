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
            'id' => Yii::t('golf', 'ID'),
            'subject' => Yii::t('golf', 'Subject'),
            'body' => Yii::t('golf', 'Body'),
            'message_start' => Yii::t('golf', 'Message Start'),
            'message_end' => Yii::t('golf', 'Message End'),
            'message_type' => Yii::t('golf', 'Message Type'),
            'facility_id' => Yii::t('golf', 'Facility ID'),
            'status' => Yii::t('golf', 'Status'),
            'created_by' => Yii::t('golf', 'Created By'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
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
