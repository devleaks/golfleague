<?php

namespace common\models;

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
 *
 * @property Course[] $courses
 */
class _Facility extends \yii\db\ActiveRecord
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
            [['created_at', 'updated_at'], 'safe'],
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
            'id' => Yii::t('igolf', 'ID'),
            'name' => Yii::t('igolf', 'Name'),
            'phone' => Yii::t('igolf', 'Phone'),
            'email' => Yii::t('igolf', 'Email'),
            'website' => Yii::t('igolf', 'Website'),
            'units' => Yii::t('igolf', 'Units'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['facility_id' => 'id']);
    }
}
