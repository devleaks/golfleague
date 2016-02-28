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

	public $lat;
	public $lon;
	public $zoom;
	
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
    public function rules()
    {
        return array_merge(
			parent::rules(),
			[
				[['lat', 'lon', 'zoom'], 'safe'],
        	]
		);
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

            'lat' => Yii::t('golf', 'Latitude'),
            'lon' => Yii::t('golf', 'Longitude'),
            'zoom' => Yii::t('golf', 'Zoom'),
        ];
    }

	public function save($runValidation = true, $attributeNames = NULL) {
		$this->position = implode(';', [$this->lat, $this->lon, $this->zoom]);
		return parent::save($runValidation, $attributeNames);		
	}

	public function parseLatLon() {
		$arr = explode(';', $this->position);
		$this->lat = $arr[0];
		$this->lon = $arr[1];
		$this->zoom = $arr[2];
	}
}
