<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = Yii::t('giveaway', 'Create '.$model->competition_type);
$this->params['breadcrumbs'][] = ['label' => Yii::t('giveaway', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
