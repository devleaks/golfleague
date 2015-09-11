<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Points');
?>
<div class="point-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> '.Html::encode($this->title).' </h3>',
        ],
        'columns' => [

            'position',
            'points',

            [
                'class' => 'kartik\grid\ActionColumn',
                'controller' => 'point'
            ],
        ],
    ]); ?>

</div>
