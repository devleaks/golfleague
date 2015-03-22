<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Score;

/**
 * ScoreSearch represents the model behind the search form about `common\models\Score`.
 */
class ScoreSearch extends Score
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scorecard_id', 'hole_id', 'score', 'putts', 'penalty', 'sand'], 'integer'],
            [['note', 'drive', 'regulation', 'approach', 'putt'], 'safe'],
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
        $query = Score::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'scorecard_id' => $this->scorecard_id,
            'hole_id' => $this->hole_id,
            'score' => $this->score,
            'putts' => $this->putts,
            'penalty' => $this->penalty,
            'sand' => $this->sand,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'drive', $this->drive])
            ->andFilterWhere(['like', 'regulation', $this->regulation])
            ->andFilterWhere(['like', 'approach', $this->approach])
            ->andFilterWhere(['like', 'putt', $this->putt]);

        return $dataProvider;
    }
}
