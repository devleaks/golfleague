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
 * @property integer $rule_id
 * @property string $start_date
 * @property string $registration_begin
 * @property string $registration_end
 * @property double $handicap_min
 * @property double $handicap_max
 * @property integer $age_min
 * @property integer $age_max
 * @property string $gender
 * @property integer $maxplayers
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $flight_size
 * @property integer $delta_time
 * @property string $special
 *
 * @property Rule $rules
 * @property _Competition $parent
 * @property _Competition[] $competitions
 * @property Course $course
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
            [['parent_id', 'course_id', 'holes', 'rule_id', 'age_min', 'age_max', 'maxplayers', 'flight_size', 'delta_time'], 'integer'],
            [['start_date', 'registration_begin', 'registration_end', 'created_at', 'updated_at'], 'safe'],
            [['handicap_min', 'handicap_max'], 'number'],
            [['competition_type', 'gender', 'status', 'special'], 'string', 'max' => 20],
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
            'id' => Yii::t('golfleague', 'ID'),
            'competition_type' => Yii::t('golfleague', 'Competition Type'),
            'name' => Yii::t('golfleague', 'Name'),
            'description' => Yii::t('golfleague', 'Description'),
            'parent_id' => Yii::t('golfleague', 'Parent ID'),
            'course_id' => Yii::t('golfleague', 'Course ID'),
            'holes' => Yii::t('golfleague', 'Holes'),
            'rule_id' => Yii::t('golfleague', 'Rules ID'),
            'start_date' => Yii::t('golfleague', 'Start Date'),
            'registration_begin' => Yii::t('golfleague', 'Registration Begin'),
            'registration_end' => Yii::t('golfleague', 'Registration End'),
            'handicap_min' => Yii::t('golfleague', 'Handicap Min'),
            'handicap_max' => Yii::t('golfleague', 'Handicap Max'),
            'age_min' => Yii::t('golfleague', 'Age Min'),
            'age_max' => Yii::t('golfleague', 'Age Max'),
            'gender' => Yii::t('golfleague', 'Gender'),
            'maxplayers' => Yii::t('golfleague', 'Maxplayers'),
            'status' => Yii::t('golfleague', 'Status'),
            'created_at' => Yii::t('golfleague', 'Created At'),
            'updated_at' => Yii::t('golfleague', 'Updated At'),
            'flight_size' => Yii::t('golfleague', 'Flight Size'),
            'delta_time' => Yii::t('golfleague', 'Delta Time'),
            'special' => Yii::t('golfleague', 'Special'),
        ];
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
