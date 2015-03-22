<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Hole;

/**
 * HoleSearch represents the model behind the search form about `app\models\Hole`.
 */
class HoleSearch extends Hole
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tees_id', 'position', 'par', 'si', 'length'], 'integer'],
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
        $query = Hole::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tees_id' => $this->tees_id,
            'position' => $this->position,
            'par' => $this->par,
            'si' => $this->si,
            'length' => $this->length,
        ]);

        return $dataProvider;
    }
}
