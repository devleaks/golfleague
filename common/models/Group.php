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
			switch($model->group_type) {
				case self::TYPE_FLIGHT:	return Flight::findOne($id);	break;
				case self::TYPE_MATCH:	return Match::findOne($id);		break;
				case self::TYPE_TEAM:	return Team::findOne($id);		break;
			}
		return null;
	}
	
	
    /**
     * @inheritdoc
     */
	public static function instantiate($row)
	{
	    switch ($row['group_type']) {
			case self::TYPE_FLIGHT:	return new Flight();	break;
			case self::TYPE_MATCH:	return new Match();		break;
			case self::TYPE_TEAM:	return new Team();		break;
	        default:
	           return new self;
	    }
	}


    /**
	 * Returns new Competition of proper type.
     */
	public static function getNew($type) {
	    switch ($type) {
	        case Group::TYPE_FLIGHT:
	            $new = new Flight();
				break;
	        case Group::TYPE_MATCH:
	            $new = new Match();
				break;
	        case Group::TYPE_TEAM:
	            $new = new Team();
				break;
	        default:
	        	$new = new self;
				break;
	    }
		$new->group_type = $type;
		return $new;
	}


    /**
     * @inheritdoc
     */
	public function getRegistrations() {
		return $this->hasMany(Registration::className(), ['id' => 'registration_id'])->viaTable('registration_group', ['group_id' => 'id']);
	}

    /**
     * @inheritdoc
     */
	public function getCompetition() {
		if($registration = $this->getRegistrations()->one()) {
			return Competition::findOne($registration->competition_id);
		}
		return null;
	}

    /**
     * @inheritdoc
     */
	public function getMatches() {
		return Match::find()->joinWith('registrations')->where(['registration_id' => $this->getRegistrations()->select('id')]);
	}

    /**
     * @inheritdoc
     */
	public function getTeams() {
		return Team::find()->joinWith('registrations')->where(['registration_id' => $this->getRegistrations()->select('id')]);
	}

    /**
     * @inheritdoc
     */
	public function getFlights() {
		return Flight::find()->joinWith('registrations')->where(['registration_id' => $this->getRegistrations()->select('id')]);
	}


    /**
     * @inheritdoc
     */
	public function getScorecards() {
		return Scorecard::find()->where(['registration_id' => $this->getRegistrations()->select('id')]);
	}


    /**
     * Get a label for match made from competitor's name separated by separator
     */
	public function getLabel($separator = '/') {
		$names = '';
		foreach($this->getRegistrations()->each() as $registration) {
			$names .= $registration->golfer->name.$separator;
		}
		return substr($names, 0, - strlen($separator));;
	}

	/**
	 * Add one registration from group
	 */
	public function add($registration) {
		$link = null;
		if(! $this->getRegistrationGroups()->andWhere(['registration_id' => $registration->id])->exists() ) {
			$pos = $this->getRegistrationGroups()->count() + 1;
			$link = new RegistrationGroup([
				'registration_id' => $registration->id,
				'group_id' => $this->id,
				'position' => $pos
			]);
			$link->save();
			//Yii::trace(print_r($link->errors, true) , 'Group::add');
			//Yii::trace('added='.$registration->id.'='.$this->id, 'Group::add');
		}
		return $link;
	}

	/**
	 * Removes one registration from group
	 */
	public function remove($registration) {
		if($link = $this->getRegistrationGroups()->andWhere(['registration_id' => $registration->id])) {
			return $link->delete();
		}
		return false;
	}
	
	/**
	 * Removes all registration from group
	 */
	public function clean() {
		foreach($this->getRegistrationGroups()->each() as $rg)
			$rg->delete();
	}

}
