<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $event_type
 * @property string $event_start
 * @property string $event_end
 * @property string $object_type
 * @property integer $object_id
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $recurrence_id
 *
 * @property Reccurence $recurrence
 */
class _Event extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'event_start'], 'required'],
            [['event_start', 'event_end', 'created_at', 'updated_at'], 'safe'],
            [['object_type'], 'string'],
            [['object_id', 'recurrence_id'], 'integer'],
            [['name'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 160],
            [['event_type', 'status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'ID'),
            'name' => Yii::t('igolf', 'Name'),
            'description' => Yii::t('igolf', 'Description'),
            'event_type' => Yii::t('igolf', 'Event Type'),
            'event_start' => Yii::t('igolf', 'Event Start'),
            'event_end' => Yii::t('igolf', 'Event End'),
            'object_type' => Yii::t('igolf', 'Object Type'),
            'object_id' => Yii::t('igolf', 'Object ID'),
            'status' => Yii::t('igolf', 'Status'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'recurrence_id' => Yii::t('igolf', 'Recurrence ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecurrence()
    {
        return $this->hasOne(Reccurence::className(), ['id' => 'recurrence_id']);
    }
}
