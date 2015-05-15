<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "registration".
 *
 * @property integer $id
 * @property integer $competition_id
 * @property integer $golfer_id
 * @property integer $tees_id
 * @property integer $team_id
 * @property integer $flight_id
 * @property string $note
 * @property integer $handicap
 * @property integer $score
 * @property integer $score_net
 * @property integer $stableford
 * @property integer $stableford_net
 * @property integer $points
 * @property integer $position
 * @property string $card_status
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $thru
 *
 * @property Tees $tees
 * @property Competition $competition
 * @property Golfer $golfer
 * @property Team $team
 * @property Flight $flight
 */
class _Registration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'registration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['competition_id', 'golfer_id', 'status'], 'required'],
            [['competition_id', 'golfer_id', 'tees_id', 'team_id', 'flight_id', 'handicap', 'score', 'score_net', 'stableford', 'stableford_net', 'points', 'position', 'thru'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['note'], 'string', 'max' => 160],
            [['card_status', 'status'], 'string', 'max' => 20]
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
            'team_id' => Yii::t('igolf', 'Team ID'),
            'flight_id' => Yii::t('igolf', 'Flight ID'),
            'note' => Yii::t('igolf', 'Note'),
            'handicap' => Yii::t('igolf', 'Handicap'),
            'score' => Yii::t('igolf', 'Score'),
            'score_net' => Yii::t('igolf', 'Score Net'),
            'stableford' => Yii::t('igolf', 'Stableford'),
            'stableford_net' => Yii::t('igolf', 'Stableford Net'),
            'points' => Yii::t('igolf', 'Points'),
            'position' => Yii::t('igolf', 'Position'),
            'card_status' => Yii::t('igolf', 'Card Status'),
            'status' => Yii::t('igolf', 'Status'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'thru' => Yii::t('igolf', 'Thru'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFlight()
    {
        return $this->hasOne(Flight::className(), ['id' => 'flight_id']);
    }
}
