<?php

use yii\helpers\Url;
use common\models\League;

$role = Yii::$app->user->identity->role;
if($league = $league = League::findOne(Yii::$app->user->identity->league_id)) {	
	$role .= ' '.$league->name ;
}

?><aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/uploads/profile/<?= Yii::$app->user->identity->username ?>.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online - <?= $role ?></a>
            </div>
        </div>

        <!-- search form -->
        <form action="<?= Url::to(['/site/search']) ?>" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>

        <!-- /.search form -->
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    [
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
					],
                    [
                        'label' => 'Prepare',
                        'icon' => 'fa fa-pencil-square',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Competitions', 'icon' => 'fa fa-trophy', 'url' => ['/start/competition'],],
						],
					],
                    [
                        'label' => 'Report',
                        'icon' => 'fa fa-server',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Competitions', 'icon' => 'fa fa-trophy', 'url' => ['/score/competition'],],
						],
					],
                    [
                        'label' => 'Site',
                        'icon' => 'fa fa-dashboard',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Leagues', 'icon' => 'fa fa-trophy', 'url' => ['/admin/league'],],
                        	['label' => 'Users', 'icon' => 'fa fa-user', 'url' => ['/admin/user'],],
	                        ['label' => 'Accounts', 'icon' => 'fa fa-user', 'url' => ['/user/admin'],],
	                        ['label' => 'Backup', 'icon' => 'fa fa-download', 'url' => ['/admin/backup'],],

						],
					],
			        [
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
					],		
                ],
            ]
        ) ?>

    </section>

</aside>
