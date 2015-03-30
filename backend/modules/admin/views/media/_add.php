<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\Model-with-picture */

?>
<div class="media-add">
<?php
    $items = array();
    foreach($model->media as $picture)
        $items[] = Html::img($picture->getFileUrl(), ['class'=>'file-preview-image', 'alt'=>$picture->name, 'title'=>$picture->name]);

	$form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
		'id' => 'media-input-form',
	]);

	echo $form->field($model, 'media[]')->widget(FileInput::classname(), [
		'options' => ['accept' => 'image/jpeg, image/png, image/gif', 'multiple' => true],
		'pluginOptions' => [
			'initialPreview'    => $items,
			'initialCaption'    => Yii::t('store', 'Select pictures'),
			'overwriteInitial'  => false
		]
	]);
?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('store', 'Add Pictures'), ['class' => 'btn btn-primary', 'id' => 'documentlinedetail-submit']) ?>
    </div>

<?php ActiveForm::end(); ?>
</div>
