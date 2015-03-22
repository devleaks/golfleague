<?php

use yii\helpers\Html;
use mdm\admin\models\Menu;

/* @var $content string */


$menus = Menu::find()
    ->where(['parent' => 8])
    ->orderBy('order')
    ->all();

$activeMenu = Yii::$app->controller->id;

?>
<?php $this->beginContent('@backend/views/layouts/main.php'); ?>
<div class="row">
    <div class="col-md-3 col-sm-4">
        <div class="list-group">
             <?php
           foreach ($menus as $id => $menu) {
                $label1 = Yii::t('igolf', $menu->name);
                $label = '<i class="glyphicon glyphicon-chevron-right"></i>' . Html::encode($label1);
                echo Html::a($label1, Yii::$app->homeUrl.ltrim ($menu->route,'/'), [
                    'class' => strpos($menu->route, $activeMenu) > 0 ? 'list-group-item active' : 'list-group-item',
                ]);
            }
            ?>
        </div>
    </div>
    <div class="col-md-9 col-sm-8">
        <?= $content ?>
    </div>
</div>
<?php $this->endContent(); ?>