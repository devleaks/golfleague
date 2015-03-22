<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ScoreSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="score-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'scorecard_id') ?>

    <?= $form->field($model, 'hole_id') ?>

    <?= $form->field($model, 'score') ?>

    <?= $form->field($model, 'putts') ?>

    <?= $form->field($model, 'penalty') ?>

    <?php // echo $form->field($model, 'sand') ?>

    <?php // echo $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'drive') ?>

    <?php // echo $form->field($model, 'regulation') ?>

    <?php // echo $form->field($model, 'approach') ?>

    <?php // echo $form->field($model, 'putt') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('igolf', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('igolf', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
