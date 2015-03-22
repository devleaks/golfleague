<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Registration */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golfleague', 'Registrations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('golfleague', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('golfleague', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('golfleague', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'competition_id',
            'golfer_id',
            'status',
            'flight_id',
            'tees',
            'created_at',
            'updated_at',
            'position',
            'score',
            'points',
            'note',
            'team_id',
            'score_net',
        ],
    ]) ?>

</div>
