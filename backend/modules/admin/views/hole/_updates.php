<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\builder\TabularForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Holes');

$buttons = '';

if( sizeof($tees->getHoles()->all()) == 0 ) {
    $buttons .= Html::a(Yii::t('golf', 'Create holes'), ['tees/addholes', 'id' => $tees->id], ['class' => 'btn btn-success']);

    $tees_with_holes = $tees->course->getTeesWithHoles();
    if(sizeof($tees_with_holes) > 0 ) {
	    $buttons .= ' <div class="btn-group">';
	    $buttons .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">'. Yii::t('golf', 'Copy tees set from') . ' <span class="caret"></span></button>';
	    $buttons .= '<ul class="dropdown-menu" role="menu">';

	    foreach($tees_with_holes->each() as $twh) {
	        $buttons .=  '<li>'.Html::a($twh->name.' ('.Yii::$app->golfleague->tee_colors[$twh->color].')', ['tees/copyholes', 'id' => $tees->id, 'copy_id' => $twh->id]).'</li>';
		}

		$buttons .= '</ul>';
		$buttons .= '</div>';
    }
} else // add button to update hole data
	$buttons .= Html::submitButton(Yii::t('golf', 'Update Holes'), ['class'=>'btn btn-primary']);

?>
<div class="hole-index">

    <?php
        $form = ActiveForm::begin(['action'=> Url::to(['/admin/hole/updates'])]);

        echo TabularForm::widget([
            'form' => $form,
            'dataProvider' => $dataProvider,
            'serialColumn' => false,
            'actionColumn' => [
				'template' => '{view}',
				'controller' => 'hole'
			],
            'checkboxColumn' => false,
		    'gridSettings' => [
		        'floatHeader' => true,
		        'panel' => [
		            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i>  '.Html::encode($this->title).' </h3>',
		            'before'=> $buttons
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