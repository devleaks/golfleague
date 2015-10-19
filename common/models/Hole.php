<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\MediaBehavior;

/**
 * This is the model class for table "holes".
 */
class Hole extends base\Hole
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
            'id' => Yii::t('golf', 'Hole'),
            'tees_id' => Yii::t('golf', 'Tees'),
            'position' => Yii::t('golf', 'Hole'),
            'par' => Yii::t('golf', 'Par'),
            'si' => Yii::t('golf', 'Stroke Index'),
            'length' => Yii::t('golf', 'Length'),
        ];
    }

	public static function validNumber($max = 18) {
		$holes = [];
		for($i = 1; $i <= $max; $i++)
			$holes[] = $i;
		return $holes;
	}
	
	/**
	 *	Returns ActiveQuery of same hole from different tees.
	 */
	public function shareMedia() {
		return Hole::find()
			->andWhere(['position' => $this->position])
			->andWhere(['tees_id' => $this->tees->course->getTees()->select('id')])
		;
	}
}
