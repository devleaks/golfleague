<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Scorecard */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Scorecards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scorecard-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('igolf', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('igolf', 'Delete'), ['delete', 'id' => $model->id], [
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
            'id',
            'match_id',
            'golfer_id',
            'tees',
            'note',
        ],
    ]) ?>

</div>
