<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "competition_registration".
 *
 * @property integer $id
 * @property string $name
 * @property string $event_type
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $description
 * @property string $event_start
 * @property string $event_end
 * @property string $object_type
 * @property integer $object_id
 */
class CompetitionRegistration extends _CompetitionRegistration
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'ID'),
            'name' => Yii::t('igolf', 'Name'),
            'event_type' => Yii::t('igolf', 'Event Type'),
            'status' => Yii::t('igolf', 'Status'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
            'description' => Yii::t('igolf', 'Description'),
            'event_start' => Yii::t('igolf', 'Event Start'),
            'event_end' => Yii::t('igolf', 'Event End'),
            'object_type' => Yii::t('igolf', 'Object Type'),
            'object_id' => Yii::t('igolf', 'Object ID'),
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
