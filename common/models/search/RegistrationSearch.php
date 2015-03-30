<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Registration;

/**
 * RegistrationSearch represents the model behind the search form about `common\models\Registration`.
 */
class RegistrationSearch extends Registration
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'competition_id', 'golfer_id', 'flight_id', 'tees', 'position', 'score', 'points', 'team_id', 'score_net'], 'integer'],
            [['status', 'created_at', 'updated_at', 'note'], 'safe'],
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
        $query = Registration::find();

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
            'flight_id' => $this->flight_id,
            'tees' => $this->tees,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'position' => $this->position,
            'score' => $this->score,
            'points' => $this->points,
            'team_id' => $this->team_id,
            'score_net' => $this->score_net,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
