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
 * @property integer $scorecard_id
 *
 * @property Scorecard $scorecard
 * @property Golfer $golfer
 * @property Course $course
 * @property Tees $tees
 * @property PracticeFlight[] $practiceFlights
 * @property PracticeGolfer[] $practiceGolfers
 * @property RegistrationGroup[] $registrationGroups
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
            [['golfer_id', 'course_id', 'start_hole', 'holes', 'tees_id', 'scorecard_id'], 'integer'],
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
            'id' => Yii::t('golf', 'ID'),
            'golfer_id' => Yii::t('golf', 'Golfer ID'),
            'course_id' => Yii::t('golf', 'Course ID'),
            'start_time' => Yii::t('golf', 'Start Time'),
            'start_hole' => Yii::t('golf', 'Start Hole'),
            'holes' => Yii::t('golf', 'Holes'),
            'tees_id' => Yii::t('golf', 'Tees ID'),
            'handicap' => Yii::t('golf', 'Handicap'),
            'status' => Yii::t('golf', 'Status'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'created_at' => Yii::t('golf', 'Created At'),
            'note' => Yii::t('golf', 'Note'),
            'scorecard_id' => Yii::t('golf', 'Scorecard ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScorecard()
    {
        return $this->hasOne(Scorecard::className(), ['id' => 'scorecard_id']);
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
    public function getTees()
    {
        return $this->hasOne(Tees::className(), ['id' => 'tees_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPracticeFlights()
    {
        return $this->hasMany(PracticeFlight::className(), ['practice_id' => 'id']);
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
    public function getRegistrationGroups()
    {
        return $this->hasMany(RegistrationGroup::className(), ['practice_id' => 'id']);
    }
}
