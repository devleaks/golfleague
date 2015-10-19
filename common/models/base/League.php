<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "league".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $website
 * @property string $units
 * @property string $created_at
 * @property string $updated_at
 * @property string $handicap_system
 * @property string $theme
 * @property string $theme_match
 * @property string $theme_params
 *
 * @property Competition[] $competitions
 * @property Event[] $events
 * @property Golfer[] $golfers
 * @property Message[] $messages
 * @property Rule[] $rules
 * @property User[] $users
 */
class League extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'league';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'theme_params'], 'string', 'max' => 80],
            [['phone', 'units'], 'string', 'max' => 20],
            [['website'], 'string', 'max' => 255],
            [['handicap_system', 'theme', 'theme_match'], 'string', 'max' => 40],
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
            'handicap_system' => Yii::t('golf', 'Handicap System'),
            'theme' => Yii::t('golf', 'Theme'),
            'theme_match' => Yii::t('golf', 'Theme Match'),
            'theme_params' => Yii::t('golf', 'Theme Params'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetitions()
    {
        return $this->hasMany(Competition::className(), ['league_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['league_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolfers()
    {
        return $this->hasMany(Golfer::className(), ['league_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['league_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRules()
    {
        return $this->hasMany(Rule::className(), ['league_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['league_id' => 'id']);
    }
}
