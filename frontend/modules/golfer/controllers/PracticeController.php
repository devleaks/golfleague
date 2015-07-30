<?php

namespace frontend\modules\golfer\controllers;

use Yii;
use common\models\Course;
use common\models\Golfer;
use common\models\Practice;
use common\models\Score;
use common\models\Scorecard;
use common\models\search\PracticeSearch;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PracticeController implements the CRUD actions for Practice model.
 */
class PracticeController extends Controller
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
     * Lists all Practice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PracticeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (! $golfer = Golfer::me() ) {
            throw new NotFoundHttpException('You are not a golfer.');
        }

		$dataProvider->query->andWhere(['golfer_id' => $golfer->id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Practice model.
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
     * Creates a new Practice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Practice();

		$golfer = Golfer::me();
		$model->golfer_id = $golfer->id;
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Practice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

	public function actionUpdateScores($id) {
		$practice = $this->findModel($id);
		$scorecard = $practice->getScorecard(true);
		
		if(isset($_POST['Score'])) {
			$count = 0;
			foreach (Yii::$app->request->post('Score') as $k => $dataToLoad) {
				$pk = explode('_', $k);
				if($model = Score::findOne(['scorecard_id'=>$pk[0], 'hole_id' =>$pk[1]])) {
	                $ret = $model->setAttributes($dataToLoad);
	                if ($model->save()) {
	                    $count++;
	                }
				}
			}
			if($count > 0) {
				Yii::$app->session->setFlash('success', Yii::t('golf', 'Scorecard updated.'));
			}
		}

		$scorecard->makeScores();
		
        return $this->render('update', [
			'model' => $practice
        ]);
	}

    /**
     * Deletes an existing Practice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		foreach($model->getScorecards()->each() as $scorecard) {
			$scorecard->delete();
		}
		$model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Practice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Practice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Practice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Scorecard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Scorecard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findScorecard($id)
    {
        if (($model = Scorecard::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionTees() {
	    $out = [];
	    if (isset($_POST['depdrop_parents'])) {
	        $parents = $_POST['depdrop_parents'];
	        if ($parents != null) {
	            $course_id = $parents[0];
				if($course = Course::findOne($course_id)) {
					$selected = null;
					foreach($course->getTeesWithHoles() as $tees) {
						if(!$selected) $selected = $tees->id;
						$out[] = ['id' => $tees->id, 'name' => $tees->name];
					}
				}
	            echo Json::encode(['output'=>$out, 'selected'=>'']);
	            return;
	        }
	    }
	    echo Json::encode(['output'=>'', 'selected'=>'']);
	}

}
