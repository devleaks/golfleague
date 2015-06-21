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
            'scorecard_id' => Yii::t('igolf', 'Scorecard'),
            'hole_id' => Yii::t('igolf', 'Hole'),
            'score' => Yii::t('igolf', 'Score'),
            'putts' => Yii::t('igolf', 'Putts'),
            'penalty' => Yii::t('igolf', 'Penalty'),
            'sand' => Yii::t('igolf', 'Sand'),
            'note' => Yii::t('igolf', 'Note'),
            'drive' => Yii::t('igolf', 'Drive'),
            'regulation' => Yii::t('igolf', 'Regulation'),
            'approach' => Yii::t('igolf', 'Approach'),
            'putt' => Yii::t('igolf', 'First Putt'),
            'putt_length' => Yii::t('igolf', 'First Putt Length'),
            'putt2' => Yii::t('igolf', 'Second Putt'),
            'putt2_length' => Yii::t('igolf', 'Seccond Putt Length'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }
}
