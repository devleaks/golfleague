<?php

use yii\helpers\Html;
use yii\grid\GridView;
use devleaks\golfleague\components\Competition;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="registration-index">

    <h1><?= Yii::t('igolf', 'Flight Members') ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
        ],
    ]); ?>

<?php
        if($searchModel == null) {
            echo Html::a(Yii::t('igolf', 'View all registrations'), ['registration/index'], ['class' => 'btn btn-primary']);
        }
?>

</div>
