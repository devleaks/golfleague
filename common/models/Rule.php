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
	 * Return valid classnames for computation
     */
    static public function getClassnames() {
        return [
			'RuleStandard',
			'TournamentSimpleSum',
		];
    }


}
