<?php

use common\models\Facility;
use common\models\Golfer;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Your Profile');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="golfer-profile-index">

<?php if(!$model): ?>
	<div class="alert alert-info">
		Your are not registered as a golfer.
	</div>
<?php else: ?>
    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> '.Html::encode($this->title).'</h3>',
	    ],
		'buttons1' => '{update}',
		'buttons2' => '{view} {save}',
		'labelColOptions' => ['style' => 'width: 30%'],
		'formOptions' => ['action' => Url::to(['view', 'id' => $model->id])],
        'attributes' => [
            //'id',
            'name',
            'email:email',
            'phone',
            'handicap',
            [
				'attribute' => 'gender',
				'type' => DetailView::INPUT_RADIO_LIST,
				'items' => [''=>Yii::t('golf', 'Unspecified')]+Golfer::getLocalizedConstants('GENDER_')
			],
            [
				'attribute' => 'hand',
				'type' => DetailView::INPUT_RADIO_LIST,
				'items' => [''=>Yii::t('golf', 'Unspecified')]+Golfer::getLocalizedConstants('HAND_')
			],
			[
				'label' => Yii::t('golf', 'Home Course'),
				'attribute' => 'facility_id',
                'value'=>isset($model->facility) ? $model->facility->name : '',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => ['' => 'Select home course...'] + ArrayHelper::map(Facility::find()->where(['>', 'id', 0])->asArray()->all(), 'id', 'name'),
				'widgetOptions' => [
					//'class' => Selectize::className(),
			        'pluginOptions' => [
			            'items' => ArrayHelper::map(Facility::find()->where(['>', 'id', 0])->asArray()->all(), 'id', 'name'),
			        ]
			    ]
			],
        ],
    ]) ?>
<?php endif; ?>

</div>
