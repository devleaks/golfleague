<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use devleaks\golfleague\assets\AppAsset;
use devleaks\golfleague\components\MenuHelper;

/* @var $this \yii\web\View */
/* @var $content string */

$asset = AppAsset::register($this);

$is_admin = false;
if(isset(Yii::$app->user))
    if(isset(Yii::$app->user->identity))
        if(isset(Yii::$app->user->identity->role))
            $is_admin =  Yii::$app->user->identity->role == 'admin';

$golfleague_module = Yii::$app->getModule('golfleague');
$league_roles = $golfleague_module->league_roles;

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
            NavBar::begin([
                'brandLabel' => Html::img($asset->baseUrl . '/images/logo.png'),
                'brandUrl' => Yii::$app->homeUrl. 'golfleague',
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);


            $menus = array();

            $menus[] = ['label' => 'Site', 'url' => ['/site/index']];

            // admin stuff
            if($is_admin) {
                $menus[] = ['label' => Yii::t('golfleague', 'Site admin'), 'url' => ['/admin']];
            }

            // golf league stuff
            if(in_array(Yii::$app->user->identity->role, $league_roles) && isset(Yii::$app->user->identity)) {
                $menus[] = ['label' => Yii::t('golfleague', 'Golf League'), 'items' => MenuHelper::getMenu(Yii::$app->user->identity->role)];
            }

            if(!Yii::$app->user->isGuest) {
                $who = Yii::$app->user->identity->username;
                if (YII_DEBUG)
                    $who .= (isset(Yii::$app->user->identity->role) ? '/'.Yii::$app->user->identity->role : '/?');
            }

            $menus[] = Yii::$app->user->isGuest ?
                        ['label' => 'Login', 'url' => ['/user/security/login']] :
                        ['label' => 'Logout (' . $who . ')',
                            'url' => ['/user/security/logout'],
                            'linkOptions' => ['data-method' => 'post']];

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menus,
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'Golf League', 'url' => Yii::$app->homeUrl.'golfleague'],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>

            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::$app->session->getFlash('success'); ?>
                </div>
            <?php elseif (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger">
                    <?php echo Yii::$app->session->getFlash('error'); ?>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; Golf League <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
