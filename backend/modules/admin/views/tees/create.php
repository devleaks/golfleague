<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tees */

$this->title = Yii::t('igolf', 'Create {modelClass}', [
    'modelClass' => 'Tees',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Tees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tees-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
