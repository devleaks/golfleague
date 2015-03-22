<?php

namespace backend\modules\results\controllers;

class CompetitionController extends \backend\controllers\DefaultController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLeaderboard()
    {
        return $this->render('leaderboard');
    }

    public function actionStanding()
    {
        return $this->render('standing');
    }

    public function actionView()
    {
        return $this->render('view');
    }

}
