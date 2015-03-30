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
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Registration model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
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


/* REGISTRATION APPROVAL
 */

    /**
     * Update status.
     * @param integer $id Registration id
     * @return mixed
     */
    protected function updateStatus($id, $status)
    {
        if (($model = Registration::findOne($id)) !== null) {
            $model->status = $status;
            $model->save();
            return $this->actionPending($model->competition_id);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Approve registration request.
     * @param integer $id Registration id
     * @return mixed
     */
    public function actionApprove($id)
    {
        return $this->updateStatus($id, Registration::STATUS_REGISTERED);
    }

    /**
     * Reject registration.
     * @param integer $id Registration id
     * @return mixed
     */
    public function actionReject($id)
    {
        return $this->updateStatus($id, Registration::STATUS_REJECTED);
    }


/* LIST OF REGISTRATION
 */

    /**
     * Lists pending Registration models for approval.
     * @param integer $id Competition
     * @return mixed
     */
    public function actionPending($id)
    {
        $searchModel = null;
        $query = Registration::find();
        $query->andWhere(['competition_id' => $id]);
        $query->andWhere(['status' => array(Registration::STATUS_PENDING, Registration::STATUS_REGISTERED, Registration::STATUS_REJECTED)]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('pending', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'competition' => Competition::findOne($id),
        ]);
    }

    /**
     * Group registration models in teams.
     * @param integer $id Competition
     * @return mixed
     */
    public function actionTeam($id)
    {
		$model = Competition::findOne($id);
		$registrations =  $model->getRegistrations()
					   			->andWhere(['status' => array(Registration::STATUS_CONFIRMED, Registration::STATUS_REGISTERED)]);

		$teams = [];	/** teams used in the competition (already defined) */
		$noteam = [];	/** registrations with no team */
		foreach($registrations->each() as $registration) {
			if(isset($registration->team_id) && intval($registration->team_id) > 0)
				$teams[] = Team::findOne($registration->team_id);
			else
				$noteam[] = $registration;
		}

        return $this->render('team', [
            'teams' => $teams,
            'noteam' => $noteam,
			'competition' => $model,
        ]);
    }

/* BULK REGISTRATION
 */

    /**
     * Prepare registration models for a competition for bulk registration by starter.
     * @return mixed
     */
    public function actionBulk($id)
    {
		$model = Competition::findOne($id);
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
		$competition = Competition::findOne($competition_id);
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
		$model = Competition::findOne($id);

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

/* PREPARE REGISTRATION
 */
    /**
     * Set tees for each registration of a competition depending on Tee Set rules.
     * @return mixed
     */
	private function setTees($competition_id, $handicap_from, $handicap_to, $gender, $age_min, $tees_id) {
		$regs = Registration::find()->andWhere(['competition_id' => $competition_id]);
		$str = '';
		foreach($regs->each() as $reg) {
			$golfer = $reg->getGolfer()->one();
			if($golfer->gender == $gender) {
				$doit = false;

				$hdcp = $golfer->handicap();
				if($handicap_from === null) { 	// no lower limit
					$doit = ($hdcp < $handicap_to);	// must be a upper kimit
				} else {
					if($handicap_to === null) {	// just lower limit
						$doit = ($hdcp >= $handicap_from);
					} else {
						$doit = ($hdcp >= $handicap_from && $hdcp < $handicap_to);
					}
				}

				if($age_min !== null)
					$doit = ($golfer->age() > $age_min);

				if($doit) {
					$str .= 'doing '.$golfer->id.', ';
					$reg->tees_id = $tees_id;
					$reg->save();
				}
			}
		}
		
		return $str;
	}

    public function actionTees($id)
    {
		$competition = Competition::findOne($id);
		if(!$competition)
        	throw new NotFoundHttpException('The requested page does not exist.');

        $post = Yii::$app->request->post();
		$str = '';
		if($post) {
			if(isset($post['TeesForm'])) {
				$teesForm = $post['TeesForm'];
				foreach($teesForm as $form) {
					if($form['handicap_from'] !== '' || $form['handicap_to'] !== '') {
						$hdcp_from = ($form['handicap_from'] === '') ? null : (($form['handicap_from'] === '0') ? 0 : $form['handicap_from']);
						$hdcp_to = ($form['handicap_to'] === '') ? null : (($form['handicap_to'] === '0') ? 0 : $form['handicap_to']);
						$age_min = ($form['age_from'] === '') ? null : $form['age_from'];
						$str .= $this->setTees($id, $hdcp_from, $hdcp_to, $form['gender'], $age_min, $form['tees_id']);
					}
				}
			}
		}
        return $this->render('tees', [
			'competition' => $competition,
			'data' => $str,
        ]);
    }


}
