<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\search\Story $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="story-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'parent_id') ?>

    <?= $form->field($model, 'story_type') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'header') ?>

    <?php // echo $form->field($model, 'body') ?>

    <?php // echo $form->field($model, 'presentation_id') ?>

    <?php // echo $form->field($model, 'animation_id') ?>

    <?php // echo $form->field($model, 'animation_parameters') ?>

    <?php // echo $form->field($model, 'animation_data') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('golf', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('golf', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
