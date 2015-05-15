<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use common\models\Registration;
use common\models\Competition;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$starts = [];
foreach($competition->getStarts()->each() as $start)
	$starts[$start->tees_id] = $start->tees->name;


$this->title = $competition->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-index">

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
                'label' => Yii::t('igolf', 'Competition'),
                'value' => function($model, $key, $index, $widget) {
                    return  $model->competition->name;
                },
				'visible' => $competition === null,
            ],
            [
            	'attribute' => 'competition_type',
                'label' => Yii::t('igolf', 'Competition Type'),
                'value' => function($model, $key, $index, $widget) {
                    return  Yii::t('igolf', $model->competition->competition_type);
                },
				'filter' => Competition::getLocalizedConstants('TYPE_'),
				'visible' => $competition === null,
            ],
            [
            	'attribute' => 'golfer_name',
                'label' => Yii::t('igolf', 'Golfer'),
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
	            'label' => Yii::t('igolf', 'Created By'),
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
                	return Yii::t('igolf', $model->status);
                },
				'filter' => Registration::getLocalizedConstants('STATUS_'),
            ],
            // 'flight_id',
            // 'tees_id',

            ['class' => 'kartik\grid\CheckboxColumn'],
        ],
    ]); ?>

<?php
$statuses = '<div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'.
				Yii::t('igolf', 'Set Tees of Selected Golfer to '). ' <span class="caret"></span></button><ul class="dropdown-menu" role="menu">';
foreach($starts as $key => $value)
	$statuses .= '<li>'.Html::a(Yii::t('igolf', $value), null, ['class' => 'igolf-bulk-action', 'data-tees_id' => $key]).'</li>';
$statuses .= '</ul></div>';

$buttons = Html::a(Yii::t('igolf', 'Assign Tees'), ['assign-tees', 'id' => $competition->id], ['class' => 'btn btn-success']);
$buttons .= ' '.$statuses;
echo $buttons;
?>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_PJAXREG') ?>
$("a.igolf-bulk-action").click(function(e) {
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