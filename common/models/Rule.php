<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;
/**
 * This is the model class for table "rules".
 */
class Rule extends _Rule
{
	use Constant;
	
	/** Rule type for competiton behavior */
	const TYPE_STROKEPLAY = 'STROKE';
	const TYPE_MATCHPLAY  = 'MATCH';
	

	/** Type/semantic of points in points column. Used for rounding, comparison, etc. */
	const POINT_FORMAT		= 'INT';
	const POINT_POSITIVE	= 'POS';
	const POINT_HALF		= 'HALF';
	const POINT_DECIMAL1	= 'DEC1';
	const POINT_DECIMAL2	= 'DEC1';
	
	const HALF_POINT = ' ½';
	
	public $flightMethods;
	public $teamMethods;
	public $matchMethods;
	
	public $defaultMethodName = 'Standard';
	
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
                        'value' => function() { return date('Y-m-d H:i:s');},
                ],
        ];
    }

/*
	public function attributes()
	{
	    return array_merge(['flightMethods', 'teamMethods', 'matchMethods'], parent::attributes());
	}
*/

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
			parent::rules(),
			[
				'flightMethods' => Yii::t('golf', 'Flight Building Methods'),
				'teamMethods' => Yii::t('golf', 'Team Building Methods'),
				'matchMethods' => Yii::t('golf', 'Match Building Methods'),
			]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
			parent::rules(),
			[
	            [['source_direction'], 'in', 'range' => array_keys(Scorecard::getConstants('DIRECTION_'))],
            	[['source_type', 'destination_type'], 'in', 'range' => array_keys(Scorecard::getConstants('SCORE_'))],
	            [['status'], 'in', 'range' => array_keys(self::getConstants('STATUS_'))],
	            [['flightMethods', 'teamMethods', 'matchMethods'], 'safe'],
        	]
		);
    }

    /**
     * @inheritdoc
     */
	public static function instantiate($row)
	{
	    switch ($row['classname']) {
	        case rule\Copy::className():
	            return new rule\Copy();
	        case rule\Rank::className():
	            return new rule\Rank();
	        case rule\SumChildren::className():
	            return new rule\SumChildren();
	        case rule\Matchplay::className():
	            return new rule\Matchplay();
	        default:
	           return new self;
	    }
	}
	
	/**
	 * Get a list of classes in rule sub-namespace under self namespace.
	 */
	public static function getList() {
		$self = new \ReflectionClass(new Rule());
		$currns = $self->getNamespaceName();
		$subns = $currns.'\\rule';
		Yii::trace('rulens='.$subns.'.', 'Rule::getList');
		return [
			self::className() => Yii::t('golf', 'Standard'),
			rule\Copy::className() => Yii::t('golf', 'Copy'),
			rule\Rank::className() => Yii::t('golf', 'Rank'),
			rule\SumChildren::className() => Yii::t('golf', 'Sum Children Scores'),
			rule\Matchplay::className() => Yii::t('golf', 'Compute totals for matchplay'),
		];
	}


	public static function getTeamList() {
		return [
			null => Yii::t('golf', 'Single'),
			2 => '2 '.Yii::t('golf', 'Players'),
			3 => '3 '.Yii::t('golf', 'Players'),
			4 => '4 '.Yii::t('golf', 'Players'),
		];
	}

    /**
	 * Return points for stableford calculation
     */
	public static function getStablefordPoints() {
		return [
			-4 => 6, //sure
			-3 => 5,
			-2 => 4,
			-1 => 3,
			 0 => 2,
			 1 => 1,
			 2 => 0,
			 3 => 0,
		];
	}

    /**
	 * Return stableford points for supplied score relative to par.
	 *
	 * @param integer $score Score relative to par. Par is 0, bogey = 1, birdy = -1, etc.
	 *
	 * @return integer Stableford points for score according to point table supplied by getStablefordPoints().
     */
	public static function stablefordPoint($score) {
		$points = self::getStablefordPoints();
		return in_array($score, array_keys($points)) ? $points[$score] : 0;
	}
	
	/**
	 * Returns Stableford score for less than 18 holes
	 */
	public static function stablefordNine($score) {
		return 18 + array_sum($score);
	}
	
	/**
	 * Parameter string is name=value;name=value. If parameter name is repeated, values are collected in array of values.
	 *
	 * @return array (name, value) pairs where value is mixed scalar/array.
	 */
	public function getParameters() {
		return Yii::$app->golfleague->getParameters($this->parameters);
	}
	
	
	/**
	 * Apply rule
	 *
	 * Very Important: Please make sure that rule application is idempotent (i.e. rule can be applied over and over again and produces same result.)
	 */	
	public function apply($competition) { }
	
	/**
	 * Compute allowed array for team according to this rule.
	 * Specific team competition have specific handicap calculation rule.
	 */
	public function allowed($team, $tees) { }

	public function getRounding() {
		$r = 0;
		switch($this->destination_format) {
			case self::POINT_HALF:
			case self::POINT_DECIMAL1:
				$r = 1;
				break;
			case self::POINT_DECIMAL2:
				$r = 2;
				break;
			case self::POINT_FORMAT:
			case self::POINT_POSITIVE:
			default:
		}
		return $r;
	}
	
	public function formatPoints($points) {
		$r = $this->getRounding();
		$d = round($points, $r);
		if($this->destination_format == self::POINT_HALF) {
			if(($d - floor($d)) > 0)
				$d = floor($d).self::HALF_POINT;
		}
		return $d;
	}
	
	public function getDefaultMethodClass() {
		$classname = $this->defaultMethodName;
		$add = null;
		if($this->team_size > 1) {
			$add .= 'Team';
		}
		if($this->rule_type == Rule::TYPE_MATCHPLAY) {
			$add .= 'Match';
		}
		if($add) {
			$classname .= 'For'.$add;
		}
		return $classname;
	}

	
}
