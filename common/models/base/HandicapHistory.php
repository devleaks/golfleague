<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "handicap_history".
 *
 * @property integer $id
 * @property integer $golfer_id
 * @property integer $scorecard_id
 * @property string $update_type
 * @property string $old_handicap
 * @property string $new_handicap
 * @property string $handicap_update
 * @property integer $score
 * @property string $status
 * @property string $created_at
 * @property string $event_date
 *
 * @property Golfer $golfer
 * @property Scorecard $scorecard
 */
class HandicapHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'handicap_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['golfer_id', 'update_type'], 'required'],
            [['golfer_id', 'scorecard_id', 'score'], 'integer'],
            [['old_handicap', 'new_handicap', 'handicap_update'], 'number'],
            [['created_at', 'event_date'], 'safe'],
            [['update_type', 'status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'golfer_id' => Yii::t('golf', 'Golfer ID'),
            'scorecard_id' => Yii::t('golf', 'Scorecard ID'),
            'update_type' => Yii::t('golf', 'Update Type'),
            'old_handicap' => Yii::t('golf', 'Old Handicap'),
            'new_handicap' => Yii::t('golf', 'New Handicap'),
            'handicap_update' => Yii::t('golf', 'Handicap Update'),
            'score' => Yii::t('golf', 'Score'),
            'status' => Yii::t('golf', 'Status'),
            'created_at' => Yii::t('golf', 'Created At'),
            'event_date' => Yii::t('golf', 'Event Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolfer()
    {
        return $this->hasOne(Golfer::className(), ['id' => 'golfer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScorecard()
    {
        return $this->hasOne(Scorecard::className(), ['id' => 'scorecard_id']);
    }
}
