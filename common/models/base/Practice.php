<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "practice".
 *
 * @property integer $id
 * @property integer $golfer_id
 * @property integer $course_id
 * @property integer $tees_id
 * @property integer $scorecard_id
 * @property string $start_time
 * @property integer $start_hole
 * @property integer $holes
 * @property string $handicap
 * @property string $note
 * @property string $status
 * @property string $updated_at
 * @property string $created_at
 *
 * @property Scorecard $scorecard
 * @property Golfer $golfer
 * @property Course $course
 * @property Tees $tees
 */
class Practice extends \yii\db\ActiveRecord
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
            [['golfer_id', 'course_id', 'tees_id', 'scorecard_id', 'start_hole', 'holes'], 'integer'],
            [['start_time', 'updated_at', 'created_at'], 'safe'],
            [['handicap'], 'number'],
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
            'golfer_id' => Yii::t('golf', 'Golfer ID'),
            'course_id' => Yii::t('golf', 'Course ID'),
            'tees_id' => Yii::t('golf', 'Tees ID'),
            'scorecard_id' => Yii::t('golf', 'Scorecard ID'),
            'start_time' => Yii::t('golf', 'Start Time'),
            'start_hole' => Yii::t('golf', 'Start Hole'),
            'holes' => Yii::t('golf', 'Holes'),
            'handicap' => Yii::t('golf', 'Handicap'),
            'note' => Yii::t('golf', 'Note'),
            'status' => Yii::t('golf', 'Status'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'created_at' => Yii::t('golf', 'Created At'),
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
    public function getGolfer()
    {
        return $this->hasOne(\common\models\Golfer::className(), ['id' => 'golfer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(\common\models\Course::className(), ['id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTees()
    {
        return $this->hasOne(\common\models\Tees::className(), ['id' => 'tees_id']);
    }
}
