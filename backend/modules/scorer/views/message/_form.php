<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Message $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="message-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'body'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Body...','rows'=> 6]], 

'message_start'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'message_end'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'created_at'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'updated_at'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'subject'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Subject...', 'maxlength'=>80]], 

'message_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Message Type...', 'maxlength'=>20]], 

'status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status...', 'maxlength'=>20]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
