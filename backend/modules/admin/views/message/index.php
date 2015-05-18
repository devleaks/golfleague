<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\Facility;
use common\models\Message;

/* @var $this yii\web\View */
/* @var $searchModel backend\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">

    <h1>    <h1><?= Html::encode($this->title) ?> <?= Html::a(Yii::t('igolf', 'Create {modelClass}', [

    'modelClass' => 'Message',
]), ['create'], ['class' => 'btn btn-success']) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
