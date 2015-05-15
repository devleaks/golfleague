<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "competition".
 *
 * @property integer $id
 * @property string $competition_type
 * @property string $name
 * @property string $description
 * @property integer $parent_id
 * @property integer $course_id
 * @property integer $holes
 * @property string $start_date
 * @property string $status
 * @property integer $flight_size
 * @property string $registration_begin
 * @property string $registration_end
 * @property string $handicap_min
 * @property string $handicap_max
 * @property integer $age_min
 * @property integer $age_max
 * @property string $gender
 * @property string $created_at
 * @property string $updated_at
 * @property integer $rule_id
 * @property integer $recurrence_id
 * @property integer $max_players
 * @property string $registration_special
 * @property integer $rule_final_id
 * @property integer $flight_time
 * @property integer $registration_time
 *
 * @property Reccurence $recurrence
 * @property _Competition $parent
 * @property _Competition[] $competitions
 * @property Course $course
 * @property Rule $rule
 * @property Rule $ruleFinal
 * @property Flight[] $flights
 * @property Registration[] $registrations
 * @property Scorecard[] $scorecards
 * @property Start[] $starts
 */
class _Competition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'competition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['competition_type', 'name', 'registration_begin', 'registration_end'], 'required'],
            [['parent_id', 'course_id', 'holes', 'flight_size', 'age_min', 'age_max', 'rule_id', 'recurrence_id', 'max_players', 'rule_final_id', 'flight_time', 'registration_time'], 'integer'],
            [['start_date', 'registration_begin', 'registration_end', 'created_at', 'updated_at'], 'safe'],
            [['handicap_min', 'handicap_max'], 'number'],
            [['competition_type', 'status', 'gender'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 255],
            [['registration_special'], 'string', 'max' => 160]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'ID'),
            'competition_type' => Yii::t('igolf', 'Competition Type'),
            'name' => Yii::t('igolf', 'Name'),
            'description' => Yii::t('igolf', 'Description'),
            'parent_id' => Yii::t('igolf', 'Parent ID'),
            'course_id' => Yii::t('igolf', 'Course ID'),
            'holes' => Yii::t('igolf', 'Holes'),
            'start_date' => Yii::t('igolf', 'Start Date'),
            'status' => Yii::t('igolf', 'Status'),
            'flight_size' => Yii::t('igolf', 'Flight Size'),
            'registration_begin' => Yii::t('igolf', 'Registration Begin'),
            'registration_end' => Yii::t('igolf', 'Registration End'),
            'handicap_min' => Yii::t('igolf', 'Handicap Min'),
            'handicap_max' => Yii::t('igolf', 'Handicap Max'),
            'age_min' => Yii::t('igolf', 'Age Min'),
            'age_max' => Yii::t('igolf', 'Age Max'),
            'gender' => Yii::t('igolf', 'Gender'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'rule_id' => Yii::t('igolf', 'Rule ID'),
            'recurrence_id' => Yii::t('igolf', 'Recurrence ID'),
            'max_players' => Yii::t('igolf', 'Max Players'),
            'registration_special' => Yii::t('igolf', 'Registration Special'),
            'rule_final_id' => Yii::t('igolf', 'Rule Final ID'),
            'flight_time' => Yii::t('igolf', 'Flight Time'),
            'registration_time' => Yii::t('igolf', 'Registration Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecurrence()
    {
        return $this->hasOne(Reccurence::className(), ['id' => 'recurrence_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(_Competition::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetitions()
    {
        return $this->hasMany(_Competition::className(), ['parent_id' => 'id']);
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
    public function getRule()
    {
        return $this->hasOne(Rule::className(), ['id' => 'rule_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleFinal()
    {
        return $this->hasOne(Rule::className(), ['id' => 'rule_final_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFlights()
    {
        return $this->hasMany(Flight::className(), ['competition_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrations()
    {
        return $this->hasMany(Registration::className(), ['competition_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScorecards()
    {
        return $this->hasMany(Scorecard::className(), ['competition_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStarts()
    {
        return $this->hasMany(Start::className(), ['competition_id' => 'id']);
    }
}
