<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;

/**
 * This is the model class for table "tees".
 *
 */
class Tees extends _Tees
{
	use Constant;
	
    /** Competition is open for men only */
    const GENDER_GENTLEMEN = Golfer::GENDER_GENTLEMAN;
    /** Competition is open for women only */
    const GENDER_LADIES = Golfer::GENDER_LADY;
    /** Competition is open for order men and women */
    const GENDER_SENIOR = 'SENIOR';
    /** Competition is open for younger men and women */
    const GENDER_JUNIOR = 'JUNIOR';

	const CATEGORY_CHAMPIONSHIP = 'CHAMPIONSHIP';
	const CATEGORY_BACK = 'BACK';
	const CATEGORY_FRONT = 'FRONT';
	const CATEGORY_BEGIN = 'BEGIN';

	const TEE_FRONT = 'FRONT';
	const TEE_BACK  = 'BACK';	

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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'Tees'),
            'course_id' => Yii::t('igolf', 'Course'),
            'name' => Yii::t('igolf', 'Tees Set Name'),
            'holes' => Yii::t('igolf', 'Holes'),
            'front_back' => Yii::t('igolf', 'Front / Back'),
            'course_rating' => Yii::t('igolf', 'Course Rating'),
            'slope_rating' => Yii::t('igolf', 'Slope Rating'),
            'color' => Yii::t('igolf', 'Tees Color'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }

	public function hasHoles() {
		return $this->getHoles()->count() > 0;
	}

	public function getLabel($mode = 'name') {
		return '<span class="label" style="background-color: black;"><span class="glyphicon glyphicon-filter" style="color: '.$this->color.';"></span> '.($mode == 'name' ? $this->name : '').'</span>';
	}
}
