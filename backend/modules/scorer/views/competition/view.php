<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Registration;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('golfleague', 'Competitions'), 'url' => ['index']];
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
            'course_id',
            'holes',
            'rule_id',
            'start_date',
            'status',
        ],
    ]) ?>

</div>
