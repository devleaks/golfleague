<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\builder\TabularForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Holes');

$buttons = '';

if( sizeof($tees->getHoles()->all()) == 0 ) {
    $buttons .= Html::a(Yii::t('igolf', 'Create holes'), ['tees/addholes', 'id' => $tees->id], ['class' => 'btn btn-primary']);

    $tees_with_holes = $tees->getCourse()->one()->getTeesWithHoles();
    if(sizeof($tees_with_holes) > 0 ) {
	    $buttons .= ' <div class="btn-group">';
	    $buttons .= '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'. Yii::t('igolf', 'Copy tees set from') . ' <span class="caret"></span></button>';
	    $buttons .= '<ul class="dropdown-menu" role="menu">';

	    foreach($tees_with_holes as $twh)
	        $buttons .=  '<li>'.Html::a($twh->name.' ('.Yii::$app->params['tees_colors'][$twh->color].')', ['tees/copyholes', 'id' => $tees->id, 'copy_id' => $twh->id]).'</li>';

		$buttons .= '</ul>';
		$buttons .= '</div>';
    }
} else // add button to update hole data
	$buttons .= Html::submitButton(Yii::t('igolf', 'Update Holes'), ['class'=>'btn btn-primary']);

?>
<div class="hole-index">

    <?php
        $form = ActiveForm::begin(['action'=> Url::to(['/admin/hole/updates'])]);

        echo TabularForm::widget([
			'id' => 'thetable',
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
                'position' => [
					'type' => TabularForm::INPUT_RAW,
                    'value' => function ($model, $key, $index, $widget) {
                    			return $model->position;
                    	},
					],
                'par' => [ 'type' => TabularForm::INPUT_TEXT, ],
                'si' => [ 'type' => TabularForm::INPUT_TEXT, ],
                'length' => [ 'type' => TabularForm::INPUT_TEXT],
            ],
            //'showPageSummary' => true,
        ]);

        ActiveForm::end();
    ?>

</div>