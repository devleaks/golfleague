<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;

/**
 * This is the model class for table "practice".
 */
class Practice extends base\Practice
{
	use Constant;

    /** Status */
    const STATUS_ACTIVE = 'ACTIVE';
    /** Status */
    const STATUS_CLOSED = 'CLOSED';
	
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
/* practice_id = 4, sc=19*/

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'Practice'),
            'golfer_id' => Yii::t('golf', 'Golfer'),
            'course_id' => Yii::t('golf', 'Course'),
            'start_time' => Yii::t('golf', 'Start Time'),
            'start_hole' => Yii::t('golf', 'Start Hole'),
            'holes' => Yii::t('golf', 'Holes'),
            'tees_id' => Yii::t('golf', 'Tees'),
            'handicap' => Yii::t('golf', 'Handicap'),
            'status' => Yii::t('golf', 'Status'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'created_at' => Yii::t('golf', 'Created At'),
        ];
    }

	/**
	 * Returns registration's associated scorecard. Creates one if none exists.
	 *
	 * @param boolean $detailed Whether to create hole detail score for scorecard
	 *
	 * return common\models\Scorecard
	 */
	public function makeScorecard($detailed = false) {
		$scorecard = null;
		if(! $scorecard = parent::getScorecard()->one() ) { // Scorecard::findOne(['registration_id'=>$registration->id])
			$scorecard = new Scorecard([
				'status' => Scorecard::STATUS_OPEN,
			]);
			$scorecard->save();
			$scorecard->refresh();
			$this->scorecard_id = $scorecard->id;
			$this->save();
			if($detailed) {
				$scorecard->makeScores();
			}
		}
		return $scorecard;
	}

	/**
	 * Returns registration's associated scorecard. Creates one if none exists.
	 *
	 * @param boolean $detailed Whether to create hole detail score for scorecard
	 *
	 * return common\models\Scorecard
	 */
	public function getScorecard($detailed = false) {
		if($scorecard = parent::getScorecard()->one()) {
			return $scorecard;
		} else {
			return $this->makeScorecard($detailed);
		}
	}

}