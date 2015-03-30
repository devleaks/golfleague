<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Message $model
 */

$this->title = Yii::t('igolf', 'Create {modelClass}', [
    'modelClass' => 'Message',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
