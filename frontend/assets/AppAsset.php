<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets';
//	public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
    ];

    public $js = [
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
//		'raoul2000\bootswatch\BootswatchAsset',
    ];

/*
<link rel="apple-touch-icon" sizes="57x57" href="<?= $appasset->baseUrl ?>/images/favicon/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="<?= $appasset->baseUrl ?>/images/favicon/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?= $appasset->baseUrl ?>/images/favicon/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?= $appasset->baseUrl ?>/images/favicon/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?= $appasset->baseUrl ?>/images/favicon/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?= $appasset->baseUrl ?>/images/favicon/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="<?= $appasset->baseUrl ?>/images/favicon/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?= $appasset->baseUrl ?>/images/favicon/apple-touch-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?= $appasset->baseUrl ?>/images/favicon/apple-touch-icon-180x180.png">

<link rel="icon" type="image/png" href="<?= $appasset->baseUrl ?>/images/favicon/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="<?= $appasset->baseUrl ?>/images/favicon/android-chrome-192x192.png" sizes="192x192">
<link rel="icon" type="image/png" href="<?= $appasset->baseUrl ?>/images/favicon/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/png" href="<?= $appasset->baseUrl ?>/images/favicon/favicon-16x16.png" sizes="16x16">

<link rel="manifest" href="<?= $appasset->baseUrl ?>/images/favicon/manifest.json">
<link rel="shortcut icon" href="<?= $appasset->baseUrl ?>/images/favicon/favicon.ico">

<meta name="msapplication-TileColor" content="#00aba9">
<meta name="msapplication-TileImage" content="<?= $appasset->baseUrl ?>/images/favicon/mstile-144x144.png">
<meta name="msapplication-config" content="<?= $appasset->baseUrl ?>/images/favicon/browserconfig.xml">
<meta name="theme-color" content="#ffffff">

*/

	public function registerIcons($view) {
		foreach(['57x57','60x60','72x72','76x76','114x114','120x120','144x144','152x152','180x180'] as $size)
			$view->registerLinkTag(['rel' => 'apple-touch-icon', 'type' => 'image/png', 'href' => $this->baseUrl.'/images/favicon/apple-touch-icon-'.$size.'.png', 'sizes' => $size]);

		$view->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => $this->baseUrl.'/images/favicon/favicon-32x32.png', 'sizes' => '32x32']);
		$view->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => $this->baseUrl.'/images/favicon/android-chrome-192x192.png', 'sizes' => '192x192']);
		$view->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => $this->baseUrl.'/images/favicon/android-chrome-96x96.png', 'sizes' => '96x96']);
		$view->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => $this->baseUrl.'/images/favicon/android-chrome-16x16.png', 'sizes' => '16x16']);
		
		$view->registerLinkTag(['rel' => 'manifest', 'type' => 'image/png', 'href' => $this->baseUrl.'/images/favicon/manifest.json']);
		$view->registerLinkTag(['rel' => 'shortcut icon', 'href' => $this->baseUrl.'/images/favicon/favicon.ico']);

		$view->registerMetaTag([
		    'name' => 'msapplication-TileColor',
		    'content' => '#00aba9'
		]);
		$view->registerMetaTag([
		    'name' => 'msapplication-TileImage',
		   'content' => $this->baseUrl . '/images/favicon/mstile-144x144.png'
		]);
		$view->registerMetaTag([
		   'name' => 'msapplication-config',
		   'content' => $this->baseUrl . '/images/favicon/browserconfig.xml'
		]);
		$view->registerMetaTag([
		   'name' => 'theme-color',
		   'content' => '#ffffff'
		]);
	}
}
