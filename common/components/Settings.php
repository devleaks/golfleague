<?php

namespace common\components;

use common\models\Setting;

use Yii;
use yii\base\Component;

class Settings extends Component
{

    protected function set_int($name, $value, $who) {
		$model = null;
		if($model = Setting::findOne([
			'name' => $name,
			'user_id' => $who
		])) {
			$model->value = $value;
		} else {
			$model = new Setting([
				'name' => $name,
				'value' => $value,
				'user_id' => $who
			]);
		}
		return $model->save();
    }

    protected function get_int($name, $who) {
		if($model = Setting::findOne([
			'name' => $name,
			'user_id' => $who
		])) {
			return $model->value;
		} else {
			return null;
		}
    }

    public function setGlobal($name, $value) {
		return $this->set_int($name, $value, null);
    }

    public function getGlobal($name) {
		return $this->get_int($name, null);
    }

    public function set($name, $value) {
		if(Yii::$app->user->isGuest)
			return;
		return $this->set_int($name, $value, Yii::$app->user->id);
    }

    public function get($name) {
		if(Yii::$app->user->isGuest)
			return;
		return $this->get_int($name, Yii::$app->user->id);
    }

}