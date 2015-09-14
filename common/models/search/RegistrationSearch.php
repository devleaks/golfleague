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
	public $tee_time;
	public $golfer_name;
	public $competition_name;
	public $competition_type;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'competition_id', 'golfer_id', 'tees_id'], 'integer'],
            [['status', 'created_at', 'updated_at', 'note'], 'safe'],
            [['golfer_name', 'competition_name', 'competition_type', 'tee_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'competition_name' => Yii::t('golf', 'Competition'),
            'competition_type' => Yii::t('golf', 'Competition Type'),
            'golfer_name' => Yii::t('golf', 'Golfer'),
        ] + parent::attributeLabels();
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
		$query->joinWith(['golfer','competition', 'flight']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->sort->attributes['tee_time'] = [
		    'asc'  => ['flight.start_time' => SORT_ASC],
		    'desc' => ['flight.start_time' => SORT_DESC],
		];

		$dataProvider->sort->attributes['golfer_name'] = [
		    'asc'  => ['golfer.name' => SORT_ASC],
		    'desc' => ['golfer.name' => SORT_DESC],
		];

		$dataProvider->sort->attributes['competition_name'] = [
		    'asc'  => ['competition.name' => SORT_ASC],
		    'desc' => ['competition.name' => SORT_DESC],
		];

		$dataProvider->sort->attributes['competition_type'] = [
		    'asc'  => ['competition.competition_type' => SORT_ASC],
		    'desc' => ['competition.competition_type' => SORT_DESC],
		];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'registration.id' => $this->id,
            'registration.competition_id' => $this->competition_id,
            'registration.golfer_id' => $this->golfer_id,
            'registration.tees_id' => $this->tees_id,
            'registration.created_at' => $this->created_at,
            'registration.updated_at' => $this->updated_at,
            'competition.competition_type' => $this->competition_type,
        ]);

        $query->andFilterWhere(['like', 'registration.status', $this->status])
            ->andFilterWhere(['like', 'registration.note', $this->note]);

        $query->andFilterWhere(['like', 'golfer.name', $this->golfer_name]);
        $query->andFilterWhere(['like', 'competition.name', $this->competition_name]);

        return $dataProvider;
    }
}
