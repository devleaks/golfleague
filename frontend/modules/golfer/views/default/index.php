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

	<div class="row">
		<div class="col-lg-8">
			<?php if(!$model): ?>
				<div class="alert alert-info">
					Your are not registered as a golfer.
				</div>
			<?php else: ?>
    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3 class="panel-title">'.Html::encode($this->title).'</h3>',
	    ],
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
		
		<div class="col-lg-4">
		    <h3><?= Yii::t('goflleague', 'Menu') ?></h3>

		    <h3><?= Yii::t('goflleague', 'Enter score') ?></h3>

				List of registration, with date of event in the past, for which there is no completed scorecard.

		    <h3><?= Yii::t('goflleague', 'Register') ?></h3>

				List of matches, with date in future, where player is allowed to play.

		    <h3><?= Yii::t('goflleague', 'Recent results') ?></h3>

				List of completed scorecards, with links to leaderboards, etc.
		</div>
	</div>


</div>
