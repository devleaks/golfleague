<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Location $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="location-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

	    'model' => $model,
	    'form' => $form,
	    'columns' => 1,
	    'attributes' => [

			'name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Name...', 'maxlength'=>80]], 

			'description'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Description...', 'maxlength'=>160]], 

			'address'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Address...', 'maxlength'=>160]], 

			'postcode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Postcode...', 'maxlength'=>40]], 

			'city'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter City...', 'maxlength'=>80]], 

			'country'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Country...', 'maxlength'=>40]], 

			'position'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Position...', 'maxlength'=>160]], 

			'status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status...', 'maxlength'=>20]], 

	    ]
    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

    ActiveForm::end(); ?>

</div>
