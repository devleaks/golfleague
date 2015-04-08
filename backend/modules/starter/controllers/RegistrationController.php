<?php

namespace backend\modules\starter\controllers;

use Yii;
use backend\controllers\DefaultController as GolfLeagueController;
use common\models\Competition;
use common\models\search\CompetitionSearch;
use common\models\Golfer;
use common\models\Registration;
use common\models\search\RegistrationSearch;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * RegistrationController implements the CRUD actions for Registration model.
 */
class RegistrationController extends GolfLeagueController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Registration models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegistrationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Registration model.
     * @param integer $id
     * @return mixed
     */
	public function actionView($id) {
        $model=$this->findModel($id);
 
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id'=>$model->id]);
        } else {
            return $this->render('view', ['model'=>$model]);
        }
    }

    /**
     * Lists all Registration models.
     * @return mixed
     */
    public function actionCompetition($id)
    {
        if (($competition = Competition::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $searchModel = new RegistrationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['competition_id' => intval($id)]);

        return $this->render('competition', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'competition' => $competition,
        ]);
    }

    /**
     * Creates a new Registration model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Registration();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Registration model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Registration the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Registration::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


	/**
	 * Bulk update status or delete for PJAXed gridview.
	 */
    public function actionBulkStatus()
    {
		$ids = (array)Yii::$app->request->post('ids'); // Array or selected records primary keys
		$status = Yii::$app->request->post('status');

	    if (!$ids) // Preventing extra unnecessary query
	        return;

		if($status == Registration::ACTION_DELETE)
			Registration::deleteAll(['id' => $ids]);
		else
			foreach($ids as $id) {
				if($r = Registration::findOne($id)) {
					$r->status = $status;
					$r->save();
				}
	        }
    }


	


}
