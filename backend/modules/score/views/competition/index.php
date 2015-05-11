<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CompetitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Icon::map($this, Icon::WHHG); 

$this->title = Yii::t('igolf', 'Competitions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_list', [
			'title' => Yii::t('igolf', 'On Going'),
	        'dataProvider' => $ongoingProvider,
	        'filterModel' => $ongoingSearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {update} {score}',
	            'buttons' => [
	                'score' => function ($url, $model) {
						$url = Url::to(['score/competition', 'id' => $model->id]);
	                    return Html::a(Icon::show('trophy'), $url, [
	                        'title' => Yii::t('store', 'Enter scores'),
	                    ]);
	                },
				],
			],
	]) ?>

    <?= $this->render('_list', [
			'title' => Yii::t('igolf','Completed'),
	        'dataProvider' => $completedProvider,
	        'filterModel' => $completedSearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {update} {score}',
	            'buttons' => [
	                'score' => function ($url, $model) {
						$url = Url::to(['score/competition', 'id' => $model->id, 'sort' => 'position']);
	                    return Html::a(Icon::show('trophy'), $url, [
	                        'title' => Yii::t('store', 'Enter scores'),
	                    ]);
	                },
				],
			],
	]) ?>

    <?= $this->render('_list', [
			'title' => Yii::t('igolf','Closed'),
	        'dataProvider' => $closedProvider,
	        'filterModel' => $closedSearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {result}',
	            'buttons' => [
	                'result' => function ($url, $model) {
						$url = Url::to(['score/result', 'id' => $model->id, 'sort' => 'position']);
	                    return Html::a(Icon::show('podium-winner', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('store', 'View scores'),
	                    ]);
	                },
				],
			],
	]) ?>


</div>
