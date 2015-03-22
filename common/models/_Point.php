<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "point".
 *
 * @property integer $id
 * @property integer $rule_id
 * @property integer $position
 * @property integer $points
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Rule $rules
 */
class _Point extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'point';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rule_id', 'position', 'points'], 'integer'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'ID'),
            'rule_id' => Yii::t('igolf', 'Rules'),
            'position' => Yii::t('igolf', 'Position'),
            'points' => Yii::t('igolf', 'Points'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRules()
    {
        return $this->hasOne(Rule::className(), ['id' => 'rule_id']);
    }
}
