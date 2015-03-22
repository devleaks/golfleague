<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Score */

$this->title = $model->scorecard_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Scores'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="score-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('igolf', 'Update'), ['update', 'scorecard_id' => $model->scorecard_id, 'hole_id' => $model->hole_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('igolf', 'Delete'), ['delete', 'scorecard_id' => $model->scorecard_id, 'hole_id' => $model->hole_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('igolf', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'scorecard_id',
            'hole_id',
            'score',
            'putts',
            'penalty',
            'sand',
            'note',
            'drive',
            'regulation',
            'approach',
            'putt',
        ],
    ]) ?>

</div>
