<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Golfer */

$this->title = Yii::t('golf', 'Create {modelClass}', [
    'modelClass' => 'Golfer',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Golfers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="golfer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
