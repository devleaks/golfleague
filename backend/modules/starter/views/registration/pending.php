<?php

use yii\helpers\Html;
use yii\grid\GridView;
use devleaks\golfleague\components\Competition;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Pending Registrations for ').$competition->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
                    return Yii::t('igolf', $data->competition->name);
                },
            ],
            'golfer.name',
            'created_at',
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, default
                'label' => Yii::t('igolf', 'Status'),
                'value' => function ($data) {
                        return Yii::t('igolf', $data->status);
                    }
            ],
            // 'flight_id',
            // 'tees',
            // 'registered_at',

            ['class' => 'common\models\action\CompetitionStarterActionColumn',
			 'template' => '{approve} {reject}'],
        ],
    ]); ?>

<?php
        if($searchModel == null) {
            echo Html::a(Yii::t('igolf', 'View all registrations'), ['registration/all'], ['class' => 'btn btn-primary']);
        }
?>

</div>
