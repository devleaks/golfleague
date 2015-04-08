<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('igolf', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute'=>'competition_type',
                'label'=>'Competition Type',
                'value'=> Yii::t('igolf', $model->competition_type),
            ],
            [
                'attribute'=>'parent_id',
                'label'=>'Competition',
                'value'=> $model->parent ? $model->parent->name : '',
            ],
            'name',
            'description',
            'course.name',
            'holes',
            'rules.name',
            'start_date',
            'registration_begin',
            'registration_end',
            'handicap_min',
            'handicap_max',
            'age_min',
            'age_max',
            [
                'attribute'=>'gender',
                'label'=>'Gender',
                'value'=> Yii::t('igolf', $model->gender),
            ],
            [
                'attribute'=>'status',
                'label'=>'Status',
                'value'=> Yii::t('igolf', $model->status),
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
