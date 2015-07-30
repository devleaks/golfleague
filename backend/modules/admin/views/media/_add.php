<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\Model-with-picture */
$delete_picture = Yii::t('golf', 'Remove picture');
$items = array();
if(method_exists($model, 'shareMedia'))
	foreach($model->shareMedia()->each() as $sharer)
		foreach($sharer->media as $picture)
		    $items[] = Html::img($picture->getFileUrl(), ['class'=>'file-preview-image', 'alt'=>$picture->name, 'title'=>$picture->name]).
						'<br><i class="glyphicon glyphicon-trash remove-action pull-right" data-id="'.$picture->id.'" title="'.$delete_picture.'"></i>';
else
	foreach($model->media as $picture)
	    $items[] = Html::img($picture->getFileUrl(), ['class'=>'file-preview-image', 'alt'=>$picture->name, 'title'=>$picture->name]).
					'<br><i class="glyphicon glyphicon-trash remove-action pull-right" data-id="'.$picture->id.'" title="'.$delete_picture.'"></i>';
?>
<div class="media-add">
	
	<?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
		'id' => 'media-input-form',
	]); ?>

	<?= Html::panel([
		'heading' => Html::tag('h4', Yii::t('golf', 'Pictures')),
		'body' => $form->field($model, 'media[]')->widget(FileInput::classname(), [
			'options' => ['accept' => 'image/jpeg, image/png, image/gif', 'multiple' => true],
			'pluginOptions' => [
				'initialPreview'    => $items,
				'initialCaption'    => Yii::t('golf', 'Select pictures with Browse... then press Upload.'),
				'overwriteInitial'  => false
			]
		])->label(''),
	]) ?>

	<?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_DELETE_PICTURE') ?>
$('.remove-action').click(function(){
	id = $(this).data('id');
	// console.log('deleting id='+id);
	if(confirm("<?= $delete_picture ?>?")) {
		picture = $(this);
		$.ajax({
			type: "POST",
			url: "<?= Url::to(['media/delete']) ?>",
			data: {
		        id : id
		    },
			success: function () {
				picture.parent().remove();
		    }
		});
	}
	return false;
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_DELETE_PICTURE'], yii\web\View::POS_READY);
