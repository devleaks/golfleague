<?php

use common\models\Scorecard;

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CompetitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Icon::map($this, Icon::WHHG); 

$this->title = Yii::t('golf', 'Competitions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-index">

    <?= $this->render('_list', [
			'title' => Yii::t('golf', 'Open Rounds'),
	        'dataProvider' => $openProvider,
	        'filterModel' => $openSearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view}',
			],
	]) ?>

    <?= $this->render('_list_tournaments', [
			'title' => Yii::t('golf', 'Open Tournaments'),
	        'dataProvider' => $openTournamentProvider,
	        'filterModel' => $openTournamentSearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
		 		'template' => '{view} {leaderboard}',
	            'buttons' => [
					'leaderboard' => function ($url, $model) {
						$url = Url::to(['competition/leaderboard', 'id' => $model->id]);
	                    return Html::a(Icon::show('numberlist', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('golf', 'Leaderboard'),
	                    ]);
	                },
				],
			],
	]) ?>

    <?= $this->render('_list', [
			'title' => Yii::t('golf', 'Rounds Ready'),
	        'dataProvider' => $readyProvider,
	        'filterModel' => $readySearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {score} {scorecards} {leaderboard}',
	            'buttons' => [
	                'score' => function ($url, $model) {
						$url = Url::to(['scorecard/competition', 'id' => $model->id]);
	                    return Html::a(Icon::show('golf', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('golf', 'Enter scores'),
	                    ]);
	                },
	                'scorecards' => function ($url, $model) {
						$url = Url::to(['score/competition', 'id' => $model->id]);
	                    return Html::a(Icon::show('invoice', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('golf', 'Scorecards'),
	                    ]);
	                },
					'leaderboard' => function ($url, $model) {
						$url = Url::to(['competition/leaderboard', 'id' => $model->id]);
	                    return Html::a(Icon::show('numberlist', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('golf', 'Leaderboard'),
	                    ]);
	                },
				],
			],
	]) ?>

    <?= $this->render('_list_tournaments', [
			'title' => Yii::t('golf', 'Tournament Ready'),
	        'dataProvider' => $readyTournamentProvider,
	        'filterModel' => $readyTournamentSearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
		 		'template' => '{view} {scorecards} {leaderboard}',
	            'buttons' => [
					'leaderboard' => function ($url, $model) {
						$url = Url::to(['competition/leaderboard', 'id' => $model->id]);
	                    return Html::a(Icon::show('numberlist', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('golf', 'Leaderboard'),
	                    ]);
	                },
	                'scorecards' => function ($url, $model) {
						$url = Url::to(['scorecard/competition', 'id' => $model->id]);
	                    return Html::a(Icon::show('invoice', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('golf', 'Scorecards'),
	                    ]);
	                },
				],
			],
	]) ?>

    <?= $this->render('_list', [
			'title' => Yii::t('golf','Competition Completed'),
	        'dataProvider' => $completedProvider,
	        'filterModel' => $completedSearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
				 	'template' => '{view} {rule} {leaderboard}',
	            'buttons' => [
	                'rule' => function ($url, $model) {
						$url = Url::to(['competition/rule', 'id' => $model->id]);
	                    return Html::a(Icon::show('law', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('golf', 'Scorecards'),
	                    ]);
	                },
					'leaderboard' => function ($url, $model) {
						$url = Url::to(['competition/leaderboard', 'id' => $model->id]);
	                    return Html::a(Icon::show('numberlist', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('golf', 'Leaderboard'),
	                    ]);
	                },
				],
			],
	]) ?>

    <?= $this->render('_list', [
			'title' => Yii::t('golf','Competition Closed'),
	        'dataProvider' => $closedProvider,
	        'filterModel' => $closedSearch,
			'actionButtons' => [
				'class' => 'kartik\grid\ActionColumn',
			 	'template' => '{view} {result} {leaderboard}',
	            'buttons' => [
	                'result' => function ($url, $model) {
						$options = [];
						$options['id'] = $model->id;
						if($rule = $model->rule) {
							if($rule->source_type && $rule->source_direction) {
								$options['sort'] = ($rule->source_direction == Scorecard::DIRECTION_ASC ? '+' : '-').$rule->source_type;
							}
						}
						$url = Url::to(['competition/result', 'id' => $model->id]);
	                    return Html::a(Icon::show('podium-winner', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('golf', 'View scores'),
	                    ]);
	                },
					'leaderboard' => function ($url, $model) {
						$url = Url::to(['competition/leaderboard', 'id' => $model->id]);
	                    return Html::a(Icon::show('numberlist', [], Icon::WHHG), $url, [
	                        'title' => Yii::t('golf', 'Leaderboard'),
	                    ]);
	                },
				],
			],
	]) ?>


</div>
