<?php

namespace common\components;

use Yii;
use common\models\AuthAssignment;
use yii\helpers\Url;

/**
 * Description of MenuHelper
 *
 * @author PierreM
 */
class MenuHelper extends \yii\base\Object
{
	const DEFAULT_ROLE = 'golfer';

	/**
	 * Returns "league" role of user, from roles attributiion. Default is golfer. Null if not loggued in.
	 */
    static public function getRole() {
		if(!Yii::$app->user->isGuest) {
			if($role = AuthAssignment::findOne(['user_id' => Yii::$app->user->identity->id]))
				if($key = array_search($role->item_name, Yii::$app->params['league_roles']))
					return $key;
			return self::DEFAULT_ROLE;
		}
		return null;
	}

    static public function getMenu($role = null) {
        /**
         * @todo : build menu from menu interface description and check with role privs.
         */

        $league_role = ($role === null) ? self::getRole() : $role;
 
        $menus = array();

        if(in_array($league_role, array('golfer', 'scorer', 'starter', 'manager', 'admin'))) {
			if (YII_DEBUG) $menus[] = ['label' => 'Front End', 'url' => ['/../golfleague']];
        }

        if(in_array($league_role, array('scorer', 'starter', 'manager', 'admin'))) {
            $menus[] = '<li class="divider"></li>';
            $menus[] = '<li class="dropdown-header">'.Yii::t('golfleague', 'Scoring').'</li>';
            $menus[] = ['label' => Yii::t('golfleague', 'Scorecards'), 'url' => '#'];
            $menus[] = ['label' => Yii::t('golfleague', 'Scores'), 'url' => '#'];
            $menus[] = ['label' => Yii::t('golfleague', 'Events'), 'url' => Url::to(['/admin/event'])];
        }

        if(in_array($league_role, array('starter', 'manager', 'admin'))) {
            $menus[] = '<li class="divider"></li>';
            $menus[] = '<li class="dropdown-header">'.Yii::t('golfleague', 'Planning').'</li>';
            $menus[] = ['label' => Yii::t('golfleague', 'Registrations'), 'url' => Url::to(['/starter/registration'])];
            $menus[] = ['label' => Yii::t('golfleague', 'Flights'), 'url' => '#'];
        }
        
        if(in_array($league_role, array('manager', 'admin'))) {
            $menus[] = '<li class="divider"></li>';
            $menus[] = '<li class="dropdown-header">'.Yii::t('golfleague', 'Managing').'</li>';
            $menus[] = ['label' => Yii::t('golfleague', 'Courses'), 'url' => Url::to(['/admin/course'])];
            $menus[] = ['label' => Yii::t('golfleague', 'Competitions'), 'url' => Url::to(['/admin/competition'])];
            $menus[] = ['label' => Yii::t('golfleague', 'Rules'), 'url' => Url::to(['/admin/rule'])];
            $menus[] = ['label' => Yii::t('golfleague', 'Golfers'), 'url' => Url::to(['/admin/golfer'])];
            $menus[] = ['label' => Yii::t('golfleague', 'Messages'), 'url' => Url::to(['/admin/message'])];
        }

        return $menus;
    }

    private function getSubMenus($parent) {
        $ret = [];

        $menus = Menu::find()
                    ->where(['parent' => $parent])
                    ->orderBy('order')
                    ->all();

        foreach ($menus as $menu) {
            $submenu = $this->getSubMenu($menu->id);
            $ret[] = ['label' => Yii::t('golfleague', $menu->name), $url => (count($submenu) == 0) ? Yii::$app->homeUrl.ltrim($menu->route,'/') : $submenu ];
        }

        return $ret;
    }

}