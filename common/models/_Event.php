<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property string $object_type
 * @property integer $object_id
 * @property string $name
 * @property string $description
 * @property string $event_start
 * @property string $event_end
 * @property string $event_type
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
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
            [['object_type'], 'string'],
            [['object_id'], 'integer'],
            [['event_start', 'event_end', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 255],
            [['event_type'], 'string', 'max' => 40],
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
            'object_type' => Yii::t('golfleague', 'Object Type'),
            'object_id' => Yii::t('golfleague', 'Object ID'),
            'name' => Yii::t('golfleague', 'Name'),
            'description' => Yii::t('golfleague', 'Description'),
            'event_start' => Yii::t('golfleague', 'Event Start'),
            'event_end' => Yii::t('golfleague', 'Event End'),
            'event_type' => Yii::t('golfleague', 'Event Type'),
            'status' => Yii::t('golfleague', 'Status'),
            'created_at' => Yii::t('golfleague', 'Created At'),
            'updated_at' => Yii::t('golfleague', 'Updated At'),
        ];
    }
}
