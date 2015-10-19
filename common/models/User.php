<?php

namespace common\models;

use common\behaviors\Constant;
use common\behaviors\MediaBehavior;

use Yii;

/**
 * User model
 */
class User extends \dektrium\user\models\User
{
	use Constant;
	
	const MAX_IMAGES = 1;
	
	const ROLE_ADMIN = 'admin';
	const ROLE_MANAGER = 'manager';
	const ROLE_STARTER = 'starter';
	const ROLE_SCORER = 'scorer';
	const ROLE_GOLFER = 'golfer';
	const ROLE_GOLFERPLUS = 'golferplus';
	const ROLE_MARKER = 'marker';
	
	public $default_profile_picture = 'images/golfer.png';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
				'uploadFile' => [
	                'class' => MediaBehavior::className(),
	                'mediasAttributes' => ['media']
	            ]
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
			parent::rules(),
			[
				// Role
	            [['role'], 'string', 'max' => 255],
				['role', 'default', 'value' => User::ROLE_GOLFER],
				// League
            	[['league_id'], 'integer'],
				['league_id', 'required',
					'when' => function($model) {
					        return !Yii::$app->user->identity->isAdmin();
					 },
					'message' => Yii::t('golf', 'A league is required if user not an administrator.')
				]
        	]
		);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
			parent::attributeLabels(),
			[
	            'role' => Yii::t('golf', 'Role'),
        	]
		);
    }

    /**
     * @inheritdoc
	 * Note: When we update User, we only update those additional attributes, original attributes from \dektrium\user\models\User
	 *       are updated from Dektrium's UI. Dektrium defines scenarii, so must must override his definition here and at least provide a default.
     */
	function scenarios() {
		return [ 
	    	'default' => ['role','league_id'], 
	    ];
	}
	

    /**
     * @inheritdoc
     */
	public static function find()
    {
        return new query\UserQuery(get_called_class());
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolfer()
    {
        return $this->hasOne(Golfer::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeague()
    {
        return $this->hasOne(League::className(), ['id' => 'league_id']);
    }

	public function getProfilePicture() {
		if($this->media)
			if($pic = $this->media[0]->getThumbnailUrl())
				return $pic;
		return $this->default_profile_picture;
	}
	
	/**
	 * Checks whether a user has a role.
	 */
	public function isA($roles) {
		return is_array($roles) ? in_array($this->role, $roles) : $this->role == $roles;
	}

	/**
	 * Check whether user is admin role. Added to prevent inclusion of User model where isA(User::ROLE_ADMIN) needed.
	 */
	public function isAdmin() {
		return $this->isA(self::ROLE_ADMIN);
	}

}
