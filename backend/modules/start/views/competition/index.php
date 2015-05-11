<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CompetitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
Icon::map($this, Icon::FA); 
Icon::map($this, Icon::WHHG); 

$this->title = Yii::t('igolf', 'Competitions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_list', [
			'title' => Yii::t('igolf', 'Registration'),
	        'dataProvider' => $registrationProvider,
	        'filterModel' => $registrationSearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {update} {register}',
	            'buttons' => [
	                'register' => function ($url, $model) {
						$url = Url::to(['registration/competition', 'id' => $model->id]);
	                    return Html::a(Icon::show('group', [], Icon::FA), $url, [
	                        'title' => Yii::t('store', 'Registrations'),
	                    ]);
	                },
				],
			],
	]) ?>

    <?= $this->render('_list', [
			'title' => Yii::t('igolf','Start'),
	        'dataProvider' => $startProvider,
	        'filterModel' => $startSearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {update} {flight} {team} {tees}',
	            'buttons' => [
	                'team' => function ($url, $model) {
						if($model->rule->team) {
							$url = Url::to(['team/competition', 'id' => $model->id]);
		                    return Html::a(Icon::show('groups-friends', [], Icon::WHHG), $url, [
		                        'title' => Yii::t('store', 'Make Teams'),
		                    ]);
						}
						return null;
	                },
	                'flight' => function ($url, $model) {
						$url = Url::to(['flight/competition', 'id' => $model->id]);
	                    return Html::a(Icon::show('flag', [], Icon::FA), $url, [
	                        'title' => Yii::t('store', 'Make Flights'),
	                    ]);
	                },
	                'tees' => function ($url, $model) {
						if($model->rule->team) {
							$url = Url::to(['team/index', 'id' => $model->id]);
		                    return Html::a(Icon::show('golf', [], Icon::WHHG), $url, [
		                        'title' => Yii::t('store', 'Assign Tees'),
		                    ]);
						}
						return null;
	                },
				],
			],
	]) ?>

    <?= $this->render('_list', [
			'title' => Yii::t('igolf','Ready'),
	        'dataProvider' => $readyProvider,
	        'filterModel' => $readySearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {flight}',
	            'buttons' => [
	                'flight' => function ($url, $model) {
						$url = Url::to(['flight/list', 'id' => $model->id]);
	                    return Html::a('<i class="glyphicon glyphicon-list"></i>', $url, [
	                        'title' => Yii::t('store', 'Make Flights'),
	                    ]);
	                },
				],
			],
	]) ?>

    <?= $this->render('_list', [
			'title' => Yii::t('igolf','Planned'),
	        'dataProvider' => $planProvider,
	        'filterModel' => $planSearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {update} {delete}'
			]
	]) ?>

</div>
