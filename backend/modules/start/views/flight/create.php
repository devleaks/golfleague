<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Flight */

$this->title = Yii::t('golf', 'Create {modelClass}', [
    'modelClass' => 'Flight',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Flights'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="flight-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
