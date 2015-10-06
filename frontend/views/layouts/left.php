<?php

use common\models\User;
use yii\helpers\Url;

$role = User::getRole();

?><aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/uploads/profile/<?= Yii::$app->user->identity->username ?>.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
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
                        'label' => 'Competitions',
                        'icon' => 'fa fa-list',
                        'url' => '#',
                        'items' => [
                            ['label' => 'List', 'icon' => 'fa fa-trophy', 'url' => ['/calendar'],],
                        	['label' => 'Registrations', 'icon' => 'fa fa-legal', 'url' => ['/golfer/registration'],],
                            ['label' => 'Scores', 'icon' => 'fa fa-user', 'url' => ['/golfer/scorecard'],],
						],
					],
                    [
                        'label' => 'Calendar',
                        'icon' => 'fa fa-calendar',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Events', 'icon' => 'fa fa-calendar', 'url' => ['/calendar'],],
                            ['label' => 'Messages', 'icon' => 'fa fa-newspaper-o', 'url' => ['/news'],],
						],
					],
                    [
                        'label' => 'Profile',
                        'icon' => 'fa fa-server',
                        'url' => '#',
                        'items' => [
	                        ['label' => 'Practice Rounds', 'icon' => 'fa fa-trophy', 'url' => ['/golfer/practice'],],
                        	['label' => 'Settings', 'icon' => 'fa fa-cog', 'url' => ['/user/settings'],],
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
	                    	['label' => 'Backend', 'icon' => 'fa fa-heart', 'url' => ['/../igolf']],
		                    // ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
						],
					],		
                ],
            ]
        ) ?>

    </section>

</aside>
