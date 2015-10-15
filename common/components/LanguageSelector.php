<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\BootstrapInterface;

class LanguageSelector extends Component implements BootstrapInterface
{
	const LANG_COOKIE = 'reuse_lang';

    public $supportedLanguages = [];

    public function bootstrap($app)
    {
		$cookies = Yii::$app->request->cookies;
		if ($cookies->has(self::LANG_COOKIE)) {
			if (($cookie = $cookies->get(self::LANG_COOKIE)) !== null) {
			    Yii::$app->language = $cookie->value;
			}
		} else {
	        $preferredLanguage = $app->request->getPreferredLanguage($this->supportedLanguages);
	        $app->language = $preferredLanguage;
		}
    }
}