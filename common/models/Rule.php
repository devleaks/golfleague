<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rules".
 * @property Point[] $points
 */
class Rule extends _Rule
{
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
            'id' => Yii::t('golfleague', 'Rule'),
            'object_type' => Yii::t('golfleague', 'Object Type'),
            'rule_type' => Yii::t('golfleague', 'Rule Type'),
            'name' => Yii::t('golfleague', 'Name'),
            'description' => Yii::t('golfleague', 'Description'),
            'note' => Yii::t('golfleague', 'Note'),
            'created_at' => Yii::t('golfleague', 'Created At'),
            'updated_at' => Yii::t('golfleague', 'Updated At'),
            'competition_type' => Yii::t('golfleague', 'Competition Type'),
            'classname' => Yii::t('golfleague', 'Classname'),
        ];
    }

    /**
     * Return name,localized_value constant whose name starts with constant_prefix
     * 
     * @return array associative array with name, localized value pairs.
     */
    static public function getClassnames() {
        return [
			'RuleStandard',
			'TournamentSimpleSum',
		];
    }


}
