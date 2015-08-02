<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Round;

/**
 * RoundSearch represents the model behind the search form about `common\models\Match`.
 */
class RoundSearch extends Round
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'course_id', 'holes', 'rule_id', 'age_min', 'age_max'], 'integer'],
            [['competition_type', 'name', 'description', 'start_date', 'registration_begin', 'registration_end', 'gender', 'status', 'created_at', 'updated_at'], 'safe'],
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
        $query = Round::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'course_id' => $this->course_id,
            'holes' => $this->holes,
            'rule_id' => $this->rule_id,
            'start_date' => $this->start_date,
            'registration_begin' => $this->registration_begin,
            'registration_end' => $this->registration_end,
            'handicap_min' => $this->handicap_min,
            'handicap_max' => $this->handicap_max,
            'age_min' => $this->age_min,
            'age_max' => $this->age_max,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'competition_type', $this->competition_type])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
