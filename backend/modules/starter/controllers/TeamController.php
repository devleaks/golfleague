<?php

namespace backend\modules\starter\controllers;

class TeamController extends \backend\controllers\DefaultController
{
    public function actionCompetition()
    {
        return $this->render('competition');
    }

    public function actionPublish()
    {
        return $this->render('publish');
    }

    public function actionReset()
    {
        return $this->render('reset');
    }

}
