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
 * @property string $recurrence
 * @property string $registration_special
 * @property integer $max_players
 * @property integer $recurrence_id
 * @property integer $league_id
 * @property integer $facility_id
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
            [['name', 'event_start'], 'required'],
            [['event_start', 'event_end', 'created_at', 'updated_at'], 'safe'],
            [['object_type', 'recurrence'], 'string'],
            [['object_id', 'max_players', 'recurrence_id', 'league_id', 'facility_id'], 'integer'],
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
            'name' => Yii::t('golf', 'Name'),
            'description' => Yii::t('golf', 'Description'),
            'event_type' => Yii::t('golf', 'Event Type'),
            'event_start' => Yii::t('golf', 'Event Start'),
            'event_end' => Yii::t('golf', 'Event End'),
            'object_type' => Yii::t('golf', 'Object Type'),
            'object_id' => Yii::t('golf', 'Object ID'),
            'status' => Yii::t('golf', 'Status'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'recurrence' => Yii::t('golf', 'Recurrence'),
            'registration_special' => Yii::t('golf', 'Registration Special'),
            'max_players' => Yii::t('golf', 'Max Players'),
            'recurrence_id' => Yii::t('golf', 'Recurrence ID'),
            'league_id' => Yii::t('golf', 'League ID'),
            'facility_id' => Yii::t('golf', 'Facility ID'),
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
