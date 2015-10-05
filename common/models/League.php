<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "league".
 */
class League extends _League
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'League'),
            'name' => Yii::t('golf', 'Name'),
            'phone' => Yii::t('golf', 'Phone'),
            'email' => Yii::t('golf', 'Email'),
            'website' => Yii::t('golf', 'Website'),
            'units' => Yii::t('golf', 'Units'),
            'owner_id' => Yii::t('golf', 'Owner'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
        ];
    }
}
