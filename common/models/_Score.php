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
 * @property string $created_at
 * @property string $updated_at
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
            [['drive', 'regulation', 'approach', 'putt'], 'string', 'max' => 20]
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
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
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
