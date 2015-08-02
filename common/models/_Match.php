<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "match".
 *
 * @property integer $id
 * @property integer $competition_id
 * @property integer $flight_id
 * @property integer $comp1
 * @property integer $comp2
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Flight $flight
 * @property Competition $competition
 */
class _Match extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'match';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['competition_id'], 'required'],
            [['competition_id', 'flight_id', 'comp1', 'comp2'], 'integer'],
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
            'competition_id' => Yii::t('golf', 'Competition ID'),
            'flight_id' => Yii::t('golf', 'Flight ID'),
            'comp1' => Yii::t('golf', 'Comp1'),
            'comp2' => Yii::t('golf', 'Comp2'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFlight()
    {
        return $this->hasOne(Flight::className(), ['id' => 'flight_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetition()
    {
        return $this->hasOne(Competition::className(), ['id' => 'competition_id']);
    }
}
