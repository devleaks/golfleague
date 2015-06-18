<?php

use common\models\Practice;
use common\models\Course;
use kartik\widgets\DateTimePicker;
use kartik\widgets\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Practice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="practice-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'course_id')->dropDownList(Course::getList()$open, ['id'=>'course-id']) ?>

    <?= $form->field($model, 'tees_id')->widget(DepDrop::classname(), [
	    'options'=>['id'=>'tees-id'],
	    'pluginOptions'=>[
	        'depends'=>['course-id'],
	        'placeholder'=>'Select...',
	        'url'=>Url::to(['/golfer/practice/tees'])
	    ]
	]) ?>
	
    <?= $form->field($model, 'start_time')->widget(DateTimePicker::classname(), [
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii:ss',
                'todayHighlight' => true
            ]
        ]) ?>

	<?= $form->field($model, 'start_hole')->dropDownList(array(1 => '1', 10 => '10')) ?>

	<?= $form->field($model, 'holes')->dropDownList(array(18 => '18', 9 => '9')) ?>

    <?= $form->field($model, 'status')->dropDownList(Practice::getLocalizedConstants('STATUS_')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('igolf', 'Create') : Yii::t('igolf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
