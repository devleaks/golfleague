<?php

use common\models\Scorecard;
use common\models\Competition;

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="scorecard-index">

     <?= GridView::widget([
		'options' => ['id' => 'scorecard'],
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
                    return  $model->registration->competition->name;
                },
				'visible' => $competition === null,
            ],
            [
            	'attribute' => 'competition_type',
                'label' => Yii::t('golf', 'Competition Type'),
                'value' => function($model, $key, $index, $widget) {
                    return  Yii::t('golf', $model->registration->competition->competition_type);
                },
				'filter' => Competition::getLocalizedConstants('TYPE_'),
				'visible' => $competition === null,
            ],
            [
            	'attribute' => 'golfer_name',
                'label' => Yii::t('golf', 'Golfer'),
                'value' => function($model, $key, $index, $widget) {
                    return  $model->registration->golfer->name;
                },
			],
			[
	            'label' => Yii::t('golf', 'Created At'),
				'attribute' => 'created_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->created_at);
				}
			],
			[
	            'label' => Yii::t('golf', 'Last Update'),
				'attribute' => 'updated_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
				}
			],
            [
                'attribute' => 'status',
                'value' => function($model, $key, $index, $widget) {
                	return Yii::t('golf', $model->status);
                },
				'filter' => Scorecard::getLocalizedConstants('STATUS_'),
            ],
            ['class' => 'kartik\grid\CheckboxColumn'],
        ],
    ]); ?>

<?php
$statuses = '<div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'.
				Yii::t('golf', 'Change Status of Selected Scorecards to '). ' <span class="caret"></span></button><ul class="dropdown-menu" role="menu">';
foreach(Scorecard::getLocalizedConstants('STATUS_') as $key => $value)
	$statuses .= '<li>'.Html::a(Yii::t('golf', 'Change to {0}', $value), null, ['class' => 'igolf-bulk-action', 'data-status' => $key]).'</li>';
$statuses .= '</ul></div>';

$buttons = $competition ? Html::a(Yii::t('golf', 'Scores'), Url::to(['competition', 'id' => $competition->id]), ['class'=>'btn btn-primary']) : '';

$buttons .= ' '.$statuses;
echo $buttons;
?>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_PJAXREG') ?>
$("a.igolf-bulk-action").click(function(e) {
	collected = $('#scorecard').yiiGridView('getSelectedRows');
	if(collected != '') {
		status = $(this).data('status');
		console.log('status to '+status);
		
		confirm_str = $(this).data('confirm-local');
		console.log('confirm to '+confirm_str);
		ok = confirm_str ? confirm(confirm_str) : true;
		console.log('ok to '+ok);
		
		if(ok) {
			console.log('sending to '+collected);
			$.ajax({
				type: "POST",
				url: "bulk-status",
				data: {
			        ids : collected,
					status : status
			    },
				success: function () {
					console.log('reloaded');
			        $.pjax.reload({container:'#scorecard-pjax'});
			    }
			});
			console.log('sent');
		}
	}
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_PJAXREG'], yii\web\View::POS_READY);