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
 * @property string $points
 * @property integer $position
 * @property string $teeshot
 * @property string $regulation
 * @property integer $putts
 * @property integer $penalty
 * @property string $sand
 * @property string $note
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $rounds
 * @property string $tie_break
 * @property integer $allowed
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
            [['registration_id', 'practice_id', 'thru', 'handicap', 'score', 'score_net', 'stableford', 'stableford_net', 'topar', 'topar_net', 'position', 'putts', 'penalty', 'rounds', 'allowed'], 'integer'],
            [['points', 'teeshot', 'regulation', 'sand', 'tie_break'], 'number'],
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
            'id' => Yii::t('golf', 'ID'),
            'scorecard_type' => Yii::t('golf', 'Scorecard Type'),
            'registration_id' => Yii::t('golf', 'Registration ID'),
            'practice_id' => Yii::t('golf', 'Practice ID'),
            'thru' => Yii::t('golf', 'Thru'),
            'handicap' => Yii::t('golf', 'Handicap'),
            'score' => Yii::t('golf', 'Score'),
            'score_net' => Yii::t('golf', 'Score Net'),
            'stableford' => Yii::t('golf', 'Stableford'),
            'stableford_net' => Yii::t('golf', 'Stableford Net'),
            'topar' => Yii::t('golf', 'Topar'),
            'topar_net' => Yii::t('golf', 'Topar Net'),
            'points' => Yii::t('golf', 'Points'),
            'position' => Yii::t('golf', 'Position'),
            'teeshot' => Yii::t('golf', 'Teeshot'),
            'regulation' => Yii::t('golf', 'Regulation'),
            'putts' => Yii::t('golf', 'Putts'),
            'penalty' => Yii::t('golf', 'Penalty'),
            'sand' => Yii::t('golf', 'Sand'),
            'note' => Yii::t('golf', 'Note'),
            'status' => Yii::t('golf', 'Status'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'rounds' => Yii::t('golf', 'Rounds'),
            'tie_break' => Yii::t('golf', 'Tie Break'),
            'allowed' => Yii::t('golf', 'Allowed'),
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
