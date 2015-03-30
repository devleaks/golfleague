<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Message $model
 */

$this->title = Yii::t('igolf', 'Update {modelClass}: ', [
    'modelClass' => 'Message',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('igolf', 'Update');
?>
<div class="message-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
