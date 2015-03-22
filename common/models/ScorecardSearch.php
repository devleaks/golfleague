<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Scorecard;

/**
 * ScorecardSearch represents the model behind the search form about `common\models\Scorecard`.
 */
class ScorecardSearch extends Scorecard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'competition_id', 'golfer_id', 'tees'], 'integer'],
            [['note'], 'safe'],
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
        $query = Scorecard::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'competition_id' => $this->competition_id,
            'golfer_id' => $this->golfer_id,
            'tees' => $this->tees,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
