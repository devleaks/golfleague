<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "history".
 *
 * @property integer $id
 * @property string $object_type
 * @property integer $object_id
 * @property string $action
 * @property string $note
 * @property integer $performer_id
 * @property string $created_at
 * @property string $payload
 * @property string $summary
 */
class _History extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_type', 'payload'], 'string'],
            [['object_id', 'performer_id'], 'integer'],
            [['action'], 'required'],
            [['created_at'], 'safe'],
            [['action'], 'string', 'max' => 40],
            [['note'], 'string', 'max' => 160],
            [['summary'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'object_type' => Yii::t('golf', 'Object Type'),
            'object_id' => Yii::t('golf', 'Object ID'),
            'action' => Yii::t('golf', 'Action'),
            'note' => Yii::t('golf', 'Note'),
            'performer_id' => Yii::t('golf', 'Performer ID'),
            'created_at' => Yii::t('golf', 'Created At'),
            'payload' => Yii::t('golf', 'Payload'),
            'summary' => Yii::t('golf', 'Summary'),
        ];
    }
}
