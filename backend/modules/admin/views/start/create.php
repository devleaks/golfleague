<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Start */

$this->title = Yii::t('app', 'Create Start');
$this->params['breadcrumbs'][] = ['label' => $model->competition->name, 'url' => ['competition/view', 'id' => $model->competition_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="start-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
