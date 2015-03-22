<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Scorecard */

$this->title = Yii::t('igolf', 'Create {modelClass}', [
    'modelClass' => 'Scorecard',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Scorecards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scorecard-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
