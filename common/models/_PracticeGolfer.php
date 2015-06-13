<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "practice_golfer".
 *
 * @property integer $id
 * @property integer $golfer_id
 * @property integer $practice_id
 * @property string $status
 *
 * @property Practice $practice
 * @property Golfer $golfer
 */
class _PracticeGolfer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'practice_golfer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['golfer_id', 'practice_id'], 'required'],
            [['golfer_id', 'practice_id'], 'integer'],
            [['status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'ID'),
            'golfer_id' => Yii::t('igolf', 'Golfer ID'),
            'practice_id' => Yii::t('igolf', 'Practice ID'),
            'status' => Yii::t('igolf', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPractice()
    {
        return $this->hasOne(Practice::className(), ['id' => 'practice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolfer()
    {
        return $this->hasOne(Golfer::className(), ['id' => 'golfer_id']);
    }
}
