<?php

namespace frontend\modules\golfer\controllers;

use Yii;
use frontend\controllers\DefaultController;
use common\models\Competition;
use common\models\search\CompetitionSearch;
use common\models\Match;
use common\models\search\MatchSearch;
use common\models\Registration;
use common\models\Season;
use common\models\Tournament;
use common\models\rule\RuleMatchStandard;
use common\models\rule\RuleCompetitionStandard;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * CompetitionController implements the CRUD actions for Competition model.
 */
class CompetitionController extends DefaultController
{
    /**
     * Lists all Competition models.
     * @return mixed
     */
    public function actionIndex()
    {
		$now = date('Y-m-d H:i:s');

		/** Competition open for registration by golfer.
		 */
		$registrationSearch = new CompetitionSearch();
        $registrationProvider = new ActiveDataProvider([
            'query' => Competition::find()->where(['status' => Competition::STATUS_OPEN])
			  							  ->andWhere(['>','registration_begin', $now])
										  ->andWhere(['<','registration_end', $now]),
        ]);

		/** Competition ready to be played.
	 	 */
		$startSearch = new CompetitionSearch();
		$startProvider = new ActiveDataProvider([
            'query' => Match::find()->where(['status' => Competition::STATUS_READY])
										  ->andWhere(['<','registration_end', $now])
										  ->andWhere(['>','start_date', $now]),
        ]);

		/** Competition for results.
		 */
		$resultSearch = new MatchSearch();
		$resultProvider = new ActiveDataProvider([
            'query' => Match::find()->where(['status' => Competition::STATUS_READY])
//										  ->andWhere(['<=','start_date', $now]),
        ]);

		/** Competition awaiting results entry.
		 *  Registration must be READY and in the past (terminated).
		 */
		$result2Search = new CompetitionSearch();
		$result2Provider = new ActiveDataProvider([
            'query' => Competition::find()->andWhere(['status' => Competition::STATUS_OPEN])
										  ->andWhere(['!=','competition_type', Competition::TYPE_MATCH]),
        ]);

		/** Planned competition.
		 *  Registration must be OPEN and we must be before the registration 
		 */
		$planSearch = new CompetitionSearch();
		$planProvider = new ActiveDataProvider([
            'query' => Competition::find()->where(['status' => Competition::STATUS_OPEN])
										  ->andWhere(['>','registration_begin', $now]),
        ]);

		/** Closed or terminated competition.
		 *  Registration must be CLOSED. 
		 */
		$closedSearch = new CompetitionSearch();
		$closedProvider = new ActiveDataProvider([
            'query' => Competition::find()->where(['status' => Competition::STATUS_CLOSED]),
        ]);

		$allSearch = new CompetitionSearch();
		$allProvider = new ActiveDataProvider([
            'query' => Competition::find(),
        ]);

    	return $this->render('index', [
	        'registrationProvider'  => $registrationProvider,
	        'registrationSearch'    => $registrationSearch,
	        'startProvider'  => $startProvider,
	        'startSearch'    => $startSearch,
	        'resultProvider' => $resultProvider,
	        'resultSearch'   => $resultSearch,
	        'result2Provider' => $result2Provider,
	        'result2Search'   => $result2Search,
	        'planProvider'   => $planProvider,
	        'planSearch'     => $planSearch,
	        'closedProvider' => $closedProvider,
	        'closedSearch'   => $closedSearch,
        ]);
    }

    /**
     * Displays a single Competition model.
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
     * Finds the Competition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Competition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Competition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
