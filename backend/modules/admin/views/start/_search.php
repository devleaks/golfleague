<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\StartSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="start-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'gender') ?>

    <?= $form->field($model, 'age_min') ?>

    <?= $form->field($model, 'age_max') ?>

    <?= $form->field($model, 'handicap_min') ?>

    <?php // echo $form->field($model, 'handicap_max') ?>

    <?php // echo $form->field($model, 'tees_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'competition_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
