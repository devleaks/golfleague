<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Points');
?>
<div class="point-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
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
