<?php

use common\models\Competition;
use common\models\search\MatchSearch;
use common\models\search\TournamentSearch;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golf', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'competition_type',
            'name',
            'description',
            'parent_id',
            'course_id',
            'holes',
            'rule_id',
            'start_date',
            'registration_begin',
            'registration_end',
            'handicap_min',
            'handicap_max',
            'age_min',
            'age_max',
            'gender',
            'status',
        ],
    ]) ?>

<?php
		if(in_array($model->competition_type, [Competition::TYPE_SEASON, Competition::TYPE_TOURNAMENT])) {
			switch($model->competition_type) {
			case Competition::TYPE_SEASON:
		        $searchModel = new TournamentSearch();
		        $dataProvider = $searchModel->search(['TournamentSearch'=>['parent_id' => $model->id]]);
				$type = Competition::TYPE_TOURNAMENT;
				break;
			case Competition::TYPE_TOURNAMENT:
		        $searchModel = new MatchSearch();
		        $dataProvider = $searchModel->search(['MatchSearch'=>['parent_id' => $model->id]]);
				$type = Competition::TYPE_MATCH;
				break;
			}
				//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

	        echo $this->render('_list', [
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,
				'parent' => $model,
				'type' => $type
	        ]);
		}
?>

</div>
