<?php
return array (
    'administrator' => array (
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '',
        'bizRule' => NULL,
        'data' => NULL,
        'children' => array(
            'moderator',
        )
    ),
    'moderator' => array (
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '',
        'bizRule' => NULL,
        'data' => NULL,
    ),
);
