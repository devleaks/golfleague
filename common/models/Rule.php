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
            'id' => Yii::t('igolf', 'Rule'),
            'object_type' => Yii::t('igolf', 'Object Type'),
            'rule_type' => Yii::t('igolf', 'Rule Type'),
            'name' => Yii::t('igolf', 'Name'),
            'description' => Yii::t('igolf', 'Description'),
            'note' => Yii::t('igolf', 'Note'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'competition_type' => Yii::t('igolf', 'Competition Type'),
            'classname' => Yii::t('igolf', 'Classname'),
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
