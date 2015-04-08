<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "scorecard".
 *
 * @property integer $id
 * @property integer $competition_id
 * @property integer $golfer_id
 * @property integer $tees_id
 * @property string $note
 * @property integer $points
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Score[] $scores
 * @property Hole[] $holes
 * @property Tees $tees
 * @property Competition $competition
 * @property Golfer $golfer
 */
class _Scorecard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scorecard';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['competition_id', 'golfer_id', 'tees_id', 'points'], 'integer'],
            [['golfer_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
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
            'golfer_id' => Yii::t('igolf', 'Golfer ID'),
            'tees_id' => Yii::t('igolf', 'Tees ID'),
            'note' => Yii::t('igolf', 'Note'),
            'points' => Yii::t('igolf', 'Points'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScores()
    {
        return $this->hasMany(Score::className(), ['scorecard_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHoles()
    {
        return $this->hasMany(Hole::className(), ['id' => 'hole_id'])->viaTable('score', ['scorecard_id' => 'id']);
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
    public function getCompetition()
    {
        return $this->hasOne(Competition::className(), ['id' => 'competition_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolfer()
    {
        return $this->hasOne(Golfer::className(), ['id' => 'golfer_id']);
    }
}
