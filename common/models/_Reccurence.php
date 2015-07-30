<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reccurence".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $recurrence_start
 * @property string $recurrence_end
 * @property integer $offset
 * @property integer $recurrence
 * @property string $recurrence_type
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Competition[] $competitions
 * @property Event[] $events
 */
class _Reccurence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reccurence';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'recurrence_start', 'recurrence_type'], 'required'],
            [['recurrence_start', 'recurrence_end', 'created_at', 'updated_at'], 'safe'],
            [['offset', 'recurrence'], 'integer'],
            [['name'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 160],
            [['recurrence_type', 'status'], 'string', 'max' => 20]
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
            'recurrence_start' => Yii::t('golf', 'Recurrence Start'),
            'recurrence_end' => Yii::t('golf', 'Recurrence End'),
            'offset' => Yii::t('golf', 'Offset'),
            'recurrence' => Yii::t('golf', 'Recurrence'),
            'recurrence_type' => Yii::t('golf', 'Recurrence Type'),
            'status' => Yii::t('golf', 'Status'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetitions()
    {
        return $this->hasMany(Competition::className(), ['recurrence_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['recurrence_id' => 'id']);
    }
}
