<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;

/**
 * This is the model class for table "practice".
 */
class Practice extends _Practice
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
	public function getScorecard($detailed = false) {
		if(! $scorecard = $this->getScorecards()->one() ) { // Scorecard::findOne(['registration_id'=>$registration->id])
			$scorecard = new ScorecardForPractice([
				'scorecard_type' => Scorecard::TYPE_PRACTICE,
				'practice_id' => $this->id,
				'status' => Scorecard::STATUS_OPEN,
			]);
			$scorecard->save();
			if($detailed) {
				$scorecard->makeScores();
			}
		}
		return $scorecard;
	}

}