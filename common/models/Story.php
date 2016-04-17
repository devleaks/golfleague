<?php

namespace common\models;

use common\behaviors\Constant;

use Yii;
use \common\models\base\Story as BaseStory;

/**
 * This is the model class for table "story".
 */
class Story extends BaseStory
{
	use Constant;

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    const TYPE_STORY = 'STORY';
    const TYPE_PAGE = 'PAGE';

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'presentation_id' => Yii::t('golf', 'Presentation'),
            'animation_id' => Yii::t('golf', 'Animation'),
        ]);
    }

	/**
	 * returns associative array of status, color for all possible status values
	 * Bootstrap colors are: default  primary  success  info  warning  danger
	 *
	 * @param $what Attribute to get color for.
	 *
	 * @return array()
	 */
	public static function getLabelColors($what) {
		$colors = [];
		switch($what) {
			case 'status':
				$colors = [
					self::STATUS_ACTIVE => 'success',
					self::STATUS_INACTIVE => 'warning',
				];
				break;
		}
		return $colors;
	}
}
