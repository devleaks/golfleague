<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\HoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Holes');
?>
<div class="hole-index">

    <h2><?= Html::encode($this->title) ?></h2>
 

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            'position',
            [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => 'par',
                    'pageSummary' => true,
                    'pageSummaryFunc' => GridView::F_SUM,
            ],
            'si',
            [
                    'class' => '\kartik\grid\EditableColumn',
                    'attribute' => 'length',
                    'pageSummary' => true,
                    'pageSummaryFunc' => GridView::F_SUM,
                    'editableOptions' => ['formOptions' => ['action' => '/basic/web/golfleague/admin/hole/updatelength']],
            ],
        ],
        'showPageSummary' => true,
    ]); ?>

</div>
