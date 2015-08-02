<?php

namespace common\components;

use Yii;
use common\models\AuthAssignment;
use yii\helpers\Url;

/**
 *  MenuHelper class builds menus for different roles.
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

        if(in_array($league_role, array('scorer', 'starter', 'manager', 'admin'))) {
            $menus[] = '<li class="dropdown-header">'.Yii::t('golf', 'Scoring').'</li>';
            $menus[] = ['label' => Yii::t('golf', 'Scorecards'), 'url' => '#'];
            $menus[] = ['label' => Yii::t('golf', 'Scores'), 'url' => '#'];
            $menus[] = ['label' => Yii::t('golf', 'Events'), 'url' => Url::to(['/admin/event'])];
        }

        if(in_array($league_role, array('starter', 'manager', 'admin'))) {
            $menus[] = '<li class="divider"></li>';
            $menus[] = '<li class="dropdown-header">'.Yii::t('golf', 'Planning').'</li>';
            $menus[] = ['label' => Yii::t('golf', 'Registrations'), 'url' => Url::to(['/start/registration'])];
            $menus[] = ['label' => Yii::t('golf', 'Flights'), 'url' => '#'];
        }
        
        if(in_array($league_role, array('manager', 'admin'))) {
            $menus[] = '<li class="divider"></li>';
            $menus[] = '<li class="dropdown-header">'.Yii::t('golf', 'Managing').'</li>';
            $menus[] = ['label' => Yii::t('golf', 'Courses'), 'url' => Url::to(['/admin/course'])];
            $menus[] = ['label' => Yii::t('golf', 'Competitions'), 'url' => Url::to(['/admin/competition'])];
            $menus[] = ['label' => Yii::t('golf', 'Rules'), 'url' => Url::to(['/admin/rule'])];
            $menus[] = ['label' => Yii::t('golf', 'Golfers'), 'url' => Url::to(['/admin/golfer'])];
            $menus[] = ['label' => Yii::t('golf', 'Messages'), 'url' => Url::to(['/admin/message'])];
        }

        return $menus;
    }

    static public function getDeveloperMenu($role = null) {
		$dev_menu = [];
    	$dev_menu[] = ['label' => Yii::t('golf', 'Gii'), 'url' => ['/gii'], 'linkOptions' => ['target'=>'_blank']];
		$dev_menu[] = ['label' => 'Back End', 'url' => ['/../igolf']];
		$dev_menu[] = ['label' => 'Front End', 'url' => ['/../golfleague']];
        return $dev_menu;
	}

    private function getSubMenus($parent) {
        $ret = [];

        $menus = Menu::find()
                    ->where(['parent' => $parent])
                    ->orderBy('order')
                    ->all();

        foreach ($menus as $menu) {
            $submenu = $this->getSubMenu($menu->id);
            $ret[] = ['label' => Yii::t('golf', $menu->name), $url => (count($submenu) == 0) ? Yii::$app->homeUrl.ltrim($menu->route,'/') : $submenu ];
        }

        return $ret;
    }

}