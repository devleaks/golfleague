<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Message */

$this->title = Yii::t('igolf', 'Create {modelClass}', [
    'modelClass' => 'Message',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
