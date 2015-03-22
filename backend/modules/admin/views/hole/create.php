<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Hole */

$this->title = Yii::t('igolf', 'Create {modelClass}', [
    'modelClass' => 'Hole',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Holes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hole-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
