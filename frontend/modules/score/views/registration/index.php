<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golfleague', 'Registrations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('golfleague', 'Create {modelClass}', [
    'modelClass' => 'Registration',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'competition.name',
            'golfer.name',
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('golfleague', 'Status'),
                'value' => function ($data) {
                        return Yii::t('golfleague', $data->status);
                    }
            ],
            //'flight_id',
            // 'tees_id',
            // 'created_at',
            // 'updated_at',
            // 'position',
            // 'score',
            // 'points',
            // 'note',
            // 'team_id',
            // 'score_net',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
