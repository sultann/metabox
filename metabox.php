<?php
/**
 * Plugin Name:Metabox
 */
require dirname( __FILE__ ) . '/core/class-metabox.php';
$settings = [
    'lazy_loading' => 'true'
];
$fields   = [
    [
        'id'                 => 'text_field',
        'type'               => 'text',
        'title'              => 'Text Field',
        'desc'               => 'This is a description',
        'default'            => 'option-2',
        'help'               => 'This is a help text',
        'class'              => 'custom-class',
        'content'            => 'This is content',
        'tooltip'            => 'This is tooltip',
        'options'            => [
            'option-1' => 'option 1',
            'option-2' => 'option 2',
            'option-3' => 'option 3',
        ],
        'dependency'         => [
            'dep_field',
            '=',
            'null'
        ],
        'before'             => 'This is a before field',
        'after'              => 'This is a after field',
//        'name'               => 'name',
        'wrapper_attributes' => [
            'data-wrapper' => 'data-value'
        ],
        'attributes'         => [
            'maxlength'   => 10,
//            'readyonly'   => 'only-key',
//            'disabled'    => 'only-key',
//            'style'       => 'color:red;',
            'placeholder' => 'This is placeholder'
        ],
        'sanitize'           => 'trim',
        'clone'              => false,
    ],
    [
        'id'                 => 'select_field',
        'type'               => 'select',
        'title'              => 'Text Field',
        'desc'               => 'This is a description',
        'default'            => 'option-2',
        'help'               => 'This is a help text',
        'class'              => 'custom-class',
        'content'            => 'This is content',
        'tooltip'            => 'This is tooltip',
        'options'            => [
            'option-1' => 'option 1',
            'option-2' => 'option 2',
            'option-3' => 'option 3',
        ],
        'dependency'         => [
            'dep_field',
            '=',
            'null'
        ],
        'before'             => 'This is a before field',
        'after'              => 'This is a after field',
        'wrapper_attributes' => [
            'data-wrapper' => 'data-value'
        ],
        'attributes'         => [
            'multiple'    => 'multiple',
            'maxlength'   => 10,
            'placeholder' => 'This is placeholder'
        ],
        'sanitize'           => 'trim',
        'clone'              => false,
    ],
    [
        'type'       => 'textarea',
        'title'      => 'textarea-field',
        'id'         => 'textarea-field',
        'default'    => 'lorem ipsum',
        'attributes' => [
            'row' => '100',
            'col' => '100',
        ],
    ],
    [
        'type'  => 'checkbox',
        'id'    => 'checkbox-field',
        'title' => 'checkbox-field',
        'default' => ['option-1', 'option-2'],
        'options' => [
            'option-1' => 'option 1',
            'option-2' => 'option 2',
            'option-3' => 'option 3',
        ],
    ]
];

Pluginever_Metabox::instance( $settings, $fields );