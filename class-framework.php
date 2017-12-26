<?php

namespace Pluginever\Framework;
if ( ! class_exists( '\Pluginever\Framework\Metabox' ) ):
    class Metabox {
        /**
         * @var object The single instance of the class
         * @since 1.0
         */
        protected static $_instance = array();

        /**
         * @var array An array where are saved all metabox settings options
         *
         * @since 1.0
         */
        private $options = array();

        /**
         * @since 1.0.0
         * @var array
         */
        private $fields = array();

        /**
         * Main Instance
         *
         * @static
         *
         * @param $id
         *
         * @return object Main instance
         *
         * @since  1.0.0
         */
        public static function instance( $id ) {
            if ( ! isset( self::$_instance[ $id ] ) ) {
                self::$_instance[ $id ] = new self( $id );
            }

            return self::$_instance[ $id ];
        }

        /**
         * Metabox constructor.
         *
         * @param string $id
         */
        function __construct( $id = '' ) {
            $this->id = $id;
        }

        /**
         * Init
         *
         * @since 1.0.0
         *
         * set options and tabs, add actions to register metabox, scripts and save data
         *
         * @param array $options
         *
         * @return void
         */
        public function init( $options = array() ) {
            $this->fields = self::set_fields( $options['fields'] );
            unset( $options['fields'] );
            $this->options = self::set_options( $options );
            add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ), 15 );
            add_action( 'add_meta_boxes', array( $this, 'register_metabox' ) );
            add_action( 'save_post', array( $this, 'save_postdata' ) );
        }

        public function get_fields() {
            return $this->fields;
        }

        /**
         * Enqueue script and styles in admin side
         *
         * Add style and scripts to administrator
         *
         * @return void
         */
        public function load_assets() {

            wp_enqueue_media();
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style( 'plvr-framework', plugins_url( 'assets/css/framework.css', __FILE__ ) );
        }

        /**
         * Set metabox options
         *
         * @since 1.0.0
         *
         * @param $options
         *
         * @return array
         */
        protected static function set_options( $options ) {
            $default = array(
                'title'        => __( 'Example Metabox', 'pluginever_framework' ),
                'screen'       => 'post',   //or array( 'post-type1', 'post-type2')
                'context'      => 'normal', //('normal', 'advanced', or 'side')
                'priority'     => 'high',
                'lazy_loading' => 'false',
            );

            return wp_parse_args( $options, $default );

        }

        /**
         * Get default attributes
         *
         * @since 1.0.0
         * @return array
         */
        protected static function get_default_field_attr() {
            return array(
                'type'          => '',
                'name'          => '',
                'id'            => '',
                'label'         => '',
                'value'         => '',
                'placeholder'   => '',
                'sanitize'      => '',
                'help'          => '',
                'class'         => '',
                'wrapper_class' => '',
                'addon'         => '',
                'addon_pos'     => '',
                'required'      => '',
                'options'       => array(),
                'custom_attr'   => array(),
                'condition'     => array(),
                'parsed'        => 'true',
            );
        }


        /**
         * Set fields
         *
         * @since 1.0.0
         *
         * @param $fields
         *
         * @return array
         */
        protected static function set_fields( $fields ) {
            $result = [];
            foreach ( $fields as $field ) {
                $field       = wp_parse_args( $field, self::get_default_field_attr() );
                $field_id    = empty( $field['id'] ) ? $field['name'] : $field['id'];
                $field['id'] = $field_id;

                $result[] = $field;
            }

            return $result;
        }

        /**
         * Register the metabox
         *
         * call the wp function add_metabox to add the metabox
         *
         *
         * @return void
         */
        public function register_metabox( $post_type ) {
            if ( in_array( $post_type, (array) $this->options['screen'] ) ) {
                add_meta_box( $this->id, $this->options['title'], array(
                    $this,
                    'show_metabox'
                ), $post_type, $this->options['context'], $this->options['priority'] );
            }
        }

        /**
         * Save Post Data
         *
         * Save the post data in the database when save the post
         *
         * @param $post_id
         *
         * @return int
         */
        public function save_postdata( $post_id ) {

            if ( ! isset( $_POST['pluginever_metabox_nonce'] ) || ! wp_verify_nonce( $_POST['pluginever_metabox_nonce'], 'pluginever_fields_nonce' ) ) {
                return false;
            }

            if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
                return false;
            }
            if ( ! isset( $_POST['post_type'] ) ) {
                return false;
            }


            foreach ( $this->fields as $key => $field ) {
                if ( in_array( $field['type'], array( 'title' ) ) ) {
                    continue;
                }

                $value = ! empty( $_POST[ $field['id'] ] ) ? trim($_POST[ $field['id'] ]) : '';
                if ( ! empty( $field['sanitize'] ) ) {
                    $function = sanitize_key( $field['sanitize'] );
                    if ( is_callable( $function ) ) {
                        $value = call_user_func( $function, $value );
                    }
                }

                if ( $field['type'] == 'checkbox' ) {
                    add_post_meta( $post_id, $field['id'], $value, true ) || update_post_meta( $post_id, $value, '0' );
                } else if ( $value !== '' ) {
                    add_post_meta( $post_id, $field['id'], $value, true ) || update_post_meta( $post_id, $field['id'], $value );
                } else {
                    delete_post_meta( $post_id, $field['id'] );
                }

            }

        }

        /**
         * @param $post
         */
        public function show_metabox( $post ) {
            global $post_id;
            $post_id = $post->ID;
            ob_start();
            $lazy_loading = $this->options['lazy_loading'] == 'true' ? 'plvr-lazy-loading' : '';
            echo '<div class="plvr-framework' . $lazy_loading . '">';
            echo '<div class="container">';
            echo wp_nonce_field( 'pluginever_fields_nonce', 'pluginever_metabox_nonce' );
            do_action( 'plvr_framework_before_metabox', $post, $this->id );
            foreach ( $this->fields as $field ) {

                $class      = '';
                $attributes = '';
                if ( ! empty( $field['condition'] ) ) {
                    $default    = array(
                        'depend_on'    => '',
                        'depend_value' => '',
                        'depend_cond'  => '', // ==, !=, <=, <, >=, >  available conditions
                    );
                    $conditions = wp_parse_args( $field['condition'], $default );
                    $class      = 'conditional';
                    $attributes = " data-cond-option='{$conditions['depend_on']}' data-cond-value='{$conditions['depend_value']}' data-cond-operator='{$conditions['depend_cond']}' ";
                }
                //if any special wrapper required around field
                $wrapper_class = ! empty( $field['wrapper_class'] ) ? sanitize_key( $field['wrapper_class'] ) : '';
                ?>
                <div class="row plvr-form-field <?php echo $class; ?>" <?php echo $attributes; ?>>
                    <?php if ( '' !== $field['label'] ): ?>
                        <div class="col-4">
                            <label for="<?php echo $field['name']; ?>"><?php echo $field['label'] ?></label>
                        </div>
                    <?php endif; ?>
                    <div class="col <?php echo $wrapper_class; ?>">
                        <?php self::add_field( $post_id, $field ); ?>
                    </div>
                </div>

                <?php
                $output = ob_get_contents();
                ob_get_clean();

                echo $output;
            }

            do_action( 'plvr_framework_after_metabox', $post, $this->id );

            echo '</div>';
            echo '</div>';
        }


        /**
         * Build a input fields
         *
         * @since 1.0.0
         *
         * @param $post_id
         * @param $field
         */
        public static function add_field( $post_id, $field ) {

            if ( empty( $field['parsed'] ) ) {
                $field = wp_parse_args( $field, self::get_default_field_attr() );
            }
            $field_attributes = array_merge( array(
                'name'        => $field['name'],
                'id'          => $field['id'],
                'class'       => $field['class'],
                'placeholder' => $field['placeholder'],
            ), $field['custom_attr'] );

            if ( $field['required'] ) {
                $field_attributes['required'] = 'required';
            }

            $value       = get_post_meta( $post_id, $field['id'], true );
            $saved_value = empty( $value ) ? $field['value'] : $value;

            $custom_attributes = self::get_custom_attribute( $field_attributes );

            switch ( $field['type'] ) {

                case 'text':
                case 'email':
                case 'number':
                case 'hidden':
                case 'url':
                    echo '<input type="' . $field['type'] . '" value="' . esc_attr( $saved_value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';
                    break;

                case 'select':
                    if ( $field['options'] ) {
                        echo '<select ' . implode( ' ', $custom_attributes ) . '>';
                        foreach ( $field['options'] as $key => $value ) {
                            printf( "<option value='%s' %s>%s</option>\n", $key, selected( $saved_value, $key, false ), $value );
                        }
                        echo '</select>';
                    }
                    break;

                case 'textarea':
                    echo '<textarea ' . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $saved_value ) . '</textarea>';
                    break;

                case 'checkbox':
                    echo '<span class="checkbox">';
                    echo '<label for="' . esc_attr( $field_attributes['id'] ) . '">';
                    echo '<input type="checkbox" ' . checked( $saved_value, '1', false ) . ' value="1" ' . implode( ' ', $custom_attributes ) . ' />';
                    echo wp_kses_post( $field['label'] );
                    echo wp_kses_post( $field['help'] );
                    echo '</label>';
                    echo '</span>';
                    break;

                case 'radio':
                    if ( $field['options'] ) {
                        foreach ( $field['options'] as $key => $value ) {
                            echo '<div class="checkbox">';
                            echo '<label><input type="radio" ' . checked( $saved_value, $key, false ) . ' value="' . $key . '" ' . implode( ' ', $custom_attributes ) . ' />' . $value . '&nbsp;</label>';
                            echo '</div>';
                        }
                    }
                    break;

                case 'date':
                    echo '<input type="date" format="dd/mm/yyyy" name="' . esc_attr( $field_attributes['id'] ) . '" id="' . esc_attr( $field_attributes['id'] ) . '" value="' . esc_attr( $saved_value ) . '" ' . implode( ' ', $custom_attributes ) . '>';
                    break;

                default:
                    # code...
                    break;

            }

            if ( ! empty( $field['help'] ) ) {
                echo '<span class="help">' . wp_kses_post( $field['help'] ) . '</span>';
            }
        }


        /**
         * Handles an elements custom attribute
         *
         * @since 1.0.0
         *
         * @param  array $attr as key/value pair
         * @param  array $other_attr as key/value pair
         *
         * @return array
         */
        protected static function get_custom_attribute( $attr = array(), $other_attr = array() ) {
            $custom_attributes = array();

            if ( ! empty( $attr ) && is_array( $attr ) ) {
                foreach ( $attr as $attribute => $value ) {
                    if ( $value != '' ) {
                        $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
                    }
                }
            }

            if ( ! empty( $other_attr ) && is_array( $other_attr ) ) {
                foreach ( $attr as $attribute => $value ) {
                    $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
                }
            }

            return $custom_attributes;
        }


    }


endif;
