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
		$registrationDataProvider->query->andWhere(['golfer_id' => Golfer::me()->id]);

		$now = date('Y-m-d H:i:s');

		// single matches (not part of a tournament)
		$matchesSearchModel = new MatchSearch();
        $matchesDataProvider = $matchesSearchModel->search(Yii::$app->request->queryParams);
		$matchesDataProvider->query->andWhere(['parent_id' => null])
								   ->andWhere(['>', 'start_date', $now]);

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
			->andWhere(['>', 'm.start_date', $now])
			->groupBy('c.id')
			;

		$tournament_ids = [];
		foreach($q->each() as $tournament)
			$tournament_ids[] = $tournament['competition_id'];

		$tournamentsSearchModel = new TournamentSearch();
        $tournamentsDataProvider = $tournamentsSearchModel->search(Yii::$app->request->queryParams);
		$tournamentsDataProvider->query->andWhere(['id' => $tournament_ids]);
		

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
    public function actionView($id)
    {
		$model = Registration::findOne($id);
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
    public function actionRegister($id) {
        if(!$me = Golfer::me()) {
            Yii::$app->session->setFlash('error', 'You need to be a golfer to register to matches.');            
        } else {
            $model = Competition::findOne($id);
            if ($model->register($me))
                Yii::$app->session->setFlash('success', Yii::t('igolf', 'You registered to competition "{0}".', $model->name));
			// else flash set in register()
        }
        return $this->redirect(Yii::$app->request->getReferrer());
    }

    /**
     * Deregister currently logged user from competition
     * @param  [type] $competition_type Season, Tournament, or Match.
     * @param  [type] $competition_id   Identifier of competition
     * @return [type]                   Action to do after. Set flash on success/error.
     */
    public function actionDeregister($id) {
        if(!$me = Golfer::me()) {
            Yii::$app->session->setFlash('error', 'You need to be a registered golfer of this site to register to matches.');            
        } else { 
            $model = Competition::findOne($id);
            if ($model->deregister($me))
                Yii::$app->session->setFlash('success', Yii::t('igolf', 'You deregistered from competition "{0}".', $model->name));
            else
                Yii::$app->session->setFlash('error', Yii::t('igolf', 'You cannot deregister from competition "{0}".', $model->name));
        }
        return $this->redirect(Yii::$app->request->getReferrer());
    }


}
