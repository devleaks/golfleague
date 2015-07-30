<?php

namespace backend\modules\start\controllers;

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
		$dataProvider->query->andWhere(['registration.status' => Registration::getPreCompetitionStatuses()]);

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
        $competition = $this->findCompetition($id);
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
    public function actionCreate($id)
    {
        $model = new Registration();
		$model->competition_id = $id;

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
	/**
	 * Bulk update status or delete for PJAXed gridview.
	 */
    public function actionBulkStatus()
    {
		$ids = (array)Yii::$app->request->post('ids'); // Array or selected records primary keys
		$status = Yii::$app->request->post('status');

	    if (!$ids) // Preventing extra unnecessary query
	        return;

		if($status == Registration::ACTION_DELETE) {
			Yii::trace('deleting '.print_r($ids, true));
			// Registration::deleteAll(['id' => $ids]);
			foreach(Registration::find()->andWhere(['id' => $ids])->each() as $registration) {
				if($registration->hasChildren()) {
					Yii::$app->session->setFlash('error', Yii::t('golf', 'Cannot delete registration that has children.'));
				} else {
					$registration->delete();		
				}
			}
		} else
			foreach(Registration::find()->andWhere(['id' => $ids])->each() as $r) {
				$r->status = $status;
				$r->save();
	        }
    }

	/**
	 * Bulk update status or delete for PJAXed gridview.
	 */
    public function actionBulkRegister()
    {
		$ids = (array)Yii::$app->request->post('ids'); // Array or selected records primary keys
		$competition_id = Yii::$app->request->post('competition');

	    if (!$ids) // Preventing extra unnecessary query
	        return;

		if($competition_id < 0) {
			if($competition = $this->findCompetition(-$competition_id)) {
				foreach($competition->getCompetitions()->each() as $child) {
					foreach(Registration::find()->andWhere(['id' => $ids])->each() as $r) {
						$child->register($r->golfer, true);
			        }
				}
			}
		} else {
			if($competition = $this->findCompetition($competition_id)) {
				foreach(Registration::find()->andWhere(['id' => $ids])->each() as $r) {
					$competition->register($r->golfer, true);
		        }
			}
		}
    }

    /**
     * Prepare registration models for a competition for bulk registration by starter.
     * @return mixed
     */
    public function actionBulk($id)
    {
        $model = $this->findCompetition($id);
        $golfers = Golfer::find()->all();
		$availables = [];
		foreach($golfers as $golfer)
			$availables[$golfer->id] = $golfer->name;
			
        $registrations = Registration::find()
			->where([
				'competition_id' => $id,
				'status' => array(Registration::STATUS_PENDING,Registration::STATUS_REGISTERED)
			])
			->all();
        $registereds = [];
        foreach ($registrations as $registration) {
            $registereds[$registration->golfer_id] = $availables[$registration->golfer_id];
            unset($availables[$registration->golfer_id]);
        }

        return $this->render('bulk', [
			'model'	      => $model,
            'availables'  => $availables,
            'registereds' => $registereds,
        ]);
    }


    /**
     * Create registration or suppress registratino.
     * @param integer $id
     * @return mixed
     */
    private function doRegistration($competition_id, $action)
    {
        $post = Yii::$app->request->post();
        $golfers = $post['golfers'];
		$competition = $this->findCompetition($competition_id);
        $error = [];

        foreach ($golfers as $golfer_id) {
			$golfer = Golfer::findOne($golfer_id);
            try {
				if($action === 'register')
                	$competition->register($golfer, true); // force = true, by-pass registration check, starter can overwrite them.
				else
                	$competition->deregister($golfer);
            } catch (\Exception $exc) {
                $error[] = $exc->getMessage();
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [$this->actionGolferSearch($competition_id, 'availables',  $post['search_avail']),
                $this->actionGolferSearch($competition_id, 'registereds', $post['search_regs']),
                $error];
    }

    public function actionRegister($competition_id)
    {
        return $this->doRegistration($competition_id, 'register');
    }

    public function actionDeregister($competition_id)
    {
        return $this->doRegistration($competition_id, 'deregister');
    }

    public function actionGolferSearch($id, $target, $term = '')
    {
		$model = $this->findCompetition($id);

        $golfers = Golfer::find()->all();
		$availables = [];
		foreach($golfers as $golfer)
			$availables[$golfer->id] = $golfer->name;
			
        $registrations = Registration::find()
			->where([
				'competition_id' => $id,
				'status' => array(Registration::STATUS_PENDING,Registration::STATUS_REGISTERED)
			])
			->all();
        $registereds = [];
        foreach ($registrations as $registration) {
            $registereds[$registration->golfer_id] = $availables[$registration->golfer_id];
            unset($availables[$registration->golfer_id]);
        }

        $result = [];
        if (!empty($term)) {
            foreach (${$target} as $golfer) {
                if (strpos($golfer, $term) !== false) {
					$id = Golfer::findOne(['name' => $golfer]);
                    $result[$id->id] = $golfer;
                }
            }
        } else {
            $result = ${$target};
        }
        return Html::renderSelectOptions('', $result);
    }


    /**
     * Lists all Registration models.
     * @return mixed
     */
    public function actionTees($id)
    {
		$competition = $this->findCompetition($id);
        $searchModel = new RegistrationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['competition_id' => intval($id)]);

        return $this->render('tees', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'competition' => $competition,
        ]);
    }

	/**
	 * Bulk update status or delete for PJAXed gridview.
	 */
    public function actionBulkAssign()
    {
		$ids = (array)Yii::$app->request->post('ids'); // Array or selected records primary keys
		$tees_id = Yii::$app->request->post('tees_id');

	    if (!$ids) // Preventing extra unnecessary query
	        return;

		foreach(Registration::find()->andWhere(['id' => $ids])->each() as $r) {
			$r->tees_id = $tees_id;
			$r->save();
		}
    }

	public function actionAssignTees($id) {
		$model = $this->findCompetition($id);
		
		foreach(Registration::find()
			->where([
				'competition_id' => $model->id,
				'status' => array(Registration::STATUS_PENDING,Registration::STATUS_REGISTERED)
			])->each() as $registration) {
			$model->setTees($registration);
		}
		
		return $this->redirect(['tees', 'id' => $model->id]);
	}
	


}
