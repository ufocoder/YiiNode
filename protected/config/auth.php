<?php
return array (
    'admin' => array (
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '',
        'bizRule' => NULL,
        'data' => NULL,
        'children' => array(
            'moderator',
            'manager',
            'user',
        )
    ),
    'moderator' => array (
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '',
        'bizRule' => NULL,
        'data' => NULL,
    ),
    'manager' => array (
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '',
        'bizRule' => NULL,
        'data' => NULL,
    ),
    'user' => array (
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '',
        'bizRule' => NULL,
        'data' => NULL,
    ),
);
