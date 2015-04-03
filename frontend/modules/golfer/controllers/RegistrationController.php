<?php

namespace frontend\modules\golfer\controllers;

use common\models\Competition;
use common\models\Golfer;
use common\models\Registration;
use common\models\search\RegistrationSearch;
use common\models\search\MatchSearch;
use common\models\search\TournamentSearch;
use common\models\search\SeasonSearch;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Controller;

class RegistrationController extends \frontend\controllers\DefaultController
{
    /**
     * Displays all matches and a possibility to register or not.
     * @return mixed Match view
     */
    public function actionIndex() {
        $registrationSearchModel = new RegistrationSearch();
        $registrationDataProvider = $registrationSearchModel->search(Yii::$app->request->queryParams);
		$registrationDataProvider->query->andWhere(['golfer_id' => Golfer::me()]);

		// single matches (not part of a tournament)
		$matchesSearchModel = new MatchSearch();
        $matchesDataProvider = $matchesSearchModel->search(Yii::$app->request->queryParams);
		$matchesDataProvider->query->andWhere(['parent_id' => null]);

		// single tournaments not part of a season
		/*
select c.id as tournament_id, count(c.id) as tot_count from competition c, competition m
where c.competition_type = 'TOURNAMENT'
and m.competition_type = 'MATCH'
and m.parent_id = c.id
and c.status = 'OPEN'
group by c.id
having tot_count = 1
		*/
		$q = new Query();
		  $q->select(['c.id as competition_id', 'count(c.id) as tot_count'])
			->from('competition c, competition m')
			->andWhere(['c.parent_id' => null])										// tournament is not part of a 'season'
			->andWhere(['c.competition_type' => Competition::TYPE_TOURNAMENT])
			->andWhere(['m.competition_type' => Competition::TYPE_MATCH])
			->andWhere('m.parent_id = c.id')
			->andWhere(['c.status' => Competition::STATUS_OPEN])
			->andWhere(['m.status' => Competition::STATUS_OPEN])
			->groupBy('c.id')
			;

		$matches_id = [];
		foreach($q->each() as $match)
			$matches_id[] = $match['competition_id'];

		$tournamentsSearchModel = new TournamentSearch();
        $tournamentsDataProvider = $tournamentsSearchModel->search(Yii::$app->request->queryParams);

		if(count($matches_id)>0)
			$tournamentsDataProvider->query->andWhere(['id' => $matches_id]);
		

		// seasons
		$seasonsSearchModel = new SeasonSearch();
        $seasonsDataProvider = $seasonsSearchModel->search(Yii::$app->request->queryParams);
		$seasonsDataProvider->query->andWhere(['status' => Competition::STATUS_OPEN]);


        return $this->render('index', [
            'registrationSearchModel'  => $registrationSearchModel,
            'registrationDataProvider' => $registrationDataProvider,

            'matchesSearchModel'  => $matchesSearchModel,
            'matchesDataProvider' => $matchesDataProvider,

            'tournamentsSearchModel'  => $tournamentsSearchModel,
            'tournamentsDataProvider' => $tournamentsDataProvider,

            'seasonsSearchModel'  => $seasonsSearchModel,
            'seasonsDataProvider' => $seasonsDataProvider,

        ]);
    }

    /**
     * Displays a single Competition model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($competition_id)
    {
		$model = Registration::findOne($competition_id);
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
