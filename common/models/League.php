<?php

namespace common\models;

use common\behaviors\MediaBehavior;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "league".
 */
class League extends base\League
{
	const MAX_IMAGES = 1;
	
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
	            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'League'),
            'name' => Yii::t('golf', 'Name'),
            'phone' => Yii::t('golf', 'Phone'),
            'email' => Yii::t('golf', 'Email'),
            'website' => Yii::t('golf', 'Website'),
            'units' => Yii::t('golf', 'Units'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
        ];
    }
}
