<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Point;

/**
 * PointSearch represents the model behind the search form about `app\models\Point`.
 */
class PointSearch extends Point
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rule_id', 'position', 'points'], 'integer'],
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
        $query = Point::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'rule_id' => $this->rule_id,
            'position' => $this->position,
            'points' => $this->points,
        ]);

        return $dataProvider;
    }
}
