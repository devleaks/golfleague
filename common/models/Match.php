<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "group where group_type = 'match'".
 */
class Match extends Group {
	const GROUP_TYPE = self::TYPE_MATCH;

    public static function defaultScope($query)
    {
		Yii::trace('Match::defaultScope');
        $query->andWhere(['group_type' => self::TYPE_MATCH]);
    }

	public static function find()
    {
        return new GroupQuery(get_called_class(), ['type' => self::GROUP_TYPE]);
    }


	public function getPlayers() {
		$type = $this->getGroupMembers()->one()->object_type;
		return $type == GroupMember::TEAM ? $this->getTeams(true) : $this->getRegistrations();
	}

    /**
     * @inheritdoc
     */
	public function getTeams() {
		return Team::find()->andWhere(['id' => GroupMember::find()->andWhere(['group_id' => $this->id])->select('object_id')]);
	}

}
