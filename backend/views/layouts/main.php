<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use backend\assets\AppAsset;
use common\components\MenuHelper;

/* @var $this \yii\web\View */
/* @var $content string */

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
    <title><?= Html::encode($this->title) ?></title>
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

            $menus = array();

            $menus[] = ['label' => Yii::t('golf', 'Home'), 'url' => ['/site/index']];

            // admin stuff
            if(in_array($role, ['admin', 'super'])) {
                $menus[] = ['label' => Yii::t('golf', 'Site Admin'), 'url' => ['/admin']];
            }

            // golf league stuff
            if($role) {
                $menus[] = ['label' => Yii::t('golf', 'Golf League'), 'items' => MenuHelper::getMenu($role)];
            }

            if(!Yii::$app->user->isGuest) {
                $who = Yii::$app->user->identity->username;
                if (YII_DEBUG)
                    $who .= ($role ? '/'.$role : '/?');

				if(YII_ENV == 'dev')
					$menus[] = ['label' => Yii::t('golf', 'Development'), 'items' => MenuHelper::getDeveloperMenu($role)];

				$menus[] = ['label' => Yii::t('golf', 'Help'), 'url' => ['/site/help']];

				$user_menu = [];
				$user_menu[] = ['label' => Yii::t('golf', 'Profile'), 'url' => ['/user/settings']];
				$user_menu[] = ['label' => Yii::t('golf', 'Logout'), 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post']];

            	$menus[] = ['label' => $who, 'items' => $user_menu];
            } else
            	$menus[] = ['label' => 'Login', 'url' => ['/user/security/login']];

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menus,
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => Yii::$app->name, 'url' => Yii::$app->homeUrl],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>

			<?php foreach(array('success', 'error', 'danger', 'warning', 'info') as $category): ?>
				<?php if (Yii::$app->session->hasFlash($category)): ?>
		                <div class="alert alert-<?= $category == 'error' ? 'danger' : $category ?>">
							<a href="#" class="close" data-dismiss="alert">&times;</a>
		                    <?= Yii::$app->session->getFlash($category) ?>
		                </div>
				<?php endif; ?>
			<?php endforeach; ?>

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
