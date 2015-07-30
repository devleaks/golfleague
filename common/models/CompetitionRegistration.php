<?php

namespace common\models;

use Yii;

/**
 * This is the model class for view "competition_registration".
 *
 */
class CompetitionRegistration extends _CompetitionRegistration
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'name' => Yii::t('golf', 'Name'),
            'event_type' => Yii::t('golf', 'Event Type'),
            'status' => Yii::t('golf', 'Status'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'description' => Yii::t('golf', 'Description'),
            'event_start' => Yii::t('golf', 'Event Start'),
            'event_end' => Yii::t('golf', 'Event End'),
            'object_type' => Yii::t('golf', 'Object Type'),
            'object_id' => Yii::t('golf', 'Object ID'),
        ];
    }

	public function getColor() {
		switch($this->event_type) {
			case Event::TYPE_COMPETITION: return 'info'; break;
			case Event::TYPE_REGISTRATION: return 'info'; break;
			case Event::TYPE_DINNER: return 'warning'; break;
			case Event::TYPE_CEREMONY: return 'danger'; break;
			default:
				return 'success';
		}
	}
}
