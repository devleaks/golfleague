<?php

namespace common\models;

use common\behaviors\Constant;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "group".
 */
class Group extends _Group {
	use Constant;
	
	const GROUP_TYPE = null;

	const TYPE_FLIGHT	= 'FLIGHT';
	const TYPE_MATCH	= 'MATCH';
	const TYPE_TEAM		= 'TEAM';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); /* mysql datetime format is ‘AAAA-MM-JJ HH:MM:SS’*/},
                ],
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
			]
		);
    }

    /**
     * @inheritdoc
     */
	public static function find()
    {
        return new GroupQuery(get_called_class(), ['type' => self::GROUP_TYPE]);
    }


	/**
	 * find a document instance and returns it property typed.
     *
     * @return app\models\{Document,Bid,Order,Bill} the document
	 */
	public static function findGroup($id) {
		$model = Group::findOne($id);
		if($model)
			switch($model->competition_type) {
				case self::TYPE_FLIGHT:	return GFlight::findOne($id);	break;
				case self::TYPE_MATCH:	return GMatch::findOne($id);	break;
				case self::TYPE_TEAM:	return GTeam::findOne($id);		break;
			}
		return null;
	}
	
	
    /**
     * @inheritdoc
     */
	public static function instantiate($row)
	{
	    switch ($row['competition_type']) {
			case self::TYPE_FLIGHT:	return new GFlight();	break;
			case self::TYPE_MATCH:	return new GMatch();	break;
			case self::TYPE_TEAM:	return new GTeam();		break;
	        default:
	           return new self;
	    }
	}


    /**
     * @inheritdoc
     */
	public function getRegistrations() {
		return $this->hasMany(Registration::className(), ['registration_id' => 'id'])->viaTable('group_registration', ['group_id' => 'id']);
	}

	


}
