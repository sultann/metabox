# Metabox

## Description
Metabox is a developer's toolkit for building metaboxes, custom fields, and forms for WordPress that will blow your mind. Easily manage meta for custom post types.

You can see a list of available field types [here](https://github.com/sultann/metabox/wiki/field-types#types).


## Installation
1. Place the metabox directory inside of your theme or plugin.
2. Now include the metabox class `require dirname(__FILE__) . '/metabox/class-metabox.php';`.
2. ```php
add_action( 'admin_init', 'add_custom_metabox' );

function(add_custom_metabox){
    $metabox = new \Pluginever\Framework\Metabox( 'html-id' );

		$config  = array(
			'title'        => __( 'Metabox Settings', 'wpcp' ),
			'screen'       => 'post',
			'context'      => 'normal',
			'priority'     => 'high',
			'lazy_loading' => 'true',
			'class'        => 'custom-class',
			'fields'       => [
				[
					'type'  => 'text',
					'label' => __( 'Example Field', 'wpcp' ),
					'name'  => 'example_field',
					'sanitize'  => 'esc_html',
				],

			],
		);

		$metabox->init( $config );
}
```
4. Profit.

**[View CHANGELOG](https://github.com/sultann/metabox/blob/master/CHANGELOG.md)**