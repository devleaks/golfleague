<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "scorecard".
 *
 * @property integer $id
 * @property string $scorecard_type
 * @property integer $registration_id
 * @property integer $practice_id
 * @property integer $thru
 * @property integer $handicap
 * @property integer $score
 * @property integer $score_net
 * @property integer $stableford
 * @property integer $stableford_net
 * @property integer $topar
 * @property integer $topar_net
 * @property integer $points
 * @property string $teeshot
 * @property string $regulation
 * @property integer $putts
 * @property integer $penalty
 * @property string $sand
 * @property string $note
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property HandicapHistory[] $handicapHistories
 * @property Score[] $scores
 * @property Hole[] $holes
 * @property Practice $practice
 * @property Registration $registration
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
            [['scorecard_type'], 'required'],
            [['registration_id', 'practice_id', 'thru', 'handicap', 'score', 'score_net', 'stableford', 'stableford_net', 'topar', 'topar_net', 'points', 'putts', 'penalty'], 'integer'],
            [['teeshot', 'regulation', 'sand'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['scorecard_type', 'status'], 'string', 'max' => 20],
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
            'scorecard_type' => Yii::t('igolf', 'Scorecard Type'),
            'registration_id' => Yii::t('igolf', 'Registration ID'),
            'practice_id' => Yii::t('igolf', 'Practice ID'),
            'thru' => Yii::t('igolf', 'Thru'),
            'handicap' => Yii::t('igolf', 'Handicap'),
            'score' => Yii::t('igolf', 'Score'),
            'score_net' => Yii::t('igolf', 'Score Net'),
            'stableford' => Yii::t('igolf', 'Stableford'),
            'stableford_net' => Yii::t('igolf', 'Stableford Net'),
            'topar' => Yii::t('igolf', 'Topar'),
            'topar_net' => Yii::t('igolf', 'Topar Net'),
            'points' => Yii::t('igolf', 'Points'),
            'teeshot' => Yii::t('igolf', 'Teeshot'),
            'regulation' => Yii::t('igolf', 'Regulation'),
            'putts' => Yii::t('igolf', 'Putts'),
            'penalty' => Yii::t('igolf', 'Penalty'),
            'sand' => Yii::t('igolf', 'Sand'),
            'note' => Yii::t('igolf', 'Note'),
            'status' => Yii::t('igolf', 'Status'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHandicapHistories()
    {
        return $this->hasMany(HandicapHistory::className(), ['scorecard_id' => 'id']);
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
    public function getPractice()
    {
        return $this->hasOne(Practice::className(), ['id' => 'practice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistration()
    {
        return $this->hasOne(Registration::className(), ['id' => 'registration_id']);
    }
}
