<?php
if ( ! function_exists( 'ever_add_field' ) ):
    function ever_add_field( $field, $value ) {
        ob_start();
        $row_classes = [
            'ever-field-row',
            'ever-field-row-' . $field['type'],
        ];

        $wrapper_classes = [
            'ever-field-wrp',
            'ever-field-wrp-' . $field['type'],
        ];


        $row_attributes     = ! empty( $field['row_attributes'] ) ? $field['row_attributes'] : [];
        $wrapper_attributes = ! empty( $field['wrapper_attributes'] ) ? $field['wrapper_attributes'] : [];


        if ( ! empty( $field['dependency'] ) && count( $field['dependency'] ) == 3 ) {
            $row_attributes['data-cond-option']   = $field['dependency'][0];
            $row_attributes['data-cond-operator'] = $field['dependency'][1];
            $row_attributes['data-cond-value']    = $field['dependency'][2];
            $row_classes[]                        = 'ever-dependent-field';

            $wrapper_attributes['data-cond-option']   = $field['dependency'][0];
            $wrapper_attributes['data-cond-operator'] = $field['dependency'][1];
            $wrapper_attributes['data-cond-value']    = $field['dependency'][2];
            $wrapper_classes[]                        = 'ever-dependent-field';
        }

        //row
        $row_attributes['class'] = implode( ' ', $row_classes );
        $row_attribute_string    = implode( ' ', ever_get_custom_attribute( $row_attributes ) );


        echo "<div {$row_attribute_string}>";

        //default field property settings
        $field = wp_parse_args( $field, array(
            'hide_title_col' => false,
            'attributes'     => [],
        ) );

        if ( empty( $field['hide_title_col'] ) || ! empty( $field['title'] ) ) {
            echo '<div class="ever-col-4">';
            echo '<label for="' . $field['id'] . '">';
            echo $field['title'];
            echo '</label>';//.ever-field-title
            echo empty( $field['title_desc'] ) ? '' : $field['title_desc'];
            echo '</div>';//.col-4
        }

        if ( ! empty( $field['tooltip'] ) ) {
            $wrapper_classes[] = 'ever-field-tooltip-wrp';
            $field_classes[]   = 'ever-field-tooltip';
        }


        //wrapper
        $wrapper_attributes['class'] = implode( ' ', $wrapper_classes );
        $wrapper_attribute_string    = implode( ' ', ever_get_custom_attribute( $wrapper_attributes ) );


        //field
        echo "<div {$wrapper_attribute_string}>";

        ever_get_field( $field, $value );


        echo "</div>"; // {$wrapper_attributes}
        echo "</div>"; //$row_attribute_string
        $output = ob_get_contents();
        ob_get_clean();

        return $output;
    }
endif;

if ( ! function_exists( 'ever_get_field' ) ):
    function ever_get_field( $field, $value ) {

        if ( empty( $field['type'] ) ) {
            return;
        }
        $field_classes[]           = empty( $field['class'] ) ? '' : esc_attr( $field['class'] );
        $field_classes[]           = 'ever-field';
        $field_classes[]           = 'ever-field-' . $field['type'];
        $field_attributes          = empty( $field['attributes'] ) ? [] : $field['attributes'];
//        $field_attributes['value'] = $value;
        $field_attributes['id']    = $field['id'];
        $field_attributes['name']  = $field['id'];
        $field_attributes['class'] = implode( ' ', $field_classes );

        $field_attributes_string = implode( ' ', ever_get_custom_attribute( $field_attributes ) );
        var_dump($value);
        switch ( $field['type'] ) {
            case 'text':
            case 'email':
            case 'number':
            case 'hidden':
            case 'tel':
            case 'url':
                echo "<input type='{$field['type']}'  {$field_attributes_string}>";
                break;
            case 'select':
                if ( $field['options'] ) {
                    echo "<select {$field_attributes_string}>";
                    foreach ( $field['options'] as $key => $name ) {
                        echo '<option value="' . $key . '" ' . selected( $value, $key, false ) . '>' . $name . '</option>';
                    }
                    echo "</select>";
                }

                break;
            case 'textarea':
                echo '<textarea ' . $field_attributes_string . '>' . $value . '</textarea>';
                break;
            case 'checkbox':
                if ( $field['options'] ) {
                    echo "<ul class='ever-field-list'>";
                    foreach ( $field['options'] as $key => $label ) {
                        $val = is_array($value) && in_array() : '';
                        echo '<li><label><span class="ever-checkbox"><input type="checkbox" value="1" name="' . $key . ' ' . checked( $val, $key, false ) . '"></span> ' . $label . '</label></li>';
                    }
                    echo "</ul>";
                }
                break;
        }
    }
endif;

if ( ! function_exists( 'ever_get_custom_attribute' ) ):
    function ever_get_custom_attribute( $attr = array() ) {
        $custom_attributes = array();

        if ( ! empty( $attr ) && is_array( $attr ) ) {
            foreach ( $attr as $attribute => $value ) {
                $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
            }
        }

        return $custom_attributes;
    }
endif;