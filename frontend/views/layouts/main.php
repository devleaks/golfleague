<?php
use common\components\MenuHelper;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */
if(isset(Yii::$app->params['BootswatchTheme']))
	raoul2000\bootswatch\BootswatchAsset::$theme = Yii::$app->params['BootswatchTheme'];

$asset = AppAsset::register($this);

$role = MenuHelper::getRole();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode(Yii::$app->name) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
			$name = Yii::$app->name . (YII_ENV_DEV ? '-DEV' : '') . (YII_DEBUG ? '-DEBUG' : '');
            NavBar::begin([
                'brandLabel' => Html::img($asset->baseUrl . '/images/logo.png', ['width'=>216, 'height'=>46, 'title' => $name]),
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

            $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
            ];

            // golf league stuff

            if(!Yii::$app->user->isGuest) {
                $who = Yii::$app->user->identity->username;

				if(YII_ENV == 'dev')
					$menuItems[] = ['label' => Yii::t('igolf', 'Development'), 'items' => MenuHelper::getDeveloperMenu($role)];

				$user_menu = [];
				$user_menu[] = ['label' => Yii::t('igolf', 'Profile'), 'url' => ['/user/settings']];
				$user_menu[] = ['label' => Yii::t('igolf', 'Logout'), 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post']];

            	$menuItems[] = ['label' => $who, 'items' => $user_menu];
            } else {
                $menuItems[] = ['label' => 'Signup', 'url' => ['/user/register']];
            	$menuItems[] = ['label' => 'Login', 'url' => ['/user/security/login']];
			}

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => ['label' => Yii::$app->name, 'url' => Yii::$app->homeUrl],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; <?= Yii::$app->name . ' ' . date('Y') ?>
			<small><?php $apphomedir = Yii::getAlias('@app'); echo ' — Version '.`cd $apphomedir ; git describe --tags`;
				if(YII_DEBUG) {
					echo ' — Last commit: '.`git log -1 --format=%cd --relative-date`;
					echo ' — '.`hostname`;
					echo ' — '.Yii::$app->getDb()->dsn;
				}
			?></small></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
