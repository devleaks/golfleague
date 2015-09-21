<?php

use common\models\Facility;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\League $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="league-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Name...', 'maxlength'=>80]], 

'website'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Website...', 'maxlength'=>255]], 

'email'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Email...', 'maxlength'=>80]], 

'phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Phone...', 'maxlength'=>20]], 

'units'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items' => Facility::getLocalizedConstants('UNITS_'), 'options'=>['placeholder'=>'Enter Units...', 'maxlength'=>20]], 
    ]

    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
