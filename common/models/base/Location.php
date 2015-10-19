<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $address
 * @property string $postcode
 * @property string $city
 * @property string $country
 * @property string $position
 * @property string $status
 * @property string $updated_at
 * @property string $created_at
 *
 * @property Event[] $events
 * @property Facility[] $facilities
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['updated_at', 'created_at'], 'safe'],
            [['name', 'city'], 'string', 'max' => 80],
            [['description', 'address', 'position'], 'string', 'max' => 160],
            [['postcode', 'country'], 'string', 'max' => 40],
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
            'name' => Yii::t('golf', 'Name'),
            'description' => Yii::t('golf', 'Description'),
            'address' => Yii::t('golf', 'Address'),
            'postcode' => Yii::t('golf', 'Postcode'),
            'city' => Yii::t('golf', 'City'),
            'country' => Yii::t('golf', 'Country'),
            'position' => Yii::t('golf', 'Position'),
            'status' => Yii::t('golf', 'Status'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'created_at' => Yii::t('golf', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['location_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacilities()
    {
        return $this->hasMany(Facility::className(), ['location_id' => 'id']);
    }
}
