<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hole".
 *
 * @property integer $id
 * @property integer $tees_id
 * @property integer $position
 * @property integer $par
 * @property integer $si
 * @property integer $length
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Tees $tees
 * @property Score[] $scores
 * @property Scorecard[] $scorecards
 */
class _Hole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hole';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tees_id'], 'required'],
            [['tees_id', 'position', 'par', 'si', 'length'], 'integer'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'ID'),
            'tees_id' => Yii::t('igolf', 'Tees'),
            'position' => Yii::t('igolf', 'Position'),
            'par' => Yii::t('igolf', 'Par'),
            'si' => Yii::t('igolf', 'Si'),
            'length' => Yii::t('igolf', 'Length'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTees()
    {
        return $this->hasOne(Tees::className(), ['id' => 'tees_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScores()
    {
        return $this->hasMany(Score::className(), ['hole_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScorecards()
    {
        return $this->hasMany(Scorecard::className(), ['id' => 'scorecard_id'])->viaTable('score', ['hole_id' => 'id']);
    }
}
