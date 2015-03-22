<?php

namespace devleaks\golfleague;
use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'devleaks\golfleague\controllers';

    /**
     * @inherit
     * @var string
     */
    public $layout = 'golfleague';

    /**
     * List of tees set colors for traditional starts.
     * @var string[]
     */
    public $tees_colors;

    /**
     * Mappig between Golf League roles and site-wide roles.
     *
     * Internaly, Golf League only uses its own roles, such as admin, manager, starter, scorer and golfer.
     * 
     * @var [type]
     */
    public $league_roles;

    /**
     * Internationalisation data
     * 
     * @var [type]
     */
    public $i18n;

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->setModules([
        	'admin' => [
            	'class' => 'backend\modules\admin\Module',
            ],
            'starter' => [
                'class' => 'backend\modules\starter\Module',
            ],
            'score' => [
                'class' => 'backend\modules\score\Module',
            ],
            'golfer' => [
                'class' => 'backend\modules\golfer\Module',
                //'defaultRoute' => 'registration',
            ],
            'results' => [
                'class' => 'backend\modules\results\Module',
        	],
		]);

        //$this->initI18N();
    }



    /**
     * Internationalisation initialisation
     * 
     */
    public function initI18N()
    {
        if (empty($this->i18n)) {
            $this->i18n = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@devleaks/messages',
                'forceTranslation' => true
            ];
        }
        Yii::$app->i18n->translations['golfleague'] = $this->i18n;
    }

}
