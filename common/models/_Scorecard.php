<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "scorecard".
 *
 * @property integer $id
 * @property integer $registration_id
 * @property integer $golfer_id
 * @property integer $tees_id
 * @property string $note
 * @property integer $score
 * @property integer $points
 * @property integer $putts
 * @property string $teeshot
 * @property string $regulation
 * @property integer $penalty
 * @property integer $sand
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $score_net
 * @property integer $stableford
 * @property integer $stableford_net
 * @property integer $handicap
 * @property integer $thru
 * @property integer $to_par
 *
 * @property Score[] $scores
 * @property Hole[] $holes
 * @property Tees $tees
 * @property Registration $registration
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
            [['registration_id', 'golfer_id', 'tees_id', 'score', 'points', 'putts', 'penalty', 'sand', 'score_net', 'stableford', 'stableford_net', 'handicap', 'thru', 'to_par'], 'integer'],
            [['golfer_id', 'tees_id'], 'required'],
            [['teeshot', 'regulation'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['note'], 'string', 'max' => 160],
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
            'registration_id' => Yii::t('igolf', 'Registration ID'),
            'golfer_id' => Yii::t('igolf', 'Golfer ID'),
            'tees_id' => Yii::t('igolf', 'Tees ID'),
            'note' => Yii::t('igolf', 'Note'),
            'score' => Yii::t('igolf', 'Score'),
            'points' => Yii::t('igolf', 'Points'),
            'putts' => Yii::t('igolf', 'Putts'),
            'teeshot' => Yii::t('igolf', 'Teeshot'),
            'regulation' => Yii::t('igolf', 'Regulation'),
            'penalty' => Yii::t('igolf', 'Penalty'),
            'sand' => Yii::t('igolf', 'Sand'),
            'status' => Yii::t('igolf', 'Status'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'score_net' => Yii::t('igolf', 'Score Net'),
            'stableford' => Yii::t('igolf', 'Stableford'),
            'stableford_net' => Yii::t('igolf', 'Stableford Net'),
            'handicap' => Yii::t('igolf', 'Handicap'),
            'thru' => Yii::t('igolf', 'Thru'),
            'to_par' => Yii::t('igolf', 'To Par'),
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
    public function getRegistration()
    {
        return $this->hasOne(Registration::className(), ['id' => 'registration_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolfer()
    {
        return $this->hasOne(Golfer::className(), ['id' => 'golfer_id']);
    }
}
