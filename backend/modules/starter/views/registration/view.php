<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Registration */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Registrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-view">

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
            [
                'attribute'=>'competition_id',
                'label'=>'Competition',
                'value'=>$model->competition->name,
            ],
            [
                'attribute'=>'golfer_id',
                'label'=>'Golfer',
                'value'=>$model->golfer->name,
            ],
            [
                'attribute'=>'status',
                'label'=>'Status',
                'value'=>Yii::t('igolf', $model->status),
            ],
        ],
    ]) ?>

</div>
