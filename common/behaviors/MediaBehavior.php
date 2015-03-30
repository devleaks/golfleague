<?php
 
namespace common\behaviors;
 
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\UploadedFile;
 
class MediaBehavior extends Behavior {
    public $mediasAttributes;
 
    public $fileModelClass = 'common\models\Media';
 
    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
        ];
    }
 
    public function __get($name) {
        if (in_array($name, $this->mediasAttributes)) {
            return $this->getMedias($name);
        } else {
            return parent::__get($name);
        }
    }
 
    public function canGetProperty($name, $checkVars = true) {
        if (in_array($name, $this->mediasAttributes)) {
            return true;
        } else {
            return parent::canGetProperty($name, $checkVars);
        }
    }
 
    protected function getMedias($attribute) {
        $class = $this->fileModelClass;
        $query = $class::find();
        $query->primaryModel = $this->owner;
        $query->link = [
            'related_id' => 'id',
        ];
        $query->andWhere(['related_class' => get_class($this->owner)])
              ->andWhere(['related_attribute' => $attribute]);
        return $query->all();
    }
 
    protected function createAndSaveUploadedMedias($attribute) {
        $class = $this->fileModelClass;
        $uploadedMedias = UploadedFile::getInstances($this->owner, $attribute);
        foreach($uploadedMedias as $uploadedFile) {
            $class::makeByUploadedFile(
                $uploadedFile,
                $this->owner,
                $attribute
            );
        }
    }
 
    public function afterUpdate($event) {
        foreach($this->mediasAttributes as $attribute) {
            $this->createAndSaveUploadedMedias($attribute);
        }
    }
}