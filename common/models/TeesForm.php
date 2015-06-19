<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\Tees;

/**
 * TeesForm is the model behind the tees selection form.
 */
class TeesForm extends Model
{
    public $handicap_from;
    public $handicap_to;
    public $gender;
    public $tees_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['tees_id'], 'integer'],
            [['gender'], 'in','range' => ['GENTLEMAN','LADY']],
            [['handicap_from', 'handicap_to'], 'number'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTees()
    {
        return Tees::find()->andWhere(['id' => $this->tees_id]);
    }
}
