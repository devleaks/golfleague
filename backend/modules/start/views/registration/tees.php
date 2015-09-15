<?php

use common\models\Registration;
use common\models\Golfer;
use common\models\Competition;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$starts = [];
foreach($competition->getStarts()->each() as $start)
	$starts[$start->tees_id] = $start->tees->name;


$this->title = $competition->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-index">

<?php if(! $competition->getStarts()->exists()): ?>
	<div class="alert alert-warning">
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		<p>Competition is no starting tee set.</p>
		
		<?= Html::a(Yii::t('golf', 'Add first tees found'), ['/admin/start/add', 'id' => $competition->id, 'm' => 'q']) ?>.
		<?php if($competition->course->hasTees(Golfer::GENDER_LADY) && $competition->course->hasTees(Golfer::GENDER_GENTLEMAN)): ?>
			<?= Html::a(Yii::t('golf', 'Add tees per gender'), ['/admin/start/add', 'id' => $competition->id, 'm' => 'g']) ?>.
		<?php endif; ?>
		<?= Html::a(Yii::t('golf', 'Edit competition'), ['/admin/competition/view', 'id' => $competition->id]) ?>.
	</div>

<?php endif; ?>

     <?= GridView::widget([
		'options' => ['id' => 'registration'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel'=>[
	        'heading' => '<h4>'.$this->title.'</h4>',
			'footer' => false,
	    ],
		'pjax' => true,
		'pjaxSettings' => [
	        'neverTimeout' => true,
        ],
		'export' => false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
            	'attribute' => 'competition_name',
                'label' => Yii::t('golf', 'Competition'),
                'value' => function($model, $key, $index, $widget) {
                    return  $model->competition->name;
                },
				'visible' => $competition === null,
            ],
            [
            	'attribute' => 'competition_type',
                'label' => Yii::t('golf', 'Competition Type'),
                'value' => function($model, $key, $index, $widget) {
                    return  Yii::t('golf', $model->competition->competition_type);
                },
				'filter' => Competition::getLocalizedConstants('TYPE_'),
				'visible' => $competition === null,
            ],
            [
            	'attribute' => 'golfer_name',
                'label' => Yii::t('golf', 'Golfer'),
                'value' => function($model, $key, $index, $widget) {
                    return  $model->golfer->name;
                },
			],
			[
				'attribute' => 'tees_id',
				'filter' => $starts,
				'format' => 'raw',
				'value' => function ($model, $key, $index, $widget) {
					return $model->tees ? $model->tees->getLabel() : '';
				}
			],
/*	        [
	            'label' => Yii::t('golf', 'Created By'),
				'attribute' => 'created_by',
				'filter' => ArrayHelper::map(User::find()->asArray()->all(), 'id', 'username'),
	            'value' => function ($model, $key, $index, $widget) {
					$user = $model->createdBy;
	                return $user ? $user->username : '?';
	            },
	            'format' => 'raw',
	        ],*/
            [
                'attribute' => 'status',
                'value' => function($model, $key, $index, $widget) {
                	return Yii::t('golf', $model->status);
                },
				'filter' => Registration::getLocalizedConstants('STATUS_'),
            ],
            ['class' => 'kartik\grid\CheckboxColumn'],
        ],
    ]); ?>

<?php
$statuses = '<div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'.
				Yii::t('golf', 'Set Tees of Selected Golfer to '). ' <span class="caret"></span></button><ul class="dropdown-menu" role="menu">';
foreach($starts as $key => $value)
	$statuses .= '<li>'.Html::a(Yii::t('golf', $value), null, ['class' => 'golfleague-bulk-action', 'data-tees_id' => $key]).'</li>';
$statuses .= '</ul></div>';

$buttons = Html::a(Yii::t('golf', 'Assign Tees'), ['assign-tees', 'id' => $competition->id], ['class' => 'btn btn-success']);
$buttons .= ' '.$statuses;
echo $buttons;
?>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_PJAXREG') ?>
$("a.golfleague-bulk-action").click(function(e) {
	collected = $('#registration').yiiGridView('getSelectedRows');
	if(collected != '') {
		tees_id = $(this).data('tees_id');
		console.log('status to '+status);
		$.ajax({
			type: "POST",
			url: "bulk-assign",
			data: {
		        ids : collected,
				tees_id : tees_id
		    },
			success: function () {
				console.log('reloaded');
		        $.pjax.reload({container:'#registration-pjax'});
		    }
		});
		console.log('sent');
	}
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_PJAXREG'], yii\web\View::POS_READY);