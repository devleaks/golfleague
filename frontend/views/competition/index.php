<?php

use common\models\Competition;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', ucfirst(strtolower(isset($type) ? $type : 'All')));
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="season-index">

    <h1>
		<?= Html::encode($this->title) ?>
    	
		<div class="btn-group">
			<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><?= Yii::t('golf', 'New')?> <span class="caret"></span></button>
			<ul class="dropdown-menu" role="menu">
				<li><?= Html::a(Yii::t('golf', Competition::TYPE_SEASON), ['create', 'type' => Competition::TYPE_SEASON]) ?></li>
				<li><?= Html::a(Yii::t('golf', Competition::TYPE_TOURNAMENT), ['create', 'type' => Competition::TYPE_TOURNAMENT]) ?></li>
				<li><?= Html::a(Yii::t('golf', Competition::TYPE_ROUND), ['create', 'type' => Competition::TYPE_ROUND]) ?></li>
			</ul>
		</div>
	</h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            //'competition_type',
            'name',
			[
                'attribute'=>'competition_type',
				'hAlign' => GridView::ALIGN_CENTER,
				'noWrap' => true,
				'visible' => !isset($type),
				'filter' => Competition::getLocalizedConstants('TYPE_'),
			],
            'description',
            //'parent_id',
            // 'course_id',
            // 'holes',
            // 'rule_id',
            // 'start_date',
			[
                'attribute'=>'registration_begin',
				'format' => 'datetime',
				'hAlign' => GridView::ALIGN_CENTER,
                'value' => function ($model, $key, $index, $widget) {
                    return new DateTime($model->registration_begin);
                },
				'noWrap' => true,
			],
			[
                'attribute'=>'registration_end',
				'format' => 'datetime',
				'hAlign' => GridView::ALIGN_CENTER,
                'value' => function ($model, $key, $index, $widget) {
                    return new DateTime($model->registration_end);
                },
				'noWrap' => true,
			],
            // 'handicap_min',
            // 'handicap_max',
            // 'age_min',
            // 'age_max',
            // 'gender',
            [
                'label' => Yii::t('golf', 'Status'),
                'value' => function ($model, $key, $index, $widget) {
                    return Yii::t('golf', $model->status);
                },
            ],
            // 'created_at',
            // 'updated_at',

            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view}',
				'noWrap' => true,
			],
        ],
    ]); ?>

</div>
