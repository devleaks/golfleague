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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'Rule'),
	        'name' => Yii::t('igolf', 'Name'),
	        'description' => Yii::t('igolf', 'Description'),
	        'competition_type' => Yii::t('igolf', 'Competition Type'),
	        'object_type' => Yii::t('igolf', 'Object Type'),
	        'rule_type' => Yii::t('igolf', 'Rule Type'),
	        'team' => Yii::t('igolf', 'Team'),
	        'note' => Yii::t('igolf', 'Note'),
	        'classname' => Yii::t('igolf', 'Classname'),
	        'created_at' => Yii::t('igolf', 'Created At'),
	        'updated_at' => Yii::t('igolf', 'Updated At'),
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
	            [['source_direction'], 'in', 'range' => array_keys(Scorecard::getConstants('DIRECTION_'))],
            	[['source_type', 'destination_type'], 'in', 'range' => array_keys(Scorecard::getConstants('SCORE_'))],
	            [['status'], 'in', 'range' => array_keys(self::getConstants('STATUS_'))],
        	]
		);
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
			self::className() => Yii::t('igolf', 'Standard'),
			rule\Copy::className() => Yii::t('igolf', 'Copy'),
			rule\Rank::className() => Yii::t('igolf', 'Rank'),
			rule\SumChildren::className() => Yii::t('igolf', 'Sum Children Scores'),
		];
	}


	public static function getTeamList() {
		return [
			null => Yii::t('igolf', 'Single / Individual'),
			2 => '2 '.Yii::t('igolf', 'Players'),
			3 => '3 '.Yii::t('igolf', 'Players'),
			4 => '4 '.Yii::t('igolf', 'Players'),
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
		return in_array($score, array_keys($point)) ? $points[$score] : 0;
	}
	
	/**
	 * Returns Stableford score for less than 18 holes
	 */
	public static function stablefordNine($scores) {
		return 18 + array_sum($scores);
	}
	
	
	public function isStableford() {
		return $this->source_type == Scorecard::SCORE_STABLEFORD || $this->source_type == Scorecard::SCORE_STABLEFORD_NET;
	}

	public function isStrokeplay() {
		return $this->source_type == Scorecard::SCORE_SCORE || $this->source_type == Scorecard::SCORE_SCORE_NET;
	}

	public function isMatchplay() {
		return $this->source_type == Scorecard::SCORE_POINTS;
	}

	public function isTeamplay() {
		return intval($this->team) > 1;
	}
	
	/**
	 * Parameter string is name=value;name=value
	 */
	public function getParameters() {
		if($this->parameters) {
		  # result array
		  $arr = array();

		  # split on outer delimiter
		  $pairs = explode(';', $str);

		  # loop through each pair
		  foreach ($pairs as $i) {
		    # split into name and value
		    list($name,$value) = explode('=', $i, 2);

		    # if name already exists
		    if( isset($arr[$name]) ) {
		      # stick multiple values into an array
		      if( is_array($arr[$name]) ) {
		        $arr[$name][] = $value;
		      }
		      else {
		        $arr[$name] = array($arr[$name], $value);
		      }
		    }
		    # otherwise, simply stick it in a scalar
		    else {
		      $arr[$name] = $value;
		    }
		  }

		  # return result array
		  return $arr;
		}
		return [];
	}
	
	
	/**
	 * Apply rule
	 */	
	public function apply($competition) { }
	
	/**
	 * Compute allowed array for team according to this rule.
	 */
	public function allowed($team, $tees) { }

}
