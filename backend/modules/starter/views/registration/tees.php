<?php

use common\models\Tees;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Tees for ').$competition->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('igolf', 'Competitions'), 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div id="feedback"></div>

<div class="tees-index">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="alert alert-info">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<p>Assign tees based on gender and handicap.</p>
</div>

    <?php $form = ActiveForm::begin(); ?>

    <h2><?= Yii::t('igolf', 'Gentlemen') ?></h2>

	<?= $data ? $data : 'no data' ?>
		<table>
			<thead>
				<tr>
					<td><?= Yii::t('igolf', 'Handicap From') ?></td>
					<td><?= Yii::t('igolf', 'Handicap To') ?></td>
					<td><?= Yii::t('igolf', 'Minimum Age') ?></td>
					<td><?= Yii::t('igolf', 'Tees') ?></td>
				</tr>
			</thead>
			</tbody>
			<?php for($i=0;$i<4;$i++) { ?>
				<tr>
					<td><?= Html::hiddenInput( "TeesForm[".$i."][gender]", 'GENTLEMAN') ?>
						<?= Html::textInput( "TeesForm[".$i."][handicap_from]") ?></td>
					<td><?= Html::textInput( "TeesForm[".$i."][handicap_to]") ?></td>
					<td><?= Html::textInput( "TeesForm[".$i."][age_from]") ?></td>
					<td><?= Html::dropDownList("TeesForm[".$i."][tees_id]",null,
									ArrayHelper::map(Tees::find()->where(['course_id' => $competition->course_id])->asArray()->all(), 'id', 'name')
								) ?></td>
				</tr>
			</tbody>
			<?php } ?>
		</table>


    <h2><?= Yii::t('igolf', 'Ladies') ?></h2>

		<table>
			<thead>
				<tr>
					<td><?= Yii::t('igolf', 'Handicap From') ?></td>
					<td><?= Yii::t('igolf', 'Handicap To') ?></td>
					<td><?= Yii::t('igolf', 'Tees') ?></td>
				</tr>
			</thead>
			</tbody>
			<?php for($i=4;$i<8;$i++) { ?>
				<tr>
					<td><?= Html::hiddenInput( "TeesForm[".$i."][gender]", 'LADY') ?>
						<?= Html::textInput( "TeesForm[".$i."][handicap_from]") ?></td>
					<td><?= Html::textInput( "TeesForm[".$i."][handicap_to]") ?></td>
					<td><?= Html::textInput( "TeesForm[".$i."][age_from]") ?></td>
					<td><?= Html::dropDownList("TeesForm[".$i."][tees_id]",null,
									ArrayHelper::map(Tees::find()->where(['course_id' => $competition->course_id])->asArray()->all(), 'id', 'name')
								) ?></td>
				</tr>
			</tbody>
			<?php } ?>
		</table>

		<?= Html::submitButton(Yii::t('igolf', 'Assign tees'), ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>

</div>