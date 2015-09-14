<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use common\models\Registration;
use common\models\Competition;
use backend\modules\start\controllers\RegistrationController;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="registration-index">

     <?= GridView::widget([
		'options' => ['id' => 'registration'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'panel'=>[
	        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
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
				'filter' => Registration::getLocalizedPreCompetitionStatuses(),
            ],
            ['class' => 'kartik\grid\CheckboxColumn'],
        ],
    ]); ?>

<?php
$statuses = '<div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'.
				Yii::t('golf', 'Change Status of Selected Registrations to '). ' <span class="caret"></span></button><ul class="dropdown-menu" role="menu">';
foreach(Registration::getLocalizedConstants('STATUS_') as $key => $value)
	$statuses .= '<li>'.Html::a(Yii::t('golf', 'Change to {0}', $value), null, ['class' => 'golfleague-bulk-action', 'data-status' => $key]).'</li>';
$statuses .= '</ul></div>';

$buttons = '';
if($competition) {
	$buttons = Html::a(Yii::t('golf', 'New Registration'), ['create', 'id' => $competition->id], ['class' => 'btn btn-success']);
	$buttons .= ' '.Html::a(Yii::t('golf', 'Bulk Registrations'), ['bulk', 'id' => $competition->id], ['class' => 'btn btn-success']);
}
$buttons .= ' '.Html::a(Yii::t('golf', 'Delete Selected Registrations'), null, ['class' => 'btn btn-danger golfleague-bulk-action', 'data' => [
				'status' => RegistrationController::ACTION_DELETE,
			    'confirm-local' => Yii::t('golf', 'Are you sure you want to delete selected registration(s)?'), // interference with bootbox
]]);
$buttons .= ' '.$statuses;

if($competition) {
	if(in_array($competition->competition_type, [Competition::TYPE_SEASON, Competition::TYPE_TOURNAMENT])) {
		if($competition->getCompetitions()->exists()) {
			$children = '<div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'.
							Yii::t('golf', 'Register Selected to '). ' <span class="caret"></span></button><ul class="dropdown-menu" role="menu">';
			foreach($competition->getCompetitions()->each() as $child)
				$children .= '<li>'.Html::a(Yii::t('golf', 'Register to {0}', $child->name), null, ['class' => 'golfleague-bulk-action', 'data-status' => 'competition', 'data-competition' => $child->id]).'</li>';
			$children .= '<li>'.Html::a(Yii::t('golf', 'Register to all'), null, ['class' => 'golfleague-bulk-action', 'data-status' => 'competition', 'data-competition' => -$competition->id]).'</li>';
			$children .= '</ul></div>';
			$buttons .= ' ' . $children;
		}
	}
}


echo $buttons;
?>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_PJAXREG') ?>
$("a.golfleague-bulk-action").click(function(e) {
	collected = $('#registration').yiiGridView('getSelectedRows');
	if(collected != '') {
		status = $(this).data('status');
		console.log('status to '+status);
		
		confirm_str = $(this).data('confirm-local');
//		console.log('confirm to '+confirm_str);
		ok = confirm_str ? confirm(confirm_str) : true;
//		console.log('ok to '+ok);
		
		if(ok) {
//			console.log('sending to '+collected);
			if(status == 'competition') {
				competition = $(this).data('competition');
				$.ajax({
					type: "POST",
					url: "<?= Url::to(['registration/bulk-register'])?>",
					data: {
				        ids : collected,
						competition : competition
				    },
					success: function () {
						console.log('reloaded');
				        $.pjax.reload({container:'#registration-pjax'});
				    }
				});
			} else {
				$.ajax({
					type: "POST",
					url: "<?= Url::to(['registration/bulk-status'])?>",
					data: {
				        ids : collected,
						status : status
				    },
					success: function () {
						console.log('reloaded');
				        $.pjax.reload({container:'#registration-pjax'});
				    }
				});
			}
//			console.log('sent');
		}
	}
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_PJAXREG'], yii\web\View::POS_READY);