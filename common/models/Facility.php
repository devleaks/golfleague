<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\MediaBehavior;

/**
 * This is the model class for table "facilities".
 */
class Facility extends _Facility
{
	const MAX_IMAGES = 3;
	
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'Facility'),
            'name' => Yii::t('igolf', 'Facility Name'),
            'phone' => Yii::t('igolf', 'Phone'),
            'email' => Yii::t('igolf', 'Email'),
            'website' => Yii::t('igolf', 'Website'),
            'units' => Yii::t('igolf', 'Units'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'media' => Yii::t('igolf', 'Pictures'),
        ];
    }
}
