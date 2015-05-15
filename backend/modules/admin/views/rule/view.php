<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\search\PointSearch;
use common\models\Competition;
use common\models\Rule;

/* @var $this yii\web\View */
/* @var $model app\models\Rule */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competition Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rule-view">

    <?= DetailView::widget([
        'model' => $model,
		'panel'=>[
	        'heading' => '<h3>'.$model->name.'</h3>',
	    ],
		'labelColOptions' => ['style' => 'width: 30%'],
        'attributes' => [
//            'id',
            'name',
            'description',
            [
                'attribute'=>'competition_type',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Competition::getConstants('TYPE_'),
            ],
            [
                'attribute'=>'object_type',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Rule::getConstants('TYPE_'),
            ],
            [
                'attribute'=>'rule_type',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Rule::getConstants('RULE_'),
            ],
            [
                'attribute'=>'team',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => [null => Yii::t('igolf', 'Individual'),
						2 => '2 '.Yii::t('igolf', 'Players'),
						3 => '3 '.Yii::t('igolf', 'Players'),
						4 => '4 '.Yii::t('igolf', 'Players'),
				],
            ],
            'note',
            'classname',
        ],
    ]) ?>



<?php
        $searchModel = new PointSearch();
        $dataProvider = $searchModel->search(['PointSearch'=>['rule_id' => $model->id]]);

        echo $this->render('../point/_updates', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'rule' => $model
        ]);
?>

</div>
