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


    /**
     * Get a label for match made from competitor's name separated by separator
     */
	public function getLabel($separator = '/') {
		$competition = $this->getCompetition();
		Yii::trace('entering for '.$this->id.'/'.$competition->id, 'Match::getLabel');
		if($competition->isTeamCompetition()) {
			$label = '';
			foreach($this->getTeams()->each() as $team) {
				Yii::trace('adding team'.$team->id.'/'.$team->getLabel(), 'Match::getLabel');
				$label .= $team->getLabel('-');
				$label .= $separator;
			}
			return substr($label, 0, - strlen($separator));;
		} else
			return parent::getLabel($separator);
	}

	/**
	 * Returns 2 opponents in a matchplay competition
	 */
	public function getOpponents() {
		return ($this->getGroupMembers()->count() > 2) ? $this->getTeams() : $this->getRegistrations();
	}
}
