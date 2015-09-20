<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = 'Site Administration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2><?= Html::encode($this->title) ?></h2>

                <ul>
                    <li><a href="<?= Url::to(['/admin/league']) ?>">Leagues</a></li>
                    <li><a href="<?= Url::to(['/user/admin']) ?>">Users and Access</a></li>
                </ul>

            </div>
        </div>

    </div>
</div>
