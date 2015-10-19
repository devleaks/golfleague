<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "facility".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $website
 * @property string $units
 * @property string $created_at
 * @property string $updated_at
 * @property integer $location_id
 *
 * @property Course[] $courses
 * @property Event[] $events
 * @property Location $location
 * @property Golfer[] $golfers
 * @property Message[] $messages
 */
class Facility extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'facility';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['location_id'], 'integer'],
            [['name', 'email'], 'string', 'max' => 80],
            [['phone', 'units'], 'string', 'max' => 20],
            [['website'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'name' => Yii::t('golf', 'Name'),
            'phone' => Yii::t('golf', 'Phone'),
            'email' => Yii::t('golf', 'Email'),
            'website' => Yii::t('golf', 'Website'),
            'units' => Yii::t('golf', 'Units'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'location_id' => Yii::t('golf', 'Location ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['facility_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['facility_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolfers()
    {
        return $this->hasMany(Golfer::className(), ['facility_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['facility_id' => 'id']);
    }
}
