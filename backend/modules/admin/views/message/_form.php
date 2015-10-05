<?php

use common\models\Facility;
use common\models\League;
use common\models\Message;

use vova07\imperavi\Widget as RedactorWidget;

use kartik\widgets\DateTimePicker;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Message */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'league_id')->dropDownList([''=>'']+ArrayHelper::map(League::find()->asArray()->all(), 'id', 'name'), ['prompt' => 'Select League']) ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => 80]) ?>

	<?= $form->field($model, 'body')->widget(RedactorWidget::className(), [
	    'settings' => [
	        'lang' => 'fr',
	        'minHeight' => 300,
	        'plugins' => [
	            'clips',
	        ],
	    	'imageUpload' => Url::to(['image-upload']),
			'imageManagerJson' => Url::to(['images-get']),
	    ]
	]) ?>
	
    <?= $form->field($model, 'message_type')->dropDownList(Message::getLocalizedConstants('TYPE_')) ?>

    <?= $form->field($model, 'facility_id')->dropDownList([''=>''] + ArrayHelper::map(Facility::find()->asArray()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'message_start')->widget(DateTimePicker::classname(), [
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii:ss',
                'todayHighlight' => true
            ]
        ]) ?>

    <?= $form->field($model, 'message_end')->widget(DateTimePicker::classname(), [
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii:ss',
                'todayHighlight' => true
            ]
        ]) ?>

    <?= $form->field($model, 'status')->dropDownList(Message::getLocalizedConstants('STATUS_')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('golf', 'Create') : Yii::t('golf', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
