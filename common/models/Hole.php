<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "holes".
 */
class Hole extends _Hole
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
	            [['par'], 'in','range' => ['3', '4', '5', '6']], // 7?
				[['position', 'si'], 'compare', 'compareValue' => 18, 'operator' => '<='],
				[['position', 'si'], 'compare', 'compareValue' => 0, 'operator' => '>'],
        	]
		);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golfleague', 'Hole'),
            'tees_id' => Yii::t('golfleague', 'Tees'),
            'position' => Yii::t('golfleague', 'Hole'),
            'par' => Yii::t('golfleague', 'Par'),
            'si' => Yii::t('golfleague', 'Stroke Index'),
            'length' => Yii::t('golfleague', 'Length'),
        ];
    }
}
