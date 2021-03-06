<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "score".
 *
 * @property integer $scorecard_id
 * @property integer $hole_id
 * @property integer $allowed
 * @property integer $score
 * @property integer $score_net
 * @property integer $stableford
 * @property integer $stableford_net
 * @property integer $topar
 * @property integer $topar_net
 * @property string $points
 * @property integer $putts
 * @property integer $penalty
 * @property integer $sand
 * @property string $note
 * @property string $regulation
 * @property string $approach
 * @property string $putt
 * @property string $approach_length
 * @property string $putt_length
 * @property string $putt2
 * @property string $putt2_length
 * @property string $teeshot
 * @property string $teeshot_length
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $tie_break
 *
 * @property Scorecard $scorecard
 * @property Hole $hole
 */
class Score extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'score';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hole_id'], 'required'],
            [['hole_id', 'allowed', 'score', 'score_net', 'stableford', 'stableford_net', 'topar', 'topar_net', 'putts', 'penalty', 'sand'], 'integer'],
            [['points', 'tie_break'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['note'], 'string', 'max' => 160],
            [['regulation', 'approach', 'putt', 'approach_length', 'putt_length', 'putt2', 'putt2_length', 'teeshot', 'teeshot_length', 'status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'scorecard_id' => Yii::t('golf', 'Scorecard ID'),
            'hole_id' => Yii::t('golf', 'Hole ID'),
            'allowed' => Yii::t('golf', 'Allowed'),
            'score' => Yii::t('golf', 'Score'),
            'score_net' => Yii::t('golf', 'Score Net'),
            'stableford' => Yii::t('golf', 'Stableford'),
            'stableford_net' => Yii::t('golf', 'Stableford Net'),
            'topar' => Yii::t('golf', 'Topar'),
            'topar_net' => Yii::t('golf', 'Topar Net'),
            'points' => Yii::t('golf', 'Points'),
            'putts' => Yii::t('golf', 'Putts'),
            'penalty' => Yii::t('golf', 'Penalty'),
            'sand' => Yii::t('golf', 'Sand'),
            'note' => Yii::t('golf', 'Note'),
            'regulation' => Yii::t('golf', 'Regulation'),
            'approach' => Yii::t('golf', 'Approach'),
            'putt' => Yii::t('golf', 'Putt'),
            'approach_length' => Yii::t('golf', 'Approach Length'),
            'putt_length' => Yii::t('golf', 'Putt Length'),
            'putt2' => Yii::t('golf', 'Putt2'),
            'putt2_length' => Yii::t('golf', 'Putt2 Length'),
            'teeshot' => Yii::t('golf', 'Teeshot'),
            'teeshot_length' => Yii::t('golf', 'Teeshot Length'),
            'status' => Yii::t('golf', 'Status'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'tie_break' => Yii::t('golf', 'Tie Break'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScorecard()
    {
        return $this->hasOne(\common\models\Scorecard::className(), ['id' => 'scorecard_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHole()
    {
        return $this->hasOne(\common\models\Hole::className(), ['id' => 'hole_id']);
    }
}
