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
            'id' => Yii::t('golf', 'ID'),
            'start_time' => Yii::t('golf', 'Start Time'),
            'start_hole' => Yii::t('golf', 'Start Hole'),
            'handicap' => Yii::t('golf', 'Handicap'),
            'position' => Yii::t('golf', 'Position'),
            'note' => Yii::t('golf', 'Note'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
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
