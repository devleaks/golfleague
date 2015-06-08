<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "score".
 *
 * @property integer $scorecard_id
 * @property integer $hole_id
 * @property integer $score
 * @property integer $putts
 * @property integer $penalty
 * @property integer $sand
 * @property string $note
 * @property string $drive
 * @property string $regulation
 * @property string $approach
 * @property string $putt
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $drive_length
 * @property string $approach_length
 * @property string $putt_length
 * @property string $putt2
 * @property string $putt2_length
 *
 * @property Hole $hole
 * @property Scorecard $scorecard
 */
class _Score extends \yii\db\ActiveRecord
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
            [['hole_id', 'score', 'putts', 'penalty', 'sand'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['note'], 'string', 'max' => 160],
            [['drive', 'regulation', 'approach', 'putt', 'status', 'drive_length', 'approach_length', 'putt_length', 'putt2', 'putt2_length'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'scorecard_id' => Yii::t('igolf', 'Scorecard ID'),
            'hole_id' => Yii::t('igolf', 'Hole ID'),
            'score' => Yii::t('igolf', 'Score'),
            'putts' => Yii::t('igolf', 'Putts'),
            'penalty' => Yii::t('igolf', 'Penalty'),
            'sand' => Yii::t('igolf', 'Sand'),
            'note' => Yii::t('igolf', 'Note'),
            'drive' => Yii::t('igolf', 'Drive'),
            'regulation' => Yii::t('igolf', 'Regulation'),
            'approach' => Yii::t('igolf', 'Approach'),
            'putt' => Yii::t('igolf', 'Putt'),
            'status' => Yii::t('igolf', 'Status'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'drive_length' => Yii::t('igolf', 'Drive Length'),
            'approach_length' => Yii::t('igolf', 'Approach Length'),
            'putt_length' => Yii::t('igolf', 'Putt Length'),
            'putt2' => Yii::t('igolf', 'Putt2'),
            'putt2_length' => Yii::t('igolf', 'Putt2 Length'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHole()
    {
        return $this->hasOne(Hole::className(), ['id' => 'hole_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScorecard()
    {
        return $this->hasOne(Scorecard::className(), ['id' => 'scorecard_id']);
    }
}
