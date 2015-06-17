<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rule".
 *
 * @property integer $id
 * @property string $name
 * @property string $competition_type
 * @property string $source_type
 * @property string $source_direction
 * @property string $destination_type
 * @property string $rule_type
 * @property integer $team
 * @property string $description
 * @property string $classname
 * @property string $parameters
 * @property string $note
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
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
            [['team'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'classname'], 'string', 'max' => 80],
            [['competition_type', 'source_direction', 'status'], 'string', 'max' => 20],
            [['source_type', 'destination_type', 'rule_type'], 'string', 'max' => 40],
            [['description', 'parameters'], 'string', 'max' => 255],
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
            'id' => Yii::t('igolf', 'ID'),
            'name' => Yii::t('igolf', 'Name'),
            'competition_type' => Yii::t('igolf', 'Competition Type'),
            'source_type' => Yii::t('igolf', 'Source Type'),
            'source_direction' => Yii::t('igolf', 'Source Direction'),
            'destination_type' => Yii::t('igolf', 'Destination Type'),
            'rule_type' => Yii::t('igolf', 'Rule Type'),
            'team' => Yii::t('igolf', 'Team'),
            'description' => Yii::t('igolf', 'Description'),
            'classname' => Yii::t('igolf', 'Classname'),
            'parameters' => Yii::t('igolf', 'Parameters'),
            'note' => Yii::t('igolf', 'Note'),
            'status' => Yii::t('igolf', 'Status'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
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
        return $this->hasMany(Competition::className(), ['rule_final_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoints()
    {
        return $this->hasMany(Point::className(), ['rule_id' => 'id']);
    }
}
