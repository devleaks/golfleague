<?php

use common\models\User;
use common\models\League;

use yii\helpers\Url;

$display_role = Yii::$app->user->identity->role;
if(Yii::$app->user->identity->league) {	
	$display_role .= ' '.Yii::$app->user->identity->league->name ;
}

$items = [];

if(Yii::$app->user->identity->isA([User::ROLE_ADMIN, User::ROLE_MANAGER])) {
	$items[] = [
        'label' => 'Create',
        'icon' => 'fa fa-list',
        'url' => '#',
        'items' => [
            ['label' => 'Competitions', 'icon' => 'fa fa-trophy', 'url' => ['/admin/competition'],],
        	['label' => 'Rules', 'icon' => 'fa fa-legal', 'url' => ['/admin/rule'],],
            ['label' => 'Golfers', 'icon' => 'fa fa-user', 'url' => ['/admin/golfer'],],
            ['label' => 'Golf courses', 'icon' => 'fa fa-flag', 'url' => ['/admin/facility'],],
            ['label' => 'Events', 'icon' => 'fa fa-calendar', 'url' => ['/admin/event'],],
            ['label' => 'Messages', 'icon' => 'fa fa-newspaper-o', 'url' => ['/admin/message'],],
		],
	];
}
if(Yii::$app->user->identity->isA([User::ROLE_ADMIN, User::ROLE_MANAGER, User::ROLE_STARTER])) {
	$items[] = [
        'label' => 'Prepare',
        'icon' => 'fa fa-pencil-square',
        'url' => '#',
        'items' => [
            ['label' => 'Competitions', 'icon' => 'fa fa-trophy', 'url' => ['/start/competition'],],
		],
	];
}
if(Yii::$app->user->identity->isA([User::ROLE_ADMIN, User::ROLE_MANAGER, User::ROLE_SCORER])) {
	$items[] = [
        'label' => 'Report',
        'icon' => 'fa fa-server',
        'url' => '#',
        'items' => [
            ['label' => 'Competitions', 'icon' => 'fa fa-trophy', 'url' => ['/score/competition'],],
		],
	];
}
if(in_array(Yii::$app->user->identity->role, [User::ROLE_ADMIN, User::ROLE_MANAGER])) {
	$subitems = [];
	if(Yii::$app->user->identity->isA([User::ROLE_ADMIN])) {
		$subitems[] = ['label' => 'Leagues', 'icon' => 'fa fa-trophy', 'url' => ['/admin/league']];
	}
	$subitems[] = ['label' => 'Users', 'icon' => 'fa fa-user', 'url' => ['/admin/user']];
	if(Yii::$app->user->identity->isA([User::ROLE_ADMIN])) {
		$subitems[] = ['label' => 'Accounts', 'icon' => 'fa fa-user', 'url' => ['/user/admin']];
		$subitems[] = ['label' => 'Backup', 'icon' => 'fa fa-download', 'url' => ['/admin/backup']];
	}
	$items[] = [
        'label' => 'Site',
        'icon' => 'fa fa-dashboard',
        'url' => '#',
        'items' => $subitems,
	];
}
if(defined('YII_DEBUG')) {
	$items[] = [
        'label' => 'Development',
        'icon' => 'fa fa-cog',
        'url' => '#',
        'items' => [
            // ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
        	['label' => 'Debug', 'icon' => 'fa fa-bug', 'url' => ['/debug']],
            ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
        	['label' => 'Frontend', 'icon' => 'fa fa-heart', 'url' => ['/../golfleague']],
            // ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
		],
	];
}

?><aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Yii::$app->user->identity->getProfilePicture() ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online - <?= $display_role ?></a>
            </div>
        </div>

        <!-- search form -->
        <form action="<?= Url::to(['/site/search']) ?>" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>

        <!-- /.search form -->
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => $items,
            ]
        ) ?>

    </section>

</aside>
