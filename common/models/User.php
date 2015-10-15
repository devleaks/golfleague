<?php
namespace common\models;

use Yii;

/**
 * User model
 */
class User extends \dektrium\user\models\User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
			parent::rules(),
			[
	            [['role'], 'string', 'max' => 255],
            	[['league_id'], 'integer'],
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


}
