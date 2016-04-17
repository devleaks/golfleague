<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Story as StoryModel;

/**
 * Story represents the model behind the search form about `common\models\Story`.
 */
class Story extends StoryModel
{
    public function rules()
    {
        return [
            [['id', 'parent_id', 'presentation_id', 'animation_id', 'updated_by', 'created_by'], 'integer'],
            [['story_type', 'title', 'header', 'body', 'animation_parameters', 'animation_data', 'status', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = StoryModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'presentation_id' => $this->presentation_id,
            'animation_id' => $this->animation_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'story_type', $this->story_type])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'header', $this->header])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'animation_parameters', $this->animation_parameters])
            ->andFilterWhere(['like', 'animation_data', $this->animation_data])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
