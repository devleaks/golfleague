<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "scorecards".
 */
class Scorecard extends _Scorecard
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
    public function rules()
    {
        return array_merge(
			parent::rules(),
			[
            	[['tees_id'], 'required'],
        	]
		);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'Scorecard'),
        	'registration_id' => Yii::t('igolf', 'Registration'),
            'competition_id' => Yii::t('igolf', 'Match'),
            'golfer_id' => Yii::t('igolf', 'Golfer'),
            'tees' => Yii::t('igolf', 'Tees'),
            'note' => Yii::t('igolf', 'Note'),
            'points' => Yii::t('igolf', 'Points'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }

	/**
	 * Creates Score entry for each hole of the scorecard.
	 */
	public function init2() {
		if($this->tees) {
			foreach($this->tees->getHoles()->each() as $hole) {
				$score = new Score([
					'scorecard_id' => $this->id,
					'hole_id' => $hole->id,
				]);
				$score->save();
			}
		}
	}

}
