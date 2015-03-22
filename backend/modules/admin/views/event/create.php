<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Event */

$this->title = Yii::t('igolf', 'Create {modelClass}', [
    'modelClass' => 'Event',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
