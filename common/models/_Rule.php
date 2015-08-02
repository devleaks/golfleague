<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rule".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $competition_type
 * @property string $source_type
 * @property string $source_direction
 * @property string $destination_type
 * @property string $rule_type
 * @property integer $handicap
 * @property integer $team
 * @property string $classname
 * @property string $parameters
 * @property string $note
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $destination_format
 *
 * @property Competition[] $competitions
 * @property Competition[] $competitions0
 * @property Point[] $points
 */
class _Rule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['competition_type'], 'required'],
            [['handicap', 'team'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'classname'], 'string', 'max' => 80],
            [['description', 'parameters'], 'string', 'max' => 255],
            [['competition_type', 'source_direction', 'status'], 'string', 'max' => 20],
            [['source_type', 'destination_type', 'rule_type', 'destination_format'], 'string', 'max' => 40],
            [['note'], 'string', 'max' => 160],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'name' => Yii::t('golf', 'Name'),
            'description' => Yii::t('golf', 'Description'),
            'competition_type' => Yii::t('golf', 'Competition Type'),
            'source_type' => Yii::t('golf', 'Source Type'),
            'source_direction' => Yii::t('golf', 'Source Direction'),
            'destination_type' => Yii::t('golf', 'Destination Type'),
            'rule_type' => Yii::t('golf', 'Rule Type'),
            'handicap' => Yii::t('golf', 'Handicap'),
            'team' => Yii::t('golf', 'Team'),
            'classname' => Yii::t('golf', 'Classname'),
            'parameters' => Yii::t('golf', 'Parameters'),
            'note' => Yii::t('golf', 'Note'),
            'status' => Yii::t('golf', 'Status'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'destination_format' => Yii::t('golf', 'Destination Format'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetitions()
    {
        return $this->hasMany(Competition::className(), ['rule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetitions0()
    {
        return $this->hasMany(Competition::className(), ['final_rule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoints()
    {
        return $this->hasMany(Point::className(), ['rule_id' => 'id']);
    }
}
