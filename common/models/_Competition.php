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
 * @property string $registration_begin
 * @property string $registration_end
 * @property double $handicap_min
 * @property double $handicap_max
 * @property integer $age_min
 * @property integer $age_max
 * @property string $gender
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $flight_size
 * @property integer $delta_time
 * @property integer $rule_id
 * @property integer $recurrence_id
 * @property integer $max_players
 * @property string $registration_special
 *
 * @property _Competition $parent
 * @property _Competition[] $competitions
 * @property Course $course
 * @property Rule $rule
 * @property Reccurence $recurrence
 * @property Flight[] $flights
 * @property Registration[] $registrations
 * @property Scorecard[] $scorecards
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
            [['parent_id', 'course_id', 'holes', 'age_min', 'age_max', 'flight_size', 'delta_time', 'rule_id', 'recurrence_id', 'max_players'], 'integer'],
            [['start_date', 'registration_begin', 'registration_end', 'created_at', 'updated_at'], 'safe'],
            [['handicap_min', 'handicap_max'], 'number'],
            [['competition_type', 'gender', 'status', 'registration_special'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 255],
            [['name'], 'unique']
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
            'registration_begin' => Yii::t('igolf', 'Registration Begin'),
            'registration_end' => Yii::t('igolf', 'Registration End'),
            'handicap_min' => Yii::t('igolf', 'Handicap Min'),
            'handicap_max' => Yii::t('igolf', 'Handicap Max'),
            'age_min' => Yii::t('igolf', 'Age Min'),
            'age_max' => Yii::t('igolf', 'Age Max'),
            'gender' => Yii::t('igolf', 'Gender'),
            'status' => Yii::t('igolf', 'Status'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'flight_size' => Yii::t('igolf', 'Flight Size'),
            'delta_time' => Yii::t('igolf', 'Delta Time'),
            'rule_id' => Yii::t('igolf', 'Rule ID'),
            'recurrence_id' => Yii::t('igolf', 'Recurrence ID'),
            'max_players' => Yii::t('igolf', 'Max Players'),
            'registration_special' => Yii::t('igolf', 'Registration Special'),
        ];
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
    public function getRecurrence()
    {
        return $this->hasOne(Reccurence::className(), ['id' => 'recurrence_id']);
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
    * @inheritdoc 
    * 
    * Note: Overrides default to create properly typed row. 
    */ 
   public static function instantiate($row) 
   { 
       switch ($row['competition_type']) { 
           case Competition::TYPE_SEASON: 
               return new Season(); 
           case Competition::TYPE_TOURNAMENT: 
               return new Tournament(); 
           case Competition::TYPE_MATCH: 
               return new Match(); 
           default: 
              return new self; 
       } 
   }

}
