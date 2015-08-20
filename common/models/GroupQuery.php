<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 *	ActiveQuery particularisation for groups
 */
class GroupQuery extends ActiveQuery
{
    public $type;

    public function prepare($builder)
    {
        if ($this->type !== null) {
            $this->andWhere(['group_type' => $this->type]);
			Yii::trace($this->type, 'GroupQuery::prepare');
        }
        return parent::prepare($builder);
    }

}