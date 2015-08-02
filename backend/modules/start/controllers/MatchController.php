<?php

namespace backend\modules\start\controllers;

use common\models\Competition;

use yii\web\Controller;

class MatchController extends Controller
{
    /**
     * Lists all Registration models.
     * @return mixed
     */
    public function actionCompetition($id)
    {
        $competition = $this->findCompetition($id);
 
        return $this->render('competition', [
            'searchModel' => null,
            'dataProvider' => null,
			'competition' => $competition,
        ]);
    }

    /**
     * Finds the Competition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Competition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCompetition($id)
    {
        if (($model = Competition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
