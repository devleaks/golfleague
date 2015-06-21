<?php

namespace common\models\search;

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
            [['id', 'parent_id', 'course_id', 'holes', 'rule_id', 'age_min', 'age_max', 'recurrence_id', 'max_players', 'cba', 'tour', 'flight_size', 'flight_time', 'flight_window', 'registration_time', 'final_rule_id'], 'integer'],
            [['competition_type', 'name', 'description', 'status', 'start_date', 'registration_begin', 'registration_end', 'gender', 'player_type', 'registration_special', 'created_at', 'updated_at'], 'safe'],
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

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'start_date' => $this->start_date,
            'course_id' => $this->course_id,
            'holes' => $this->holes,
            'rule_id' => $this->rule_id,
            'registration_begin' => $this->registration_begin,
            'registration_end' => $this->registration_end,
            'handicap_min' => $this->handicap_min,
            'handicap_max' => $this->handicap_max,
            'age_min' => $this->age_min,
            'age_max' => $this->age_max,
            'recurrence_id' => $this->recurrence_id,
            'max_players' => $this->max_players,
            'cba' => $this->cba,
            'tour' => $this->tour,
            'flight_size' => $this->flight_size,
            'flight_time' => $this->flight_time,
            'flight_window' => $this->flight_window,
            'registration_time' => $this->registration_time,
            'final_rule_id' => $this->final_rule_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'competition_type', $this->competition_type])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'player_type', $this->player_type])
            ->andFilterWhere(['like', 'registration_special', $this->registration_special]);

        return $dataProvider;
    }
}
