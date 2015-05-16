<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use common\models\Registration;
use common\models\Competition;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
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
	            'label' => Yii::t('igolf', 'Created At'),
				'attribute' => 'created_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
				}
			],
			[
	            'label' => Yii::t('igolf', 'Last Update'),
				'attribute' => 'updated_at',
				'format' => 'datetime',
				'value' => function ($model, $key, $index, $widget) {
					return new DateTime($model->updated_at);
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

            [
				'class' => 'kartik\grid\ActionColumn',
				'noWrap' => true,
			],

            ['class' => 'kartik\grid\CheckboxColumn'],
        ],
    ]); ?>

<?php
$buttons = '<div class="btn-group"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'.
				Yii::t('igolf', 'Change Status of Selected Registrations to '). ' <span class="caret"></span></button><ul class="dropdown-menu" role="menu">';
foreach(Registration::getLocalizedConstants('STATUS_') as $key => $value)
	$buttons .= '<li>'.Html::a(Yii::t('igolf', 'Change to {0}', $value), null, ['class' => 'igolf-bulk-action', 'data-status' => $key]).'</li>';
$buttons .= '</ul></div>';

echo $buttons;
?>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_PJAXREG') ?>
$("a.igolf-bulk-action").click(function(e) {
	status = $(this).data('status');
	console.log('status to '+status);
	collected = $('#registration').yiiGridView('getSelectedRows');
	if(collected != '') {
		$.post(
		    "bulk-status", 
		    {
		        ids : collected,
				status : status
		    },
		    function () {
		        $.pjax.reload({container:'#registration-pjax'});
		    }
		);
	}
});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_PJAXREG'], yii\web\View::POS_READY);