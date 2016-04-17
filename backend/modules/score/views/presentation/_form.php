<?php

use common\models\Presentation;

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use insolita\iconpicker\Iconpicker;
use kartik\widgets\ColorInput

/**
 * @var yii\web\View $this
 * @var common\models\Presentation $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="presentation-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

'name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Name...', 'maxlength'=>80]],

'display_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Display Name...', 'maxlength'=>80]],

'description'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Description...', 'maxlength'=>2000]],

'icon'=>['type'=> Form::INPUT_WIDGET,
		 'widgetClass' => Iconpicker::className(),
		 'options' => [
			'rows' => 6,
			'columns' => 8,
			'iconset'=> 'fontawesome',
			'options'=>['placeholder'=>'Enter Icon...', 'maxlength'=>40]
		 ]
],

'bgcolor'=>['type'=> Form::INPUT_WIDGET,
		 'widgetClass' => ColorInput::className(),
		 'options' => [
			'options'=>['maxlength'=>40]
		 ]
],

'fgcolor'=>['type'=> Form::INPUT_WIDGET,
		 'widgetClass' => ColorInput::className(),
		 'options' => [
			'options'=>['maxlength'=>40]
		 ]
],

'font'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Font...', 'maxlength'=>200]],

'status'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'options'=>['placeholder'=>'Enter Status...', 'maxlength'=>40], 'items' => Presentation::getLocalizedConstants('STATUS_')],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
