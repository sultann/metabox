<?php
$fields = [
    'html',
    'title',
    'separator',

    'text',
    'url',
    'number',
    'password',
    'textarea',
    'email',
    'wysiwyg',

    'select', //single+multiple, with select2
    'radio', //inline + list style
    'checkbox', //inline + list style
    'switcher', //inline + list style
    'date', //inline + list style
    'date_time', //inline + list style
    'time', //inline + list style
    'colorpicker', //inline + list style
    'textlist', //inline + list style

    'upload', //single multiple
    'image_select', //single multiple

    'taxonomy', //with sorting //select multiple or single
    'post', //with sorting //select multiple or single choosen plugin

    'hidden'
];

$settings = [
    'title'        => __( 'Example Metabox', 'pluginever_framework' ),
    'screen'       => 'post',   //or array( 'post-type1', 'post-type2')
    'context'      => 'normal', //('normal', 'advanced', or 'side')
    'priority'     => 'high',
    'lazy_loading' => 'false',
];

$field_options = [
    'id'                 => '',
    'type'               => '',
    'title'              => '',
    'desc'               => '',
    'default'            => '',
    'help'               => '',
    'class'              => '',
    'content'            => '',
    'tooltip'            => '',
    'title_desc'         => '',
    'options'            => [

    ],
    'dependency'         => [
        'id',
        'operator',
        'value'
    ],
    'before'             => '',
    'after'              => '',
    'name'               => '',
    'wrapper_attributes' => [],
    'attirbutes'         => [
        'maxlength'   => 10,
        'placeholder' => 'do stuff',
        'readyonly'   => 'only-key',
        'disabled'    => 'only-key',
        'style'       => 'color:red;',
    ],
    'sanitize'           => '',
    'clone'              => false,
];

$fields = [
    [
        'type' => 'html',
        'id'   => 'html',
    ]
];