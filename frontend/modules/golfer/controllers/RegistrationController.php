<?php

namespace backend\modules\golfer\controllers;

use common\models\AllMatches;
use common\models\AllMatchesSearch;
use common\models\Competition;
use common\models\Golfer;
use common\models\Match;
use common\models\MatchSearch;
use common\models\Registration;
use common\models\Season;
use common\models\SeasonSearch;
use common\models\Tournament;
use common\models\TournamentSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;

class RegistrationController extends \backend\controllers\DefaultController
{
    /**
     * Displays all matches and a possibility to register or not.
     * @return mixed Match view
     */
    public function actionIndex()
    {
        $searchModel = new MatchSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => Match::find()->with('parent', 'course'),
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays all seasons and a possibility to register or not.
     * @return mixed Season view
     */
    public function actionSeasons()
    {
        $searchModel = new SeasonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('seasons', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays all tournaments and a possibility to register or not.
     * @return mixed Season view
     */
    public function actionTournaments()
    {
        $searchModel = new TournamentSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => Tournament::find()->with('season'),
        ]);

        return $this->render('tournaments', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Competition model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($competition_id)
    {
		$model = Competition::findOne($competition_id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Register currently logged user to competition
     * @param  [type] $competition_type Season, Tournament, or Match.
     * @param  [type] $competition_id   Identifier of competition
     * @return [type]                   Action to do after. Set flash on success/error.
     */
    public function actionRegister($competition_id) {
        if(!$me = Golfer::me()) {
            Yii::$app->session->setFlash('error', 'You need to be a golfer to register to matches.');            
        } else {
            $model = Competition::findOne($competition_id);
            if ($model->register($me))
                Yii::$app->session->setFlash('success', Yii::t('golfleague', 'You registered to competition "{0}".', $model->name));
        }
        return $this->redirect(Yii::$app->request->getReferrer());
    }

    /**
     * Deregister currently logged user from competition
     * @param  [type] $competition_type Season, Tournament, or Match.
     * @param  [type] $competition_id   Identifier of competition
     * @return [type]                   Action to do after. Set flash on success/error.
     */
    public function actionDeregister($competition_id) {
        if(!$me = Golfer::me()) {
            Yii::$app->session->setFlash('error', 'You need to be a registered golfer of this site to register to matches.');            
        } else { 
            $model = Competition::findOne($competition_id);
            if ($model->deregister($me))
                Yii::$app->session->setFlash('success', Yii::t('golfleague', 'You deregistered from competition "{0}".', $model->name));
            else
                Yii::$app->session->setFlash('error', Yii::t('golfleague', 'You cannot deregister from competition "{0}".', $model->name));
        }
        return $this->redirect(Yii::$app->request->getReferrer());
    }


}
