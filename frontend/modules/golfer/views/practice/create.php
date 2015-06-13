<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Practice */

$this->title = Yii::t('igolf', 'Create Practice');
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Practices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="practice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
