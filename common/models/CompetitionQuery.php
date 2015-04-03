<?php

namespace common\models;

use yii\db\ActiveQuery;

class CompetitionQuery extends ActiveQuery
{
    public $type;

    public function prepare($builder)
    {
        if ($this->type !== null) {
            $this->andWhere(['type' => $this->type]);
        }
        return parent::prepare($builder);
    }

	public function open() {
		$now = date('Y-m-d H:i:s');
		return $this->andWhere(['status' => Competition::STATUS_OPEN])
					->andWhere(['>','registration_begin', $now])
					->andWhere(['<','registration_end', $now]);
	}


	public function status($statuses) {
		return $this->andWhere(['status' => $statuses]);
	}
}