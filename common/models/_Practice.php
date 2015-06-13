<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "practice".
 *
 * @property integer $id
 * @property integer $golfer_id
 * @property integer $course_id
 * @property string $start_time
 * @property integer $start_hole
 * @property integer $holes
 * @property integer $tees_id
 * @property string $handicap
 * @property string $status
 * @property string $updated_at
 * @property string $created_at
 * @property string $note
 *
 * @property Tees $tees
 * @property Golfer $golfer
 * @property Course $course
 * @property PracticeGolfer[] $practiceGolfers
 * @property Scorecard[] $scorecards
 */
class _Practice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'practice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['golfer_id', 'course_id', 'tees_id'], 'required'],
            [['golfer_id', 'course_id', 'start_hole', 'holes', 'tees_id'], 'integer'],
            [['start_time', 'updated_at', 'created_at'], 'safe'],
            [['handicap'], 'number'],
            [['status'], 'string', 'max' => 20],
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
            'golfer_id' => Yii::t('igolf', 'Golfer ID'),
            'course_id' => Yii::t('igolf', 'Course ID'),
            'start_time' => Yii::t('igolf', 'Start Time'),
            'start_hole' => Yii::t('igolf', 'Start Hole'),
            'holes' => Yii::t('igolf', 'Holes'),
            'tees_id' => Yii::t('igolf', 'Tees ID'),
            'handicap' => Yii::t('igolf', 'Handicap'),
            'status' => Yii::t('igolf', 'Status'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'note' => Yii::t('igolf', 'Note'),
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
    public function getGolfer()
    {
        return $this->hasOne(Golfer::className(), ['id' => 'golfer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPracticeGolfers()
    {
        return $this->hasMany(PracticeGolfer::className(), ['practice_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScorecards()
    {
        return $this->hasMany(Scorecard::className(), ['practice_id' => 'id']);
    }
}
