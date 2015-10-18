<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property integer $league_id
 * @property integer $facility_id
 * @property string $name
 * @property string $description
 * @property string $event_type
 * @property string $object_type
 * @property integer $object_id
 * @property string $event_start
 * @property string $event_end
 * @property string $recurrence
 * @property integer $recurrence_id
 * @property string $registration_special
 * @property integer $max_players
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property League $league
 * @property Facility $facility
 * @property _Event $recurrence0
 * @property _Event[] $events
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
            [['league_id', 'facility_id', 'object_id', 'recurrence_id', 'max_players'], 'integer'],
            [['name', 'event_start'], 'required'],
            [['object_type', 'recurrence'], 'string'],
            [['event_start', 'event_end', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 80],
            [['description', 'registration_special'], 'string', 'max' => 160],
            [['event_type', 'status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'league_id' => Yii::t('golf', 'League ID'),
            'facility_id' => Yii::t('golf', 'Facility ID'),
            'name' => Yii::t('golf', 'Name'),
            'description' => Yii::t('golf', 'Description'),
            'event_type' => Yii::t('golf', 'Event Type'),
            'object_type' => Yii::t('golf', 'Object Type'),
            'object_id' => Yii::t('golf', 'Object ID'),
            'event_start' => Yii::t('golf', 'Event Start'),
            'event_end' => Yii::t('golf', 'Event End'),
            'recurrence' => Yii::t('golf', 'Recurrence'),
            'recurrence_id' => Yii::t('golf', 'Recurrence ID'),
            'registration_special' => Yii::t('golf', 'Registration Special'),
            'max_players' => Yii::t('golf', 'Max Players'),
            'status' => Yii::t('golf', 'Status'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeague()
    {
        return $this->hasOne(League::className(), ['id' => 'league_id']);
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
    public function getRecurrence0()
    {
        return $this->hasOne(_Event::className(), ['id' => 'recurrence_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(_Event::className(), ['recurrence_id' => 'id']);
    }
}
