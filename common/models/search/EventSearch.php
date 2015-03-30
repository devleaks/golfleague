<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Event;

/**
 * EventSearch represents the model behind the search form about `common\models\Event`.
 */
class EventSearch extends Event
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id'], 'integer'],
            [['object_type', 'name', 'description', 'created_at', 'updated_at', 'event_type', 'status', 'event_start', 'event_end'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Event::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'object_id' => $this->object_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'event_start' => $this->event_start,
            'event_end' => $this->event_end,
        ]);

        $query->andFilterWhere(['like', 'object_type', $this->object_type])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'event_type', $this->event_type])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
