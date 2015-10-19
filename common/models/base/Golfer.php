<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "golfer".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $handicap
 * @property string $gender
 * @property string $birthdate
 * @property string $hand
 * @property string $homecourse
 * @property string $created_at
 * @property string $updated_at
 * @property integer $facility_id
 * @property integer $league_id
 *
 * @property League $league
 * @property User $user
 * @property Facility $facility
 * @property HandicapHistory[] $handicapHistories
 * @property Practice[] $practices
 * @property Registration[] $registrations
 */
class Golfer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'golfer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'facility_id', 'league_id'], 'integer'],
            [['name'], 'required'],
            [['handicap'], 'number'],
            [['birthdate', 'created_at', 'updated_at'], 'safe'],
            [['name', 'homecourse'], 'string', 'max' => 80],
            [['email', 'phone'], 'string', 'max' => 40],
            [['gender', 'hand'], 'string', 'max' => 20],
            [['name'], 'unique'],
            [['email'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'user_id' => Yii::t('golf', 'User ID'),
            'name' => Yii::t('golf', 'Name'),
            'email' => Yii::t('golf', 'Email'),
            'phone' => Yii::t('golf', 'Phone'),
            'handicap' => Yii::t('golf', 'Handicap'),
            'gender' => Yii::t('golf', 'Gender'),
            'birthdate' => Yii::t('golf', 'Birthdate'),
            'hand' => Yii::t('golf', 'Hand'),
            'homecourse' => Yii::t('golf', 'Homecourse'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'facility_id' => Yii::t('golf', 'Facility ID'),
            'league_id' => Yii::t('golf', 'League ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeague()
    {
        return $this->hasOne(League::className(), ['id' => 'league_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacility()
    {
        return $this->hasOne(Facility::className(), ['id' => 'facility_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHandicapHistories()
    {
        return $this->hasMany(HandicapHistory::className(), ['golfer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPractices()
    {
        return $this->hasMany(Practice::className(), ['golfer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrations()
    {
        return $this->hasMany(Registration::className(), ['golfer_id' => 'id']);
    }
}
