<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Course;

/* @var $this yii\web\View */
/* @var $model app\models\Tees */
/* @var $form yii\widgets\ActiveForm */


$golfleague_module = Yii::$app->getModule('golfleague');
$tees_colors = $golfleague_module->tees_colors;

?>

<div class="tees-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'course_id')->dropDownList(Course::getList()/*, ['disabled' => 'true']*/) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'color')->dropDownList($tees_colors) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('igolf', 'Create') : Yii::t('igolf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>