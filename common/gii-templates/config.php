<?php

$config['modules']['gii']['generators'] = [
    'kartikgii-crud' => [ // generator name
        'class' => 'warrence\kartikgii\crud\Generator', // generator class
        'templates' => [ //setting for out templates
            'devleaks' => '@common/gii-templates/crud/devleaks', // template name => path to template
        ],
	],
    'enhanced-gii-model' => [ // generator name
        'class' => 'mootensai\enhancedgii\model\Generator', // generator class
        'templates' => [ //setting for out templates
            'devleaks' => '@common/gii-templates/model', // template name => path to template
        ],
	],
];
