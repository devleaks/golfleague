<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Practice;

/**
 * PracticeSearch represents the model behind the search form about `common\models\Practice`.
 */
class PracticeSearch extends Practice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'golfer_id', 'course_id', 'start_hole', 'holes', 'tees_id', 'handicap'], 'integer'],
            [['start_time', 'status', 'updated_at', 'created_at'], 'safe'],
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
        $query = Practice::find();

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
            'golfer_id' => $this->golfer_id,
            'course_id' => $this->course_id,
            'start_time' => $this->start_time,
            'start_hole' => $this->start_hole,
            'holes' => $this->holes,
            'tees_id' => $this->tees_id,
            'handicap' => $this->handicap,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
