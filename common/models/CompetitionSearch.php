<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Competition;

/**
 * CompetitionSearch represents the model behind the search form about `common\models\Competition`.
 */
class CompetitionSearch extends Competition
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'course_id', 'holes', 'rule_id', 'age_min', 'age_max', 'parent_id', 'flight_size', 'delta_time'], 'integer'],
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
        $query = Competition::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
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
            'parent_id' => $this->parent_id,
            'flight_size' => $this->flight_size,
            'delta_time' => $this->delta_time,
        ]);

        $query->andFilterWhere(['like', 'competition_type', $this->competition_type])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
