<?php

namespace backend\modules\admin\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public $layout = '@backend/views/layouts/main';

	public function actionIndex()
    {
        return $this->render('index');
    }
}
