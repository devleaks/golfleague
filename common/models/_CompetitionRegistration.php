<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "competition_registration".
 *
 * @property integer $id
 * @property string $name
 * @property string $event_type
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $description
 * @property string $event_start
 * @property string $event_end
 * @property string $object_type
 * @property integer $object_id
 */
class _CompetitionRegistration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'competition_registration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id'], 'integer'],
            [['name', 'event_start', 'event_end'], 'required'],
            [['created_at', 'updated_at', 'event_start', 'event_end'], 'safe'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 80],
            [['event_type', 'object_type'], 'string', 'max' => 11],
            [['status'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'name' => Yii::t('golf', 'Name'),
            'event_type' => Yii::t('golf', 'Event Type'),
            'status' => Yii::t('golf', 'Status'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'description' => Yii::t('golf', 'Description'),
            'event_start' => Yii::t('golf', 'Event Start'),
            'event_end' => Yii::t('golf', 'Event End'),
            'object_type' => Yii::t('golf', 'Object Type'),
            'object_id' => Yii::t('golf', 'Object ID'),
        ];
    }
}
