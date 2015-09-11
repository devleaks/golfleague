<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\builder\TabularForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('golf', 'Points');

$buttons = '<div class="btn-group">';
$buttons .= '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'. Yii::t('golf', 'Add points') . ' <span class="caret"></span></button>';
$buttons .= '<ul class="dropdown-menu" role="menu">';
foreach([1, 2, 5, 10, 20, 50] as $cnt)
    $buttons .= '<li>'.Html::a(Yii::t('golf', 'Add {0, number} points', $cnt), ['rule/addpoints', 'rule_id' => $rule->id, 'count' => $cnt]).'</a></li>';
$buttons .= '</ul>';
$buttons .= '</div> ';

if( $rule->getPoints()->count() > 0 ) {
	$buttons .= Html::submitButton(Yii::t('golf', 'Update Points'), ['class'=>'btn btn-primary']);
}

?>
<div class="point-index">

    <?php
        $form = ActiveForm::begin(['action'=> Url::to(['/admin/point/updates'])]);

        echo TabularForm::widget([
            'form' => $form,
            'dataProvider' => $dataProvider,
            'serialColumn' => false,
            'actionColumn' => [
				'template' => '{delete}',
				'controller' => 'point',
	            'buttons' => [
	                'delete' => function ($url, $model) {
	                    return Html::a('<i class="glyphicon glyphicon-trash"></i>', $url, [
	                        'class' => 'btn',
	                        'data-confirm' => Yii::t('golf', 'Are you sure to delete this point?'),
	                        'title' => Yii::t('golf', 'Delete'),
	                    ]);
	                },
	            ],
				'urlCreator' => function ($action, $model, $key, $index) {
					$url = '';
					switch($action) {
						case 'delete':
							$url = Url::to(['point/delete-get', 'id' => $model->id]);
							break;
					}
					return $url;
				}
			],
            'checkboxColumn' => false,
		    'gridSettings' => [
		        'responsive'=>true,
		        'hover'=>true,
		        'condensed'=>true,
		        'floatHeader'=>true,
		        'panel' => [
		            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i>  '.Html::encode($this->title).' </h3>',
		            'before'=> $buttons
		        ]
		    ], 
            'attributes' => [
                'position' => [ 'type' => TabularForm::INPUT_TEXT, ],
                'points' => [ 'type' => TabularForm::INPUT_TEXT, ],
			],
        ]);

        ActiveForm::end();
    ?>

</div>
