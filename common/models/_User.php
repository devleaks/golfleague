<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property integer $confirmed_at
 * @property string $unconfirmed_email
 * @property integer $blocked_at
 * @property integer $registration_ip
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $flags
 * @property string $role
 *
 * @property Golfer[] $golfers
 * @property Profile $profile
 * @property SocialAccount[] $socialAccounts
 * @property Token[] $tokens
 */
class _User extends \yii\db\ActiveRecord
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
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['confirmed_at', 'blocked_at', 'registration_ip', 'created_at', 'updated_at', 'flags'], 'integer'],
            [['username'], 'string', 'max' => 25],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash'], 'string', 'max' => 60],
            [['email', 'unconfirmed_email', 'role'], 'string', 'max' => 255],
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
            'id' => Yii::t('golfleague', 'ID'),
            'username' => Yii::t('golfleague', 'Username'),
            'auth_key' => Yii::t('golfleague', 'Auth Key'),
            'password_hash' => Yii::t('golfleague', 'Password Hash'),
            'email' => Yii::t('golfleague', 'Email'),
            'confirmed_at' => Yii::t('golfleague', 'Confirmed At'),
            'unconfirmed_email' => Yii::t('golfleague', 'Unconfirmed Email'),
            'blocked_at' => Yii::t('golfleague', 'Blocked At'),
            'registration_ip' => Yii::t('golfleague', 'Registration Ip'),
            'created_at' => Yii::t('golfleague', 'Created At'),
            'updated_at' => Yii::t('golfleague', 'Updated At'),
            'flags' => Yii::t('golfleague', 'Flags'),
            'role' => Yii::t('golfleague', 'Role'),
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
}
