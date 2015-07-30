<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
* This is the model class for table "start".
 */
class Start extends _Start
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
        	'competition_id' => Yii::t('golf', 'Competition ID'),
            'tees_id' => Yii::t('golf', 'Tees'),
            'gender' => Yii::t('golf', 'Gender'),
            'age_min' => Yii::t('golf', 'Age Min'),
            'age_max' => Yii::t('golf', 'Age Max'),
            'handicap_min' => Yii::t('golf', 'Handicap Min'),
            'handicap_max' => Yii::t('golf', 'Handicap Max'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
        ];
    }

	protected function genderOk($golfer) {
		return $this->gender ? ($this->gender === Competition::GENDER_BOTH) ||  ($this->gender === $golfer->gender) : true;
	}

	protected function handicapMinOk($golfer) {
		return $this->handicap_min ? ($this->handicap_min < $golfer->handicap) : true;
	}

	protected function handicapMaxOk($golfer) {
		return $this->handicap_max ? ($this->handicap_max > $golfer->handicap) : true;
	}

	protected function ageMinOk($golfer) {
		return $this->age_min ? ($this->age_min < $golfer->age()) : true;
	}

	protected function ageMaxOk($golfer) {
		return $this->age_max ? ($this->age_max > $golfer->age()) : true;
	}

	public function isOk($golfer) {
		return $this->genderOk($golfer)
					&& $this->handicapMinOk($golfer)
					&& $this->handicapMaxOk($golfer)
					&& $this->ageMinOk($golfer)
					&& $this->ageMaxOk($golfer);
	}

}