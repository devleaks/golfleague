<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "flights".
 *
 */
class Flight extends _Flight
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
            'id' => Yii::t('golf', 'Flight'),
            'position' => Yii::t('golf', 'Position'),
            'note' => Yii::t('golf', 'Note'),
        ];
    }

    /**
     * Unlink this flight from all registrations. Delete model after removing from registrations
     */
    public function cleanRegistrations($delete = false)
    {
        foreach($this->getRegistrations()->each() as $r) {
			$r->flight_id = null;
			$r->save();
		}
		if($delete)
			$this->delete();
    }

	public function getTeams() {
		return $this->hasMany(Team::className(), ['id' => 'team_id'])->viaTable('registration', ['flight_id' => 'id']);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
	public function getMatches() {
		return $this->hasMany(Match::className(), ['id' => 'match_id'])->viaTable('registration', ['flight_id' => 'id']);
	}


}
