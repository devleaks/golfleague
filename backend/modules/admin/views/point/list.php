<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Points');
?>
<div class="point-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'position',
            'points',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'point'
            ],
        ],
    ]); ?>

</div>
