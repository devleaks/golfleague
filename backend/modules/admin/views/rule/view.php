<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\search\PointSearch;
use common\models\Competition;

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
                'attribute'=>'object_type',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Competition::getConstants('TYPE_'),
            ],
            [
                'attribute'=>'rule_type',
				'type' => DetailView::INPUT_DROPDOWN_LIST,
				'items' => Competition::getConstants('TYPE_'),
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
