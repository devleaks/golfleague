<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Competition;
use common\models\Course;
use common\models\Tees;

/* @var $this yii\web\View */
/* @var $model app\models\Tees */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tees-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'course_id')->dropDownList(Course::getList()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'par')->textInput(['maxlength' => 20]) ?>

	<?= $form->field($model, 'holes')->dropDownList(array(18 => '18', 9 => '9')) ?>

	<?= $form->field($model, 'gender')->dropDownList(['' => ''] + Competition::getLocalizedConstants('GENDER_')) ?>

	<?= $form->field($model, 'category')->dropDownList(['' => ''] + Tees::getLocalizedConstants('CATEGORY_')) ?>

	<?= $form->field($model, 'front_back')->dropDownList(['' => ''] + Tees::getLocalizedConstants('TEE_')) ?>

    <?= $form->field($model, 'course_rating')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'slope_rating')->textInput(['maxlength' => 20]) ?>


    <?= $form->field($model, 'color')->dropDownList(Yii::$app->params['tees_colors']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('golf', 'Create') : Yii::t('golf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>