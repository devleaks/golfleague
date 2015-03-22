<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\builder\TabularForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Points');

$buttons = '<div class="btn-group">';
$buttons .= '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'. Yii::t('igolf', 'Add points') . ' <span class="caret"></span></button>';
$buttons .= '<ul class="dropdown-menu" role="menu">';
foreach([1, 2, 5, 10, 20, 50] as $cnt)
    $buttons .= '<li>'.Html::a(Yii::t('igolf', 'Add {0, number} points', $cnt), ['rule/addpoints', 'rule_id' => $rule->id, 'count' => $cnt]).'</a></li>';
$buttons .= '</ul>';
$buttons .= '</div> ';

if( $rule->getPoints()->count() > 0 ) {
	$buttons .= Html::submitButton(Yii::t('igolf', 'Update Points'), ['class'=>'btn btn-primary']);
}

?>
<div class="point-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php
        $form = ActiveForm::begin(['action'=> Url::to(['/admin/point/updates'])]);

        echo TabularForm::widget([
            'form' => $form,
            'dataProvider' => $dataProvider,
            'serialColumn' => false,
            'actionColumn' => false,
            'checkboxColumn' => false,
		    'gridSettings' => [
		        'floatHeader' => true,
		        'panel' => [
		            'heading' => '<h4>'.$this->title.'</h4>',
		            'footer'=> $buttons
		        ]
		    ], 
            'attributes' => [
                'position' => [ 'type' => TabularForm::INPUT_TEXT, ],
                'points' => [ 'type' => TabularForm::INPUT_TEXT, ],
			],
			'actionColumn' => [
			    'class' => '\kartik\grid\ActionColumn',
				'controller' => 'point',
			    'template' => '{delete}'
			]
        ]);

        ActiveForm::end();
    ?>

</div>
