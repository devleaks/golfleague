<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "media".
 */
class Media extends _Media
{
    /**
     * Maximum size of images associated with ads
     * @var integer
     */
    const PICTURE_SIZE   = 400; // px;

    /**
     * Maximum size of thumbnail images associated with ads
     * @var integer
     */
    const THUMBNAIL_SIZE = 150; // px;

    /**
     * Maximum size of thumbnail images associated with ads
     * @var integer
     */
    const MEDIA_ROOT_URL = '/uploads';

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); /* mysql datetime format is ‘AAAA-MM-JJ HH:MM:SS’*/},
                ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('golf', 'Media'),
            'name' => Yii::t('golf', 'Name'),
            'size' => Yii::t('golf', 'Size'),
            'type' => Yii::t('golf', 'Type'),
            'related_id' => Yii::t('golf', 'Related ID'),
            'related_class' => Yii::t('golf', 'Related Class'),
            'related_attribute' => Yii::t('golf', 'Related Attribute'),
            'name_hash' => Yii::t('golf', 'Name Hash'),
            'status' => Yii::t('golf', 'Status'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
        ];
    }

    public function init() {
        parent::init();
        $dirPath = $this->getDirPath();
        if (!is_dir($dirPath)) {
            FileHelper::createDirectory($dirPath);
        }
    }

    protected function getDirPath() {
        return \Yii::getAlias('@common/uploads');
    }

    public function getFilePath() {
		$oClass = new \ReflectionClass($this->related_class);
		$dirname = strtolower($oClass->getShortName());
        $fp = $this->getDirPath() . DIRECTORY_SEPARATOR . $dirname . DIRECTORY_SEPARATOR . $this->related_id . DIRECTORY_SEPARATOR . $this->name;
		$dn = dirname($fp);
        if (!is_dir($dn)) {
            FileHelper::createDirectory($dn);
        }
		return $fp;
    }

    public function getThumbnailPath() {
		$oClass = new \ReflectionClass($this->related_class);
		$dirname = strtolower($oClass->getShortName());
        $fp = $this->getDirPath() . DIRECTORY_SEPARATOR . $dirname . DIRECTORY_SEPARATOR . $this->related_id . DIRECTORY_SEPARATOR . '_' . $this->name;
		$dn = dirname($fp);
        if (!is_dir($dn)) {
            FileHelper::createDirectory($dn);
        }
		return $fp;
    }

    protected function getDirUrl() {
        return self::MEDIA_ROOT_URL;
    }

    public function getFileUrl() {
		$oClass = new \ReflectionClass($this->related_class);
		$dirname = strtolower($oClass->getShortName());
        return $this->getDirUrl() . DIRECTORY_SEPARATOR . $dirname . DIRECTORY_SEPARATOR . $this->related_id . DIRECTORY_SEPARATOR . $this->name;
    }

    public function getThumbnailUrl() {
		$oClass = new \ReflectionClass($this->related_class);
		$dirname = strtolower($oClass->getShortName());
        return $this->getDirUrl() . DIRECTORY_SEPARATOR . $dirname . DIRECTORY_SEPARATOR . $this->related_id . DIRECTORY_SEPARATOR . '_' . $this->name;
    }

    public function beforeSave($insert) {
        $return = parent::beforeSave($insert);
        if ($insert) {
	        $pi = pathinfo($this->name);
	        $ext = strtolower(ArrayHelper::getValue($pi, 'extension'));

            // $ext = end((explode(".", $this->name)));
            $this->name_hash = \Yii::$app->security->generateRandomString() . ".{$ext}";
        }
        return $return;
    }

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            return unlink($this->getFilePath());
        } else {
            return false;
        }
    }

    public static function findByNameHash($nameHash) {
        return static::find()->andWhere(['name_hash' => $nameHash])->one();
    }


	public function generateThumbnail() {
		$imagePath = $this->getFilepath();
		$pic = Yii::$app->image->load($imagePath);
		$thumbPath = $this->getThumbnailPath();
		//Yii::trace('Image:'.$pic->width.' X '.$pic->height.'.', 'Media::generateThumbnail');
		if($pic->width > self::THUMBNAIL_SIZE || $pic->height > self::THUMBNAIL_SIZE) {
			$ratio = ($pic->width > $pic->height) ? $pic->width / self::THUMBNAIL_SIZE : $pic->height / self::THUMBNAIL_SIZE;
			$newidth  = round($pic->width  / $ratio);
			$neheight = round($pic->height / $ratio);
			$pic->resize($newidth, $neheight);
			$pic->save($thumbPath);
		}	
		if($pic->width > self::PICTURE_SIZE || $pic->height > self::PICTURE_SIZE) {
		    $ratio = ($pic->width > $pic->height) ? $pic->width / self::PICTURE_SIZE : $pic->height / self::PICTURE_SIZE;
		    $newidth  = $pic->width  / $ratio;
		    $neheight = $pic->height / $ratio;
		    $pic->resize($newidth, $neheight);
		    $pic->save();
		}
	}

    /**
     * Creates a [[\app\models\File]] based on the [[yii\web\UploadedFile]]
     * @param UploadedFile $uploadedFile
     * @param $relatedModel
     * @param $relatedAttribute
     * @return bool
     */
    public static function makeByUploadedFile(UploadedFile $uploadedFile, $relatedModel, $relatedAttribute) {
        $file = new static();
        $file->name = $uploadedFile->name;
        $file->size = $uploadedFile->size;
        $file->type = $uploadedFile->type;
        $file->related_id = $relatedModel->id;
        $file->related_class = get_class($relatedModel);
        $file->related_attribute = $relatedAttribute;
        $saved = $file->save();
        if( $ret = ($saved && $uploadedFile->saveAs($file->getFilePath())) && in_array($file->type, ['image/jpeg', 'image/png']) )
			$file->generateThumbnail();
		return $ret;
    }

}
