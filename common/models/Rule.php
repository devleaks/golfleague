<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;

/**
 * This is the model class for table "rules".
 * @property Point[] $points
 */
class Rule extends _Rule
{
	use Constant;

	/** */
	const TYPE_SCORE = 'SCORE';
	const TYPE_SCORE_NET = 'SCORE_NET';
	const TYPE_STABLEFORD = 'STABLEFORD';
	const TYPE_STABLEFORD_NET = 'STABLEFORD_NET';
	const TYPE_POINTS = 'POINTS';
	const TYPE_POSITION = 'POSITION';
	const TYPE_MATCHPLAY = 'MATCHPLAY';
	
	/** */
	const RULE_MORE = 'MORE';
	const RULE_LESS = 'LESS';
	
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
	 * Return stableford points for supplied score relative to par. Par = 0, bogey = 1, birdy = -1, etc.
     */
	public static function stablefordPoint($score) {
		if($score < 0)
			return abs($score) + 2;
		else if ($score > 1)
			return 0;
		return 2 - $score;
	}
	
	/**
	 * Returns Stableford score for less than 18 holes
	 */
	public static function stablefordNine($scores) {
		return 18 + array_sum($scores);
	}
	
	
	public function isStableford() {
		return $this->rule_type == self::TYPE_STABLEFORD || $this->rule_type == self::TYPE_STABLEFORD_NET;
	}

	public function isStrokeplay() {
		return $this->rule_type == self::TYPE_SCORE || $this->rule_type == self::TYPE_SCORE_NET;
	}

	public function isMatchplay() {
		return $this->rule_type == self::TYPE_MATCHPLAY;
	}

	public function isTeamplay() {
		return intval($this->team) > 1;
	}

}
