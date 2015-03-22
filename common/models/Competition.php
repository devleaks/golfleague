<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\db\ActiveRecord;
use common\components\Constant;

/**
 * This is the model class for table "competitions".
 *
 */
class Competition extends _Competition
{
	use Constant;
	
    /** Competition type SEASON */
    const TYPE_SEASON = 'SEASON';
    /** Competition type TOURNAMENT */
    const TYPE_TOURNAMENT = 'TOURNAMENT';
    /** Competition type MATCH */
    const TYPE_MATCH = 'MATCH';

    /** Competition is open for registration */
    const STATUS_OPEN = 'OPEN';
    /** Competition is ready to be played, or played */
    const STATUS_READY = 'READY';
    /** Competition is closed for registration (full, expired...) */
    const STATUS_CLOSED = 'CLOSED';


    /** Competition is open for men only */
    const GENDER_GENTLEMEN = Golfer::GENDER_GENTLEMAN;
    /** Competition is open for women only */
    const GENDER_LADIES = Golfer::GENDER_LADY;
    /** Competition is open for men and women */
    const GENDER_BOTH = 'BOTH';


    /** Competition is for local players only */
    const SPECIAL_LOCAL = 'HOMECOURSE';
    /** Competition is on invitation only */
    const SPECIAL_INVITE = 'INVITE';


	/** before registration opens */
	const PHASE_SCHEDULED		 	= 'SCHEDULED';
	/** after reg_open before reg_end */
	const PHASE_REGISTRATION_OPEN	= 'REGISTRATION_OPEN';
	/** after reg_end, no start_date */
	const PHASE_REGISTRATION_CLOSED	= 'REGISTRATION_CLOSED';
	/** between reg_end before start_date */
	const PHASE_READY		 		= 'READY';
	/**  on start_date day */
	const PHASE_ONGOING		 		= 'ONGOING';
	/** after start_date */
	const PHASE_COMPLETED		 	= 'COMPLETED';


    /** Size of flights (# of golfers) */
	const FLIGHT_SIZE_DEFAULT = 4;

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
	            [['competition_type'], 'in', 'range' => [Competition::TYPE_SEASON,Competition::TYPE_TOURNAMENT,Competition::TYPE_MATCH]],
	            [['gender'], 'in','range' => [Competition::GENDER_GENTLEMEN,Competition::GENDER_LADIES,Competition::GENDER_BOTH]],
        	]
		);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'Competition'),
            'competition_type' => Yii::t('igolf', 'Competition Type'),
            'name' => Yii::t('igolf', 'Name'),
            'description' => Yii::t('igolf', 'Description'),
            'parent_id' => Yii::t('igolf', 'Parent'),
            'course_id' => Yii::t('igolf', 'Course'),
            'holes' => Yii::t('igolf', 'Holes'),
            'rule_id' => Yii::t('igolf', 'Rules'),
            'start_date' => Yii::t('igolf', 'Start Date'),
            'registration_begin' => Yii::t('igolf', 'Registration Begin'),
            'registration_end' => Yii::t('igolf', 'Registration End'),
            'handicap_min' => Yii::t('igolf', 'Handicap Min'),
            'handicap_max' => Yii::t('igolf', 'Handicap Max'),
            'age_min' => Yii::t('igolf', 'Age Min'),
            'age_max' => Yii::t('igolf', 'Age Max'),
            'gender' => Yii::t('igolf', 'Gender'),
            'maxplayers' => Yii::t('igolf', 'Maxplayers'),
            'status' => Yii::t('igolf', 'Status'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'flight_size' => Yii::t('igolf', 'Flight Size'),
            'delta_time' => Yii::t('igolf', 'Delta Time'),
        ];
    }

    /*  Class Utility function
     *
     */

    /*  Registration Function
     *
     */
	public function getPhase() {
		$status = null;
		if ($this->status == Competition::STATUS_CLOSED)
			$status =  Competition::STATUS_CLOSED;
		else if ($this->status == Competition::STATUS_READY)
			$status =  Competition::PHASE_READY;
		else { // if($this->status == Competition::STATUS_OPEN) {	// competition is open, look further
			$now = date('Y-m-d H:i:s');
			if($this->registration_begin) {
				if($this->registration_begin < $now) { // open for reg
					if($this->registration_end) {
						if($this->registration_end > $now) { // open for reg
							$status =  Competition::PHASE_REGISTRATION_OPEN;
						} else { // no longer open for reg
							if($this->start_date) { // start_date, must be a match
								if($this->start_date > $now) {
									$status =  Competition::PHASE_READY;
								} if(substr($this->start_date, 10, 1) == substr($now, 10, 1)) { // same day
									$status =  Competition::PHASE_ONGOING;
								} else {
									$status =  Competition::PHASE_COMPLETED;
								}
							} else { // no start date, must be a season or tournament
								$status =  Competition::PHASE_REGISTRATION_CLOSED;
							}
						}
					} else { // no reg end date, can always register
						$status =  Competition::PHASE_REGISTRATION_OPEN;
					}
				} else { // not open for reg
					$status =  Competition::PHASE_SCHEDULED;
				}
			} else { // no reg start date, can always register
				$status =  Competition::PHASE_REGISTRATION_OPEN;
			}
		}
		return $status;
	}

    /**
     * Checks whether golfer registered to event
     * 
     * @var  Golfer $golfer Golfer to check
     * @return boolean
     */
    public function registered($golfer)
    {
        return Registration::find()
                            ->where(['golfer_id' 		=> $golfer->id,
                                     'competition_id'   => $this->id,
                                     'status'           => array( Registration::STATUS_PENDING,
																  Registration::STATUS_REGISTERED,
																  Registration::STATUS_CONFIRMED )
                                    ])
							->count() > 0;
    }


	/**
	 * Return whether a golfer can register to this competition or not
	 * Numerous small functions to check part of global check
	 *
	 * @param Golfer $golfer
	 * @return boolean whether a golfer can register to this competition or not
	 */
	private function genderOk($golfer) {
		return $this->gender ? ($this->gender === Competition::GENDER_BOTH) ||  ($this->gender === $golfer->gender) : true;
	}

	private function handicapMinOk($golfer) {
		return $this->handicap_min ? ($this->handicap_min < $golfer->handicap) : true;
	}

	private function handicapMaxOk($golfer) {
		return $this->handicap_max ? ($this->handicap_max > $golfer->handicap) : true;
	}

	private function ageMinOk($golfer) {
		return $this->age_min ? ($this->age_min < $golfer->age()) : true;
	}

	private function ageMaxOk($golfer) {
		return $this->age_max ? ($this->age_max > $golfer->age()) : true;
	}

	private function golferOk($golfer) {
		$canRegister = $this->genderOk($golfer)
					&& $this->handicapMinOk($golfer)
					&& $this->handicapMaxOk($golfer)
					&& $this->ageMinOk($golfer)
					&& $this->ageMaxOk($golfer);

		if(!$canRegister)
			Yii::$app->session->setFlash('error', 'You do not meet handicap/age/gender restriction on the competition.');
		
		return $canRegister;
	}
	
	private function maxPlayerOk() {
		return intval($this->maxplayers) > 0 ?
				$this->getRegistrations()
					 ->andWhere(['status' => [Registration::STATUS_REGISTERED, Registration::STATUS_CONFIRMED]])
					 ->count() <= intval($this->maxplayers)
			   : true;
	}

	private function dateOk() {
		if($this->status == Competition::STATUS_OPEN) {	// competition is open
			$phase = $this->getPhase();
			
			if ($phase == Competition::PHASE_REGISTRATION_OPEN) {
				return true;
			} else if ($phase == Competition::PHASE_SCHEDULED) {
				Yii::$app->session->setFlash('error', 'Competition is not yet open for registration.');
			} else if ($phase == Competition::REGISTRATION_CLOSED) {
				Yii::$app->session->setFlash('error', 'Competition is no longer open for registration.');
			}			
		} else {
			Yii::$app->session->setFlash('error', 'Competition is closed.');
		}		
		return false;
	}

	public function canRegister($golfer) {
		return $this->dateOk() && $this->maxPlayerOk() && $this->golferOk($golfer);
	}
	
    /**
     * Register golfer to event
     * 
     * @var  Golfer $golfer Golfer to register
     * @return Registration
     */
	function register($golfer, $force = false) {
		$canRegister = true;

		if($this->parent_id) {
			$parent = $this->getParent()->one();
			$canRegister = $parent->register($golfer, $force);
		}

		if( $canRegister && ($force || $this->canRegister($golfer)) ) {
	        $model = Registration::findOne([
	                     'golfer_id' => $golfer->id,
	                     'competition_id'   => $this->id    ]);

	        if(!$model) {
	            $model = new Registration();
	            $model->golfer_id = $golfer->id;
	            $model->competition_id   = $this->id;
	        } // force can only be used by starters
	        $model->status = $force ? Registration::STATUS_REGISTERED : Registration::STATUS_PENDING;
	        $model->save();
			return true;
		}
		return false;
	}
	
	/**
     * Deregister golfer from event
     * 
     * @var  Golfer $golfer Golfer to register
     * @return Deregistration status
     */
    public function deregister($golfer)
    {
        $model = Registration::findOne([
                     'golfer_id' => $golfer->id,
                     'competition_id'   => $this->id    ]);

        if($model) {
            $model->status = Registration::STATUS_CANCELLED;
            return $model->save();
        }
        return false;
    }


    public static function open($query)
    {
        $query->andWhere(['status', self::STATUS_OPEN]);
    }

    public static function closed($query)
    {
        $query->andWhere(['status', self::STATUS_CLOSED]);
    }

    public static function ready($query)
    {
        $query->andWhere(['status', self::STATUS_READY]);
    }
/*
    public static function defaultScope($query)
    {
        $query->andWhere(['>', 'id', 0]);
    }
*/

	public function createEvents() {
		if(isset($this->registration_start) && $this->registration_start > $now) {
			$event = new Event();
			$event->object_type = $this->competition_type;
			$event->object_id = $this->competition_id;
			$event->category = Event::TYPE_COMPETITION_REGSTART;
			$event->event_start = $this->registration_start;
			$event->save();
		}
		if(isset($this->registration_end) && $this->registration_end > $now) {
			$event = new Event();
			$event->object_type = $this->competition_type;
			$event->object_id = $this->competition_id;
			$event->category = Event::TYPE_COMPETITION_REGEND;
			$event->event_start = $this->registration_end;
			$event->save();
		}
		if(isset($this->start_date) && $this->start_date > $now) {
			$event = new Event();
			$event->object_type = $this->competition_type;
			$event->object_id = $this->competition_id;
			$event->category = Event::TYPE_COMPETITION;
			$event->event_start = $this->start_date;
			$event->save();
		}
	}
	
	public function breadcrumbs() {
		$before = $this->parent_id ? $this->parent->breadcrumbs() : [];
		$before[] = ['label' => Yii::t('igolf', $this->name), 'url' => ['view', 'id'=>$this->id]];
		return $before;
	}

	public function childType() {
		switch($this->competition_type) {
			case $this::TYPE_SEASON: return $this::TYPE_TOURNAMENT; break;
			case $this::TYPE_TOURNAMENT: return $this::TYPE_MATCH; break;
		}
		return null;
	}

	public function parentType() {
		switch($this->competition_type) {
			case $this::TYPE_TOURNAMENT: return $this::TYPE_SEASON; break;
			case $this::TYPE_MATCH: return $this::TYPE_TOURNAMENT; break;
		}
		return null;
	}

    /**
	 * Returns new Competition of proper type.
     */
	public static function getNew($type)
	{
	    switch ($type) {
	        case Competition::TYPE_SEASON:
	            $new = new Season();
				break;
	        case Competition::TYPE_TOURNAMENT:
	            $new = new Tournament();
				break;
	        case Competition::TYPE_MATCH:
	            $new = new Match();
				break;
	        default:
	        	$new = new self;
				break;
	    }
		$new->competition_type = $type;
		return $new;
	}

}
