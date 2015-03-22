<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "flight".
 *
 * @property integer $id
 * @property integer $competition_id
 * @property integer $position
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 * @property string $start_time
 *
 * @property Competition $competition
 * @property Registration[] $registrations
 */
class _Flight extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'flight';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['competition_id', 'position'], 'integer'],
            [['position'], 'required'],
            [['created_at', 'updated_at', 'start_time'], 'safe'],
            [['note'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golfleague', 'ID'),
            'competition_id' => Yii::t('golfleague', 'Competition ID'),
            'position' => Yii::t('golfleague', 'Position'),
            'note' => Yii::t('golfleague', 'Note'),
            'created_at' => Yii::t('golfleague', 'Created At'),
            'updated_at' => Yii::t('golfleague', 'Updated At'),
            'start_time' => Yii::t('golfleague', 'Start Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetition()
    {
        return $this->hasOne(Competition::className(), ['id' => 'competition_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrations()
    {
        return $this->hasMany(Registration::className(), ['flight_id' => 'id']);
    }
}
