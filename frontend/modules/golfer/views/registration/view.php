<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('giveaway', 'Competitions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'competition_type',
            'name',
            'description',
            'parent_id',
            'course_id',
            'holes',
            'rule_id',
            'start_date',
            'registration_begin',
            'registration_end',
            'handicap_min',
            'handicap_max',
            'age_min',
            'age_max',
            'gender',
            'status',
        ],
    ]) ?>

</div>
