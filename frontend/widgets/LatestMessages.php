<?php
/**
 * LatestMessages widget renders the last messages available on the website.
 *
 * @author Pierre M <devleaks.be@gmail.com>
 */

namespace frontend\widgets;

use yii\data\ActiveDataProvider;
use yii\bootstrap\Widget;
use common\models\Message;

class LatestMessages extends Widget {
	/** number of recent message to display */
	public $message_count = 5;
	
	/** Display messages from home club only. (and general messages from Yii golf League.) */
	public $home_club_only;
	

	public function run() {
		$q = Message::find();
		
		if($this->home_club_only && !Yii::$app->user->isGuest) {
			$golfer = Golfer::me();
			if($golfer->facility_id)
				$q->andWhere(['facility_id' => [0, $golfer->facility_id]]);
		}

        return $this->render('latest-message', [
            'dataProvider' => new ActiveDataProvider([
				'query' => $q->orderBy('created_at desc'),
				'pagination' => [
			        'pageSize' => $this->message_count,
				],
			]),
        ]);
	}

}