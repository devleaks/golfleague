<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "start".
 *
 * @property integer $id
 * @property string $gender
 * @property integer $age_min
 * @property integer $age_max
 * @property string $handicap_min
 * @property string $handicap_max
 * @property integer $tees_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $competition_id
 *
 * @property Tees $tees
 * @property Competition $competition
 */
class _Start extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'start';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['age_min', 'age_max', 'tees_id', 'competition_id'], 'integer'],
            [['handicap_min', 'handicap_max'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['competition_id'], 'required'],
            [['gender'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'gender' => Yii::t('golf', 'Gender'),
            'age_min' => Yii::t('golf', 'Age Min'),
            'age_max' => Yii::t('golf', 'Age Max'),
            'handicap_min' => Yii::t('golf', 'Handicap Min'),
            'handicap_max' => Yii::t('golf', 'Handicap Max'),
            'tees_id' => Yii::t('golf', 'Tees ID'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'competition_id' => Yii::t('golf', 'Competition ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTees()
    {
        return $this->hasOne(Tees::className(), ['id' => 'tees_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetition()
    {
        return $this->hasOne(Competition::className(), ['id' => 'competition_id']);
    }
}
