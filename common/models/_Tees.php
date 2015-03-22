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
 *
 * @property Hole[] $holes
 * @property Registration[] $registrations
 * @property Scorecard[] $scorecards
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
            [['course_id'], 'required'],
            [['course_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'color'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'ID'),
            'course_id' => Yii::t('igolf', 'Course'),
            'name' => Yii::t('igolf', 'Name'),
            'color' => Yii::t('igolf', 'Color'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
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
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }
}
