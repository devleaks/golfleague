<?php

namespace backend\modules\golfer\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
   public function actionIndex()
    {
        return $this->render('index');
    }
}
