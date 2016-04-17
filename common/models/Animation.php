<?php

namespace common\models;

use common\behaviors\ListAll;

use Yii;
use \common\models\base\Animation as BaseAnimation;

/**
 * This is the model class for table "animation".
 */
class Animation extends BaseAnimation
{
	use ListAll;
}
