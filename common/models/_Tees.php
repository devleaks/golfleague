<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tees".
 *
 * @property integer $id
 * @property integer $course_id
 * @property string $name
 * @property string $color
 * @property string $created_at
 * @property string $updated_at
 * @property string $slope
 * @property string $rating
 * @property integer $holes
 * @property string $front_back
 *
 * @property Hole[] $holes
 * @property Registration[] $registrations
 * @property Scorecard[] $scorecards
 * @property Start[] $starts
 * @property Course $course
 */
class _Tees extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tees';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'name', 'color'], 'required'],
            [['course_id', 'holes'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['slope', 'rating'], 'number'],
            [['name', 'color', 'front_back'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'ID'),
            'course_id' => Yii::t('igolf', 'Course ID'),
            'name' => Yii::t('igolf', 'Name'),
            'color' => Yii::t('igolf', 'Color'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'slope' => Yii::t('igolf', 'Slope'),
            'rating' => Yii::t('igolf', 'Rating'),
            'holes' => Yii::t('igolf', 'Holes'),
            'front_back' => Yii::t('igolf', 'Front Back'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHoles()
    {
        return $this->hasMany(Hole::className(), ['tees_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrations()
    {
        return $this->hasMany(Registration::className(), ['tees_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScorecards()
    {
        return $this->hasMany(Scorecard::className(), ['tees_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStarts()
    {
        return $this->hasMany(Start::className(), ['tees_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }
}
