<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use devleaks\golfleague\components\Competition;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="registration-index">

    <h1><?= Yii::t('golf', 'Flight Members') ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'golfer.name',
            'created_at',
            'updated_at',
            [
                'label' => Yii::t('golf', 'Status'),
                'value' => function($model, $key, $index, $widget) {
                        return Yii::t('golf', $model->status);
                    }
            ],
        ],
    ]); ?>

<?php
        if($searchModel == null) {
            echo Html::a(Yii::t('golf', 'View all registrations'), ['registration/index'], ['class' => 'btn btn-primary']);
        }
?>

</div>
