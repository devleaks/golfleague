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
		return $this->hasMany(Registration::className(), ['id' => 'registration_id'])->viaTable(GroupMember::tableName(), ['group_id' => 'id']);
	}

    /**
     * @inheritdoc
     */
	public function getCompetition() {
		if($group_member = $this->getGroupMembers()->one()) {
			if($registration = $group_member->getRegistration()->one()) {
				return Competition::findOne($registration->competition_id);
			}
		}
		return null;
	}

	/**
	 * Get flight this group is in.
	 */
	public function getFlight() {
		if($registration-> $this->getRegistrations()->one()) {
			return $registration->getFlight();
		}
		return null;
	}
	
	public function getTeams() {
		return Team::find()->andWhere(['id' => GroupMember::find()->andWhere(['registration_id' => $this->getRegistrations()->select('id')->distinct()])->select('group_id')->distinct()]);
	}

    /**
     * @inheritdoc
     */
	public function getScorecards() {
		return Scorecard::find()->where(['id' => $this->getRegistrations()->select('scorecard_id')]);
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

	public function add($registration) {
		if(!GroupMember::find()->andWhere(['group_id' => $this->id, 'registration_id' => $registration->id])->exists()) {
			$pos = $this->getGroupMembers()->count() + 1;
			$link = new GroupMember([
				'registration_id' => $registration->id,
				'group_id' => $this->id,
				'position' => $pos
			]);
			$link->save();
			$this->handicap += $registration->getHandicap();
			$this->save();
			Yii::trace('added='.$registration->id.' to '.$this->id, 'Group::add');
		}
	}

	/**
	 * Removes one registration from group
	 */
	public function remove($registration) {
		if($link = $this->getGroupMembers()->andWhere(['registration_id' => $registration->id])) {
			$this->handicap -= $registration->getHandicap();
			$this->save();
			return $link->delete();
		}
		return false;
	}
	
	/**
	 * Removes all registration from group
	 */
	public function clean($delete = false) {
		foreach($this->getGroupMembers()->each() as $rg) {
			$rg->delete();
		}
			
		if($delete)
			return $this->delete();

		$this->handicap = 0;
		return $this->save();
	}

}
