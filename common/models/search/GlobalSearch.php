<?php

namespace common\models\search;

use common\models\Event;
use common\models\Message;

use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\data\ActiveDataProvider;

/**
 * This is the model class to perform global fulltext search.
 *
 * create fulltext index competition_ft on competition (name, description);
 * create fulltext index event_ft on event (name, description);
 * create fulltext index message_ft on message (subject, body);
 *
 */
class GlobalSearch extends Model
{
	public $q;

	/**
     * Creates data provider instance with search query applied
     *
     * @param string $q
     *
     * @return ActiveDataProvider
     */
    public function search($q)
    {
		// Search competitions
        $qc = new Query();
		$qc->from(['competition'])
			->select([
				'source' => 'concat("competition")',
				'id',
				'type' => 'competition_type',
				'title' => 'name',
				'text' => 'description',
			])
			->andWhere('MATCH (name,description) AGAINST ( :query )', [':query' => $q])
		;

		// Search events
	    $qe = new Query();
		$qe->from(['event'])
			->select([
				'source' => 'concat("event")',
				'id',
				'type' => 'event_type',
				'title' => 'name',
				'text' => 'description',
			])
			->andWhere('MATCH (name,description) AGAINST ( :query )', [':query' => $q])
		;

		// Search messages
		$qm = new Query();
		$qm->from(['message'])
			->select([
				'source' => 'concat("message")',
				'id',
				'type' => 'message_type',
				'title' => 'subject',
				'text' => 'body',
			])
			->andWhere('MATCH (subject,body) AGAINST ( :query )', [':query' => $q])
		;
		
		$dataProvider = new ActiveDataProvider([
            'query' => $qc->union($qe)->union($qm),
        ]);

		return $dataProvider;
    }
}
