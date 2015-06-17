<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "flight".
 *
 * @property integer $id
 * @property string $start_time
 * @property integer $start_hole
 * @property string $handicap
 * @property integer $position
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 *
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
            [['start_time', 'start_hole'], 'required'],
            [['start_time', 'created_at', 'updated_at'], 'safe'],
            [['start_hole', 'position'], 'integer'],
            [['handicap'], 'number'],
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
            'start_time' => Yii::t('igolf', 'Start Time'),
            'start_hole' => Yii::t('igolf', 'Start Hole'),
            'handicap' => Yii::t('igolf', 'Handicap'),
            'position' => Yii::t('igolf', 'Position'),
            'note' => Yii::t('igolf', 'Note'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrations()
    {
        return $this->hasMany(Registration::className(), ['flight_id' => 'id']);
    }
}
