<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;

/**
 * This is the model class for table "score".
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
            'id' => Yii::t('igolf', 'Practice'),
            'golfer_id' => Yii::t('igolf', 'Golfer'),
            'course_id' => Yii::t('igolf', 'Course'),
            'start_time' => Yii::t('igolf', 'Start Time'),
            'start_hole' => Yii::t('igolf', 'Start Hole'),
            'holes' => Yii::t('igolf', 'Holes'),
            'tees_id' => Yii::t('igolf', 'Tees'),
            'handicap' => Yii::t('igolf', 'Handicap'),
            'status' => Yii::t('igolf', 'Status'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'created_at' => Yii::t('igolf', 'Created At'),
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
				'status' => Scorecard::STATUS_CREATED,
			]);
			$scorecard->save();
			if($detailed) {
				$scorecard->makeScores();
			}
		}
		return $scorecard;
	}

}