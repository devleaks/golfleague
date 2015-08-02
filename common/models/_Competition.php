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
 * @property string $status
 * @property integer $parent_id
 * @property string $start_date
 * @property integer $recurrence_id
 * @property integer $course_id
 * @property integer $holes
 * @property integer $start_hole
 * @property integer $rule_id
 * @property integer $final_rule_id
 * @property string $registration_begin
 * @property string $registration_end
 * @property string $handicap_min
 * @property string $handicap_max
 * @property integer $age_min
 * @property integer $age_max
 * @property string $gender
 * @property string $player_type
 * @property integer $max_players
 * @property string $registration_special
 * @property integer $cba
 * @property integer $tour
 * @property integer $flight_size
 * @property integer $flight_time
 * @property integer $flight_window
 * @property integer $registration_time
 * @property string $created_at
 * @property string $updated_at
 * @property string $recurrence
 *
 * @property _Competition $recurrence0
 * @property _Competition[] $competitions
 * @property _Competition $parent
 * @property _Competition[] $competitions0
 * @property Course $course
 * @property Rule $rule
 * @property Rule $finalRule
 * @property Match[] $matches
 * @property Registration[] $registrations
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
            [['competition_type', 'name', 'rule_id', 'registration_begin', 'registration_end'], 'required'],
            [['parent_id', 'recurrence_id', 'course_id', 'holes', 'start_hole', 'rule_id', 'final_rule_id', 'age_min', 'age_max', 'max_players', 'cba', 'tour', 'flight_size', 'flight_time', 'flight_window', 'registration_time'], 'integer'],
            [['start_date', 'registration_begin', 'registration_end', 'created_at', 'updated_at'], 'safe'],
            [['handicap_min', 'handicap_max'], 'number'],
            [['competition_type', 'status', 'gender', 'player_type'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 80],
            [['description', 'recurrence'], 'string', 'max' => 255],
            [['registration_special'], 'string', 'max' => 160]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'competition_type' => Yii::t('golf', 'Competition Type'),
            'name' => Yii::t('golf', 'Name'),
            'description' => Yii::t('golf', 'Description'),
            'status' => Yii::t('golf', 'Status'),
            'parent_id' => Yii::t('golf', 'Parent ID'),
            'start_date' => Yii::t('golf', 'Start Date'),
            'recurrence_id' => Yii::t('golf', 'Recurrence ID'),
            'course_id' => Yii::t('golf', 'Course ID'),
            'holes' => Yii::t('golf', 'Holes'),
            'start_hole' => Yii::t('golf', 'Start Hole'),
            'rule_id' => Yii::t('golf', 'Rule ID'),
            'final_rule_id' => Yii::t('golf', 'Final Rule ID'),
            'registration_begin' => Yii::t('golf', 'Registration Begin'),
            'registration_end' => Yii::t('golf', 'Registration End'),
            'handicap_min' => Yii::t('golf', 'Handicap Min'),
            'handicap_max' => Yii::t('golf', 'Handicap Max'),
            'age_min' => Yii::t('golf', 'Age Min'),
            'age_max' => Yii::t('golf', 'Age Max'),
            'gender' => Yii::t('golf', 'Gender'),
            'player_type' => Yii::t('golf', 'Player Type'),
            'max_players' => Yii::t('golf', 'Max Players'),
            'registration_special' => Yii::t('golf', 'Registration Special'),
            'cba' => Yii::t('golf', 'Cba'),
            'tour' => Yii::t('golf', 'Tour'),
            'flight_size' => Yii::t('golf', 'Flight Size'),
            'flight_time' => Yii::t('golf', 'Flight Time'),
            'flight_window' => Yii::t('golf', 'Flight Window'),
            'registration_time' => Yii::t('golf', 'Registration Time'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'recurrence' => Yii::t('golf', 'Recurrence'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecurrence0()
    {
        return $this->hasOne(_Competition::className(), ['id' => 'recurrence_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetitions()
    {
        return $this->hasMany(_Competition::className(), ['recurrence_id' => 'id']);
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
    public function getCompetitions0()
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
    public function getFinalRule()
    {
        return $this->hasOne(Rule::className(), ['id' => 'final_rule_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatches()
    {
        return $this->hasMany(Match::className(), ['competition_id' => 'id']);
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
    public function getStarts()
    {
        return $this->hasMany(Start::className(), ['competition_id' => 'id']);
    }
}
