<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "scorecard".
 *
 * @property integer $id
 * @property integer $thru
 * @property integer $handicap
 * @property integer $rounds
 * @property integer $allowed
 * @property integer $score
 * @property integer $score_net
 * @property integer $stableford
 * @property integer $stableford_net
 * @property integer $topar
 * @property integer $topar_net
 * @property string $points
 * @property string $tie_break
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
 *
 * @property HandicapHistory[] $handicapHistories
 * @property Practice[] $practices
 * @property Registration[] $registrations
 * @property Score[] $scores
 * @property Hole[] $holes
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
            [['thru', 'handicap', 'rounds', 'allowed', 'score', 'score_net', 'stableford', 'stableford_net', 'topar', 'topar_net', 'position', 'putts', 'penalty'], 'integer'],
            [['points', 'tie_break', 'teeshot', 'regulation', 'sand'], 'number'],
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
            'id' => Yii::t('golf', 'ID'),
            'thru' => Yii::t('golf', 'Thru'),
            'handicap' => Yii::t('golf', 'Handicap'),
            'rounds' => Yii::t('golf', 'Rounds'),
            'allowed' => Yii::t('golf', 'Allowed'),
            'score' => Yii::t('golf', 'Score'),
            'score_net' => Yii::t('golf', 'Score Net'),
            'stableford' => Yii::t('golf', 'Stableford'),
            'stableford_net' => Yii::t('golf', 'Stableford Net'),
            'topar' => Yii::t('golf', 'Topar'),
            'topar_net' => Yii::t('golf', 'Topar Net'),
            'points' => Yii::t('golf', 'Points'),
            'tie_break' => Yii::t('golf', 'Tie Break'),
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
    public function getPractices()
    {
        return $this->hasMany(Practice::className(), ['scorecard_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrations()
    {
        return $this->hasMany(Registration::className(), ['scorecard_id' => 'id']);
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
        return $this->hasMany(Hole::className(), ['id' => 'hole_id'])->viaTable(Score::tableName(), ['scorecard_id' => 'id']);
    }
}
