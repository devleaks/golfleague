<?php

namespace devleaks\golfleague\components;

use Yii;
use yii\base\Behavior;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Golfer;
use common\models\Registration;

/**
 * This is the model class for behavior "competition". Competition could be a base class for Season, Tournament, and Match.
 *
 */
class CompetitionSearch extends Behavior
{

	/**
	 * List only competitions that are still open and in the future.
	 * @param  [type] $query [description]
	 * @param  [type] $date  [description]
	 * @return [type]        [description]
	 */
	public static function open($query, $date=date('Y-m-d H:i:s'))
    {
        $query->andWhere(['>','limit_date',$date])
        	  ->andWhere(['status', Competition::OPEN])
        ;
    }

    /**
     * List competitions if golfer allowed to play
     * @param  [type] $query [description]
     * @param  [type] $level [description]
     * @return [type]        [description]
     */
	public static function allowed($query, $level=0)
    {
        $query->andWhere(['>','level',$level]);
    }


}
