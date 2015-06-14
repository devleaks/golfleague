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

    <?= $this->render('_list', [
			'title' => Yii::t('igolf', 'Registration'),
	        'dataProvider' => $registrationProvider,
	        'filterModel' => $registrationSearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {register}',
	            'buttons' => [
	                'register' => function ($url, $model) {
						$url = Url::to(['registration/competition', 'id' => $model->id]);
	                    return Html::a(Icon::show('group', [], Icon::FA), $url, [
	                        'title' => Yii::t('igolf', 'Registrations'),
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
			 	'template' => '{view} {tees} {flight} {team}',
	            'buttons' => [
	                'team' => function ($url, $model) {
						if($model->rule->team) {
							$url = Url::to(['team/competition', 'id' => $model->id]);
		                    return Html::a(Icon::show('groups-friends', [], Icon::WHHG), $url, [
		                        'title' => Yii::t('igolf', 'Make Teams'),
		                    ]);
						}
						return null;
	                },
	                'flight' => function ($url, $model) {
						$url = Url::to(['flight/competition', 'id' => $model->id]);
	                    return Html::a(Icon::show('flag', [], Icon::FA), $url, [
	                        'title' => Yii::t('igolf', 'Make Flights'),
	                    ]);
	                },
	                'tees' => function ($url, $model) {
						$url = Url::to(['registration/assign-tees', 'id' => $model->id]);
	                    return Html::a(Icon::show('filter', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('igolf', 'Assign Tees'),
	                    ]);
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
	                        'title' => Yii::t('igolf', 'Make Flights'),
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
			 	'template' => '{view} {delete}'
			]
	]) ?>

</div>
