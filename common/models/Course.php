<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\behaviors\MediaBehavior;

/**
 * This is the model class for table "courses".
 *
 */
class Course extends _Course
{
    public $facility_name;

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
    public function rules()
    {
        return array_merge(
			parent::rules(),
			[
	            [['facility_name'], 'in',
	                'range' => Facility::find()->select(['name'])->column(),
	                'message' => 'Facility "{value}" not found.'],
	            [['holes'], 'in','range' => ['18','9']],
        	]
		);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'Course'),
            'facility_id' => Yii::t('igolf', 'Facility'),
            'name' => Yii::t('igolf', 'Name'),
            'holes' => Yii::t('igolf', 'Holes'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }

    /**
     * Sets facility name.
     */
    public function filterFacility()
    {
        $this->facility_name = $this->getFacility()->name;
    }

    /**
     *  @return  array id,name pairs
     */
    public function getList($id = 0) {
        if($id !== 0)
            $models = Course::find()->where(['facility_id', $id])->asArray()->all();
        else
            $models = Course::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'name');
    }

    /**
     *  @return  array id,name pairs
     */
    public static function getCourseList($with_tees = false) {
        $models = Course::find()->all();
		$list = [];
		foreach($models as $model) {
			if($with_tees) {
				if($model->hasTees()) {
					$list[$model->id] = $model->facility->name. ', ' . $model->name; 
				}
			} else {
				$list[$model->id] = $model->facility->name. ', ' . $model->name; 
			}
		}
        return $list;
    }

    public function hasTees($gender = null) {
		return $gender ? $this->getTees()->andWhere(['gender' => $gender])->exists() : $this->getTees()->exists();
    }

    public function getTeesWithHoles() {
        $tees_with_holes = array();

        foreach($this->getTees()->each() as $tees)
            if($tees->hasHoles()/*!*/)
                $tees_with_holes[] = $tees;

        return $tees_with_holes;
    }

	/**
	 * Get name with facility name
	 *
	 * @return string Full course name
	 */
	public function getFullName() {
		if($this->facility)
			return $this->facility->name . ' » ' . $this->name;
		else
			return $this->name;
	}


}
