<?php

use yii\helpers\Html;
use yii\grid\GridView;
use devleaks\golfleague\components\Competition;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Registrations');
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('igolf', 'Create {modelClass}', [
    'modelClass' => 'Registration',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('igolf', 'Competition'),
                'value' => function ($data) {
                    return  $data->competition->name;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('igolf', 'Competition Type'),
                'value' => function ($data) {
                    return  Yii::t('igolf', $data->competition->competition_type);
                },
            ],
            'golfer.name',
            'created_at',
            'updated_at',
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('igolf', 'Status'),
                'value' => function ($data) {
                        return Yii::t('igolf', $data->status);
                    }
            ],
            // 'flight_id',
            // 'tees_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

<?php
        if($searchModel == null) {
            echo Html::a(Yii::t('igolf', 'View all registrations'), ['registration/index'], ['class' => 'btn btn-primary']);
        }
?>

</div>
