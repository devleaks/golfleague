<?php

namespace common\models;

use common\behaviors\MediaBehavior;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "location".
 */
class Location extends base\Location
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
           'id' => Yii::t('golf', 'Location'),
            'name' => Yii::t('golf', 'Name'),
            'description' => Yii::t('golf', 'Description'),
            'address' => Yii::t('golf', 'Address'),
            'postcode' => Yii::t('golf', 'Postcode'),
            'city' => Yii::t('golf', 'City'),
            'country' => Yii::t('golf', 'Country'),
            'position' => Yii::t('golf', 'Position'),
            'status' => Yii::t('golf', 'Status'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'created_at' => Yii::t('golf', 'Created At'),
        ];
    }
}
