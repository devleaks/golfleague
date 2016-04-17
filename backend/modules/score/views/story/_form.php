<?php

use common\models\Animation;
use common\models\Presentation;
use common\models\Story;

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use vova07\imperavi\Widget as Redactor;
/**
 * @var yii\web\View $this
 * @var common\models\Story $model
 * @var yii\widgets\ActiveForm $form
 */

$model->story_type = 'STORY';

?>
<div class="story-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

			'title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Title...', 'maxlength'=>160]],

			'header'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Header...', 'maxlength'=>400]],

			'body'=>['type'=> Form::INPUT_WIDGET,
				 	'widgetClass' => Redactor::className(),
					'settings' => [
					    'lang' => 'ru',
					    'minHeight' => 200,
					    'plugins' => [
					        'clips',
					        'fullscreen'
					    ]
					]
			],

			'status'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'options'=>['placeholder'=>'Enter Status...', 'maxlength'=>40], 'items' => Story::getLocalizedConstants('STATUS_')],

			'presentation_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'options'=>['placeholder'=>'Enter Presentation...'], 'items' => ['' => ''] + Presentation::getList()],

			'animation_id'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'options'=>['placeholder'=>'Enter Animation...'], 'items' => ['' => ''] + Animation::getList()],

			'animation_parameters'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Animation Parameters...','rows'=> 6]],

			'animation_data'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Animation Data...','rows'=> 6]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
