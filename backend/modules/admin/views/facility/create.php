<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Facility */

$this->title = Yii::t('igolf', 'Create {modelClass}', [
    'modelClass' => 'Facility',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Facilities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="facility-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
