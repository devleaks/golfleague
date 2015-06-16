<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\db\ActiveRecord;
use common\behaviors\Constant;
use common\behaviors\MediaBehavior;

/**
 * This is the model class for table "competitions".
 *
 */
class Competition extends _Competition
{
	use Constant;
	
	const COMPETITION_TYPE = null;

    /** Competition type SEASON */
    const TYPE_SEASON = 'SEASON';
    /** Competition type TOURNAMENT */
    const TYPE_TOURNAMENT = 'TOURNAMENT';
    /** Competition type MATCH */
    const TYPE_MATCH = 'MATCH';

    /** Competition is open, for registration if within time frame */
    const STATUS_OPEN = 'OPEN';
    /** Competition is ready to be played, or played */
    const STATUS_READY = 'READY';
    /** Competition is ready to be played, or played */
    const STATUS_COMPLETED = 'COMPLETED';
    /** Competition is closed for registration (full, expired...) */
    const STATUS_CLOSED = 'CLOSED';


    /** Competition is open for men only */
    const GENDER_GENTLEMEN = Golfer::GENDER_GENTLEMAN;
    /** Competition is open for women only */
    const GENDER_LADIES = Golfer::GENDER_LADY;
    /** Competition is open for men and women */
    const GENDER_BOTH = 'BOTH';


    /** Competition is for local players only, i.e. golfer whose home course is the course where the competition is being played */
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
	            [['competition_type'], 'in', 'range' => array_keys(self::getConstants('TYPE_'))],
	            [['gender'], 'in','range' => array_keys(self::getConstants('GENDER_'))],
	            [['registration_special'], 'in','range' => array_keys(self::getConstants('SPECIAL_'))],
	            [['status'], 'in', 'range' => array_keys(self::getConstants('STATUS_'))],
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
            'parent_id' => Yii::t('igolf', 'Part of'),
            'course_id' => Yii::t('igolf', 'Course'),
            'holes' => Yii::t('igolf', 'Holes'),
            'rule_id' => Yii::t('igolf', 'Rule/Format'),
            'rule_final_id' => Yii::t('igolf', 'Post-Competition Rule'),
            'start_date' => Yii::t('igolf', 'Start Date'),
            'registration_begin' => Yii::t('igolf', 'Registration Begin'),
            'registration_end' => Yii::t('igolf', 'Registration End'),
            'handicap_min' => Yii::t('igolf', 'Handicap Min'),
            'handicap_max' => Yii::t('igolf', 'Handicap Max'),
            'age_min' => Yii::t('igolf', 'Age Min'),
            'age_max' => Yii::t('igolf', 'Age Max'),
            'gender' => Yii::t('igolf', 'Gender'),
            'max_players' => Yii::t('igolf', 'Maxplayers'),
            'status' => Yii::t('igolf', 'Status'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'flight_size' => Yii::t('igolf', 'Flight Size'),
            'delta_time' => Yii::t('igolf', 'Delta Time'),
            'media' => Yii::t('igolf', 'Pictures'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Competition::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetitions()
    {
        return $this->hasMany(Competition::className(), ['parent_id' => 'id']);
    }

	public static function find()
    {
        return new CompetitionQuery(get_called_class(), ['type' => self::COMPETITION_TYPE]);
    }


	/**
	 * More relations
	 */


	/**
	 * General Information
	 */
	
	/**
	 * Get hierarchical list of links for breadcrumbs, from Season to match.
	 *
	 * @return array breadcrumbs widget data
	 */
	public function breadcrumbs() {
		$before = $this->parent_id ? $this->parent->breadcrumbs() : [];
		$before[] = ['label' => Yii::t('igolf', $this->name), 'url' => ['view', 'id'=>$this->id]];
		return $before;
	}

	/**
	 * Get hierarchical name, from Season to match.
	 *
	 * @return string Full competition name
	 */
	public function getFullName() {
		if($this->parent)
			return $this->parent->getFullName() . ' » ' . $this->name;
		else
			return $this->name;
	}

	/**
	 * Returns scorecards for this competition. Ignore registered non-participant.
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getScorecards() {
		return $this->hasMany(Scorecard::className(), ['registration_id' => 'id'])->viaTable('registration', ['competition_id' => 'id']);
		//return Scorecard::find()->where(['registration_id' => $this->getRegistrations()->select('id')]);
	}

	/**
	 * Returns competition teams, if any
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getTeams() {
		return $this->hasMany(Team::className(), ['id' => 'team_id'])->viaTable('registration', ['competition_id' => 'id']);
	}
	
	/**
	 * Returns type of child competition, if any.
	 */
	public function childType() {
		switch($this->competition_type) {
			case $this::TYPE_SEASON: return $this::TYPE_TOURNAMENT; break;
			case $this::TYPE_TOURNAMENT: return $this::TYPE_MATCH; break;
		}
		return null;
	}

	/**
	 * Returns type of parent competition, if any.
	 */
	public function parentType() {
		switch($this->competition_type) {
			case $this::TYPE_TOURNAMENT: return $this::TYPE_SEASON; break;
			case $this::TYPE_MATCH: return $this::TYPE_TOURNAMENT; break;
		}
		return null;
	}
	
	/**
	 * Returns total number of rounds.
	 */	
	public function getRounds() {
		return $this->getCompetitions()->count();
	}

	/**
	 * Get sequence number of competition in parent competition. Competitions are sorted by start date.
	 *
	 * @return integer Sequence number, starting from 1. Returns 0 if not applicable.
	 */
	public function getRound() {
		$seq = 0;
		if($parent = $this->getParent()->one()) { // will be null for season
			$seq = 1;
			foreach($parent->getCompetitions()->each() as $competition) { // are we sure comp is always in its parent's children !? ;-)
				if($competition->id = $this->id) {
					return $seq;
				} else {
					$seq++;
				}
			}
		}
		return $seq;
	}

	/**
	 * returns current match if any
	 *
	 * @return common\models\Match
	 */
	public function currentMatch() {
		return null;
	}

	/**
	 * Get end date of a competition. Matches are assumesd to be one day only.
	 */
	public function getEndDate() {
		if($this->competition_type == self::TYPE_MATCH)
			return $this->start_date;
		else
			if ($last_competition = $this->getCompetitions()->orderBy('start_date desc')->one())
				return $last_competition->getEndDate();
		return $this->start_date;
	}

	/**
	 * Get end date of a competition. Matches are assumes to be one day only.
	 */
	public function getStartDate() {
		if($this->competition_type == self::TYPE_MATCH)
			return $this->start_date;
		else
			if ($first_competition = $this->getCompetitions()->orderBy('start_date asc')->one())
				return $first_competition->getStartDate();
		return $this->start_date;
	}
	
	/**
	 * Whether a competition has started and has scores registered.
	 *
	 * @return boolean
	 */
	public function hasScores() {
		foreach($this->getRegistrations()->andWhere(['status' => Registration::getParticipantStatuses()])->each() as $registration) {
			if($registration->hasScore())
				return true;
		}
		return false;
	}


    /**
	 * Returns new Competition of proper type.
     */
	public static function getNew($type) {
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



	/**
	 * Registrations
	 */
	
    /**
     * Checks whether golfer registered to event
     * 
     * @var  Golfer $golfer Golfer to check
     * @return boolean
     */
    public function registered($golfer) {
        return Registration::find()
                            ->where(['golfer_id' 		=> $golfer->id,
                                     'competition_id'   => $this->id,
                                     'status'           => array( Registration::STATUS_PENDING,
																  Registration::STATUS_REGISTERED,
																  Registration::STATUS_CONFIRMED )
                                    ])
							->exists();
    }


	/**
	 * Return whether a golfer can register to this competition or not
	 * Numerous small functions to check part of global check
	 *
	 * @param Golfer $golfer
	 * @return boolean whether a golfer can register to this competition or not
	 */
	protected function genderOk($golfer) {
		return $this->gender ? ($this->gender === Competition::GENDER_BOTH) ||  ($this->gender === $golfer->gender) : true;
	}

	protected function handicapMinOk($golfer) {
		return $this->handicap_min ? ($this->handicap_min < $golfer->handicap) : true;
	}

	protected function handicapMaxOk($golfer) {
		return $this->handicap_max ? ($this->handicap_max > $golfer->handicap) : true;
	}

	protected function ageMinOk($golfer) {
		return $this->age_min ? ($this->age_min < $golfer->age()) : true;
	}

	protected function ageMaxOk($golfer) {
		return $this->age_max ? ($this->age_max > $golfer->age()) : true;
	}

	protected function golferOk($golfer) {
		$canRegister = $this->genderOk($golfer)
					&& $this->handicapMinOk($golfer)
					&& $this->handicapMaxOk($golfer)
					&& $this->ageMinOk($golfer)
					&& $this->ageMaxOk($golfer);

		if(!$canRegister)
			Yii::$app->session->setFlash('error', 'You do not meet handicap/age/gender restriction on the competition.');
		
		return $canRegister;
	}
	
	protected function maxPlayerOk() {
		return intval($this->max_players) > 0 ?
				$this->getRegistrations()
					 ->andWhere(['status' => [Registration::STATUS_REGISTERED, Registration::STATUS_CONFIRMED]])
					 ->count() <= intval($this->max_players)
			   : true;
	}

	protected function dateOk() {
		if($this->status == Competition::STATUS_OPEN) {	// competition is open
			$now = date('Y-m-d H:i:s');

			if($this->registration_begin) {
				if($this->registration_begin < $now) { // open for reg
					if($this->registration_end) {
						if($this->registration_end > $now) { // open for reg
							return true;
						} else {
							Yii::$app->session->setFlash('error', 'Competition is no longer open for registration.');
						}
					} else { // no reg end date, and after reg start, assume can always register after reg start
						return true;
					}
				} else {
					Yii::$app->session->setFlash('error', 'Competition is not yet open for registration.');
				}
			} else { // no reg start date,assume can always register
				return true;
			}
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

//		Yii::trace($this->id.' for '.$golfer->id, 'Competition::register');
		if($parent = $this->parent) {
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
			Yii::trace('OK for '.$this->id.' for '.$golfer->id, 'Competition::register');
			return true;
		}
		Yii::trace('NOT for '.$this->id.' for '.$golfer->id, 'Competition::register');
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

	/**
	 *	Assigns appropriate starting tees set for supplied registration.
	 */
	public function setTees($registration) {
		foreach($this->getStarts()->each() as $start) {
			if($start->isOk($registration->golfer) && !$registration->tees_id)
				$registration->tees_id = $start->tees_id;
				$registration->save();
		}
	}

	/**
	 * Finds the "longest"most appropriate" tees from all possible starting tees sets. Returns null if none found (error).
	 *
	 * @return common\models\Tees Longest starting tee set.
	 */
	public function getTees() {
		if($start = $this->getStarts()->one()) { // @todo: Get most appropriate tees set. Ladies only? Pro only? ... for now, returns first one found.
			return $start->tees;
		}
		return null;
	}



	/**
	 * Events
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
	
	/**
	 * Generates events for this competition
	 */
	public function getEvents() {
		$events = [];
		/*
		$events[] = new Event([
			'object_type' => $this->competition_type,
			'object_id' => $this->id,
			'name'	=> $this->name,
			'description' => Yii::t('igolf', 'Competition registration'),
			'event_type' => 'REGISTRATION',
			'status' => Event::STATUS_ACTIVE,
			'event_start' => $this->registration_begin,
			'event_end' => $this->registration_end,
		]);*/
		if($this->competition_type == self::TYPE_MATCH) {
			$events[] = new Event([
				'object_type' => $this->competition_type,
				'object_id' => $this->id,
				'name'	=> $this->name,
				'description' => Yii::t('igolf', $this->competition_type),
				'event_type' => 'COMPETITION',
				'status' => Event::STATUS_ACTIVE,
				'event_start' => $this->start_date,
				'event_end' => null,
			]);
		}
		/*
		foreach(CompetitionRegistration::find()->orderBy('event_start')->each() as $event) {
			$start = \DateTime::createFromFormat('Y-m-d H:i:s', $event->event_start);
			$end   = \DateTime::createFromFormat('Y-m-d H:i:s', $event->event_end);
						
			$calendarEvents[] = new CalendarEvent([
				'id' => $event->id,
				'title' => $event->name,
				'url' => Url::to(['view', 'id'=>$event->id]),
				'className' => 'btn-'.$event->getColor(),
 				'start' => date('Y-m-d\TH:m:s\Z',$start->getTimestamp()),
				'end' => $end ? date('Y-m-d\TH:m:s\Z',$end->getTimestamp()) : null,
			]);
		}
		*/
		return $events;
	}
	
	

	
	/**
	 * Teams
	 */
	public function isTeamCompetition() {
		return $this->rule->team > 1;
	}

	/**
	 * Checks whether some registrations are not in team yet
	 */
	public function isTeamOk() {
		$team_size = $this->rule->team;
		if(!$this->getTeams()->exists())
			return false;
		$registrations = [];
		foreach($this->getRegistrations()->andWhere(['status' => Registration::STATUS_REGISTERED])->each() as $registration) {
			$registrations[$registration->id] = $registration;
			Yii::trace('adding '.$registration->id);
		}
			
		foreach($this->getTeams()->each() as $team) {
			$regcount = 0;
			foreach($team->getRegistrations()->each() as $registration) {
				$regcount++;
				unset($registrations[$registration->id]);
				Yii::trace('removing '.$registration->id);
			}
			Yii::trace('checking '.$regcount.' vs '.$team_size);
			if($regcount != $team_size)
				return false;
		}
		Yii::trace('final '.count($registrations));
		return count($registrations) == 0;
	}

}