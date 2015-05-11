<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Registration;
use common\models\search\RegistrationSearch;

/* @var $this yii\web\View */
/* @var $model common\models\Flight */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Flights'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="flight-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('igolf', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'competition.name',
            'position',
            'note',
            'created_at',
            'updated_at',
        ],
    ]) ?>

<?php
        $searchModel = new RegistrationSearch();
        $dataProvider = $searchModel->search(['RegistrationSearch'=>['flight_id' => $model->id]]);

        echo $this->render('../registration/list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
?>

</div>
