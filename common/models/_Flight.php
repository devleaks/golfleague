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
            [['note'], 'string', 'max' => 160]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'ID'),
            'competition_id' => Yii::t('igolf', 'Competition ID'),
            'position' => Yii::t('igolf', 'Position'),
            'note' => Yii::t('igolf', 'Note'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'start_time' => Yii::t('igolf', 'Start Time'),
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
