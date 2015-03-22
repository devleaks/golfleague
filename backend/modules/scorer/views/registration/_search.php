<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RegistrationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="registration-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'competition_id') ?>

    <?= $form->field($model, 'golfer_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'flight_id') ?>

    <?php // echo $form->field($model, 'tees') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'score') ?>

    <?php // echo $form->field($model, 'points') ?>

    <?php // echo $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'team_id') ?>

    <?php // echo $form->field($model, 'score_net') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('golfleague', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('golfleague', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
