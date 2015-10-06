<?php
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

	<link rel="apple-touch-icon" sizes="57x57" href="/golfleague/images/favicon/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/golfleague/images/favicon/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/golfleague/images/favicon/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/golfleague/images/favicon/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/golfleague/images/favicon/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/golfleague/images/favicon/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/golfleague/images/favicon/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/golfleague/images/favicon/apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/golfleague/images/favicon/apple-touch-icon-180x180.png">
	<link rel="icon" type="image/png" href="/golfleague/images/favicon/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/golfleague/images/favicon/android-chrome-192x192.png" sizes="192x192">
	<link rel="icon" type="image/png" href="/golfleague/images/favicon/favicon-96x96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="/golfleague/images/favicon/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="/golfleague/images/favicon/manifest.json">
	<link rel="shortcut icon" href="/golfleague/images/favicon/favicon.ico">
	<meta name="msapplication-TileColor" content="#00aba9">
	<meta name="msapplication-TileImage" content="/golfleague/images/favicon/mstile-144x144.png">
	<meta name="msapplication-config" content="/golfleague/images/favicon/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
	
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

				$user_menu = [];
				$user_menu[] = ['label' => Yii::t('golf', 'Profile'), 'url' => ['/user/settings']];
				$user_menu[] = ['label' => Yii::t('golf', 'Logout'), 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post']];

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
