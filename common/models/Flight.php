<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "group where group_type = 'flight'".
 */
class Flight extends Group {
	const GROUP_TYPE = self::TYPE_FLIGHT;

    public static function defaultScope($query)
    {
		Yii::trace('Flight::defaultScope');
        $query->andWhere(['group_type' => self::TYPE_FLIGHT]);
    }

	public static function find()
    {
        return new GroupQuery(get_called_class(), ['type' => self::GROUP_TYPE]);
    }

    /**
     * @inheritdoc
     */
	public function beforeSave($insert)
	    {
	        $this->group_type = self::GROUP_TYPE;
	        return parent::beforeSave($insert);
	    }
	
    /**
     * Get a label for match made from competitor's name separated by separator
     */
	public function getLabel($separator = '/') {
		$names = '';
		foreach($this->getRegistrations()->each() as $registration) {
			if($registration->competition->isTeamCompetition())
				$names .= $registration->team->getLabel('-').$separator;
			else
				$names .= $registration->golfer->name.$separator;
		}
		return substr($names, 0, - strlen($separator));;
	}


	public function getMatches() { //@todo: rewritable with viaTable?
		return Match::find()->andWhere(['id' => GroupMember::find()->andWhere(['registration_id' => $this->getRegistrations()->select('id')->distinct()])->select('group_id')->distinct()]);
	}

}
