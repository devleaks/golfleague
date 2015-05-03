<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CompetitionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="competition-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'competition_type') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'parent_id') ?>

    <?php // echo $form->field($model, 'course_id') ?>

    <?php // echo $form->field($model, 'holes') ?>

    <?php // echo $form->field($model, 'rule_id') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'registration_begin') ?>

    <?php // echo $form->field($model, 'registration_end') ?>

    <?php // echo $form->field($model, 'handicap_min') ?>

    <?php // echo $form->field($model, 'handicap_max') ?>

    <?php // echo $form->field($model, 'age_min') ?>

    <?php // echo $form->field($model, 'age_max') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('igolf', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('igolf', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
