<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\Constant;

/**
 * This is the model class for table "score".
 */
class Score extends _Score
{
	use Constant;
	
	/** Scorecard statuses */
	const STATUS_OPEN 		= 'OPEN';
	const STATUS_CLOSED		= 'CLOSED';
	const STATUS_DISQUAL	= 'DISQUAL';
	
	const TARGET_LL = '1';
	const TARGET_LC = '2';
	const TARGET_LR = '3';
	const TARGET_CL = '4';
	const TARGET_CC = '5';
	const TARGET_CR = '6';
	const TARGET_SL = '7';
	const TARGET_SC = '8';
	const TARGET_SR = '9';
	const TARGET_WATER = 'W';
	const TARGET_BUNKER = 'B';
	
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
            'scorecard_id' => Yii::t('golf', 'Scorecard'),
            'hole_id' => Yii::t('golf', 'Hole'),
            'score' => Yii::t('golf', 'Score'),
            'putts' => Yii::t('golf', 'Putts'),
            'penalty' => Yii::t('golf', 'Penalty'),
            'sand' => Yii::t('golf', 'Sand'),
            'note' => Yii::t('golf', 'Note'),
            'drive' => Yii::t('golf', 'Drive'),
            'regulation' => Yii::t('golf', 'Regulation'),
            'approach' => Yii::t('golf', 'Approach'),
            'putt' => Yii::t('golf', 'First Putt'),
            'putt_length' => Yii::t('golf', 'First Putt Length'),
            'putt2' => Yii::t('golf', 'Second Putt'),
            'putt2_length' => Yii::t('golf', 'Seccond Putt Length'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
        ];
    }
}
