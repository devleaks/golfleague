<?php
/**
 * LatestMessages widget renders the last messages available on the website.
 *
 * @author Pierre M <devleaks.be@gmail.com>
 */

namespace frontend\widgets;

use yii\data\ActiveDataProvider;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;

use common\models\search\GlobalSearch;

class Search extends Widget {
	/** Query string */
	public $query = 5;
	
	/** number of results returned */
	public $result_count = 10;

	public $detail_urls;
	
	public function run() {
        $searchModel = new GlobalSearch();
        $dataProvider = $searchModel->search($this->query);
		
        return $this->render('search', [
            'dataProvider' => $dataProvider,
			'detail_urls' => $this->detail_urls,
        ]);
	}

}