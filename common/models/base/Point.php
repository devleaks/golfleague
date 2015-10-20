<?php

namespace common\models\base;

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
class Point extends \yii\db\ActiveRecord
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
            'id' => Yii::t('golf', 'ID'),
            'rule_id' => Yii::t('golf', 'Rules'),
            'position' => Yii::t('golf', 'Position'),
            'points' => Yii::t('golf', 'Points'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRules()
    {
        return $this->hasOne(\common\models\Rule::className(), ['id' => 'rule_id']);
    }
}
