<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $role
 * @property integer $flags
 * @property string $auth_key
 * @property string $password_hash
 * @property integer $confirmed_at
 * @property string $unconfirmed_email
 * @property integer $blocked_at
 * @property integer $registration_ip
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $league_id
 *
 * @property Golfer[] $golfers
 * @property League[] $leagues
 * @property Message[] $messages
 * @property Profile $profile
 * @property SocialAccount[] $socialAccounts
 * @property Token[] $tokens
 * @property League $league
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'auth_key', 'password_hash', 'created_at', 'updated_at'], 'required'],
            [['flags', 'confirmed_at', 'blocked_at', 'registration_ip', 'created_at', 'updated_at', 'league_id'], 'integer'],
            [['username'], 'string', 'max' => 25],
            [['email', 'role', 'unconfirmed_email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash'], 'string', 'max' => 60],
            [['username'], 'unique'],
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
            'username' => Yii::t('golf', 'Username'),
            'email' => Yii::t('golf', 'Email'),
            'role' => Yii::t('golf', 'Role'),
            'flags' => Yii::t('golf', 'Flags'),
            'auth_key' => Yii::t('golf', 'Auth Key'),
            'password_hash' => Yii::t('golf', 'Password Hash'),
            'confirmed_at' => Yii::t('golf', 'Confirmed At'),
            'unconfirmed_email' => Yii::t('golf', 'Unconfirmed Email'),
            'blocked_at' => Yii::t('golf', 'Blocked At'),
            'registration_ip' => Yii::t('golf', 'Registration Ip'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'league_id' => Yii::t('golf', 'League ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolfers()
    {
        return $this->hasMany(Golfer::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialAccounts()
    {
        return $this->hasMany(SocialAccount::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(Token::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeague()
    {
        return $this->hasOne(League::className(), ['id' => 'league_id']);
    }
}
