<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = Yii::t('golfleague', 'Create {modelClass}', [
    'modelClass' => 'Competition',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('golfleague', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
