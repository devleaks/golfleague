<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yii\web\IdentityInterface */

$this->title = Yii::t('golfleague', 'Registrations for ').$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golfleague', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['registration/competition', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-index">
    <h1>Competition: <?= $model->name ?></h1>

    <div class="col-lg-5">
        Golfers:
        <?php
        echo Html::textInput('search_avail', '', ['class' => 'golfer-search', 'data-target' => 'availables']) . '<br>';
        echo Html::listBox('golfers', '', $availables, [
            'id' => 'availables',
            'multiple' => true,
            'size' => 20,
            'style' => 'width:100%']);
        ?>
    </div>
    <div class="col-lg-1">
        &nbsp;<br><br>
        <?php
        echo Html::a('>>', '#', ['class' => 'btn btn-success', 'data-action' => 'register']) . '<br>';
        echo Html::a('<<', '#', ['class' => 'btn btn-success', 'data-action' => 'deregister']) . '<br>';
        ?>
    </div>
    <div class="col-lg-5">
        Registered:
        <?php
        echo Html::textInput('search_regs', '', ['class' => 'golfer-search', 'data-target' => 'registereds']) . '<br>';
        echo Html::listBox('golfers', '', $registereds, [
            'id' => 'registereds',
            'multiple' => true,
            'size' => 20,
            'style' => 'width:100%']);
        ?>
    </div>
</div>
<?php
$this->render('_bulkscript',['id'=>$model->id]);
