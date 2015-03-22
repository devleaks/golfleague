<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'Web Site Administration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2><?= Html::encode($this->title) ?></h2>

                <ul>
                    <li><a href="<?= Url::to(['/user/admin']) ?>">Users and Access</a></li>
                    <li><a href="<?= Url::to(['/rbac/role/index']) ?>">Roles and Privileges</a></li>
                    <li><a href="<?= Url::to(['/rbac/menu/index']) ?>">Menus</a></li>
                </ul>

            </div>
        </div>

    </div>
</div>
