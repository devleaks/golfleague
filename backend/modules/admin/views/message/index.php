<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\Facility;
use common\models\Message;

/* @var $this yii\web\View */
/* @var $searchModel backend\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-comment"></i> '.Html::encode($this->title).' </h3>',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          	
//			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            // 'id',
            'subject',
			[
                'attribute'=>'message_type',
				'filter' => Message::getLocalizedConstants('TYPE_'),
			],
			[
                'attribute'=>'facility_id',
				'filter' => ArrayHelper::map(Facility::find()->asArray()->all(), 'id', 'name'),
                'value' => 'facility.name',
			],
			[
                'attribute'=>'status',
				'filter' => Message::getLocalizedConstants('STATUS_'),
			],
            // 'created_at',
            'updated_at',
            // 'body:ntext',
            // 'message_start',
            // 'message_end',
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {delete}'
			],
        ],
    ]); ?>

</div>
