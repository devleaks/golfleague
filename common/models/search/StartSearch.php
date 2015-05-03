<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Start;

/**
 * StartSearch represents the model behind the search form about `common\models\Start`.
 */
class StartSearch extends Start
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'age_min', 'age_max', 'tees_id', 'competition_id'], 'integer'],
            [['gender', 'created_at', 'updated_at'], 'safe'],
            [['handicap_min', 'handicap_max'], 'number'],
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
        $query = Start::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'age_min' => $this->age_min,
            'age_max' => $this->age_max,
            'handicap_min' => $this->handicap_min,
            'handicap_max' => $this->handicap_max,
            'tees_id' => $this->tees_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'competition_id' => $this->competition_id,
        ]);

        $query->andFilterWhere(['like', 'gender', $this->gender]);

        return $dataProvider;
    }
}
