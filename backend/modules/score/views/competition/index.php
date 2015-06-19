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

    <?= $this->render('_list', [
			'title' => Yii::t('igolf', 'Ready'),
	        'dataProvider' => $readyProvider,
	        'filterModel' => $readySearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view}',
			],
	]) ?>

    <?= $this->render('_list', [
			'title' => Yii::t('igolf', 'Open'),
	        'dataProvider' => $openProvider,
	        'filterModel' => $openSearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {score} {scorecards} {leaderboard}',
	            'buttons' => [
	                'score' => function ($url, $model) {
						$url = Url::to(['scorecard/competition', 'id' => $model->id]);
	                    return Html::a(Icon::show('golf', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('igolf', 'Enter scores'),
	                    ]);
	                },
	                'scorecards' => function ($url, $model) {
						$url = Url::to(['score/competition', 'id' => $model->id]);
	                    return Html::a(Icon::show('invoice', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('igolf', 'Scorecards'),
	                    ]);
	                },
					'leaderboard' => function ($url, $model) {
						$url = Url::to(['competition/leaderboard', 'id' => $model->id]);
	                    return Html::a(Icon::show('numberlist', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('igolf', 'Leaderboard'),
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
				 	'template' => '{view} {rule} {leaderboard}',
	            'buttons' => [
	                'rule' => function ($url, $model) {
						$url = Url::to(['competition/rule', 'id' => $model->id]);
	                    return Html::a(Icon::show('law', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('igolf', 'Scorecards'),
	                    ]);
	                },
					'leaderboard' => function ($url, $model) {
						$url = Url::to(['competition/leaderboard', 'id' => $model->id]);
	                    return Html::a(Icon::show('numberlist', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('igolf', 'Leaderboard'),
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
						$url = Url::to(['result/list', 'id' => $model->id]);
	                    return Html::a(Icon::show('podium-winner', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('igolf', 'View scores'),
	                    ]);
	                },
				],
			],
	]) ?>


</div>
