<?php
require dirname( __FILE__ ) . '/functions.php';
if ( ! class_exists( 'Pluginever_Metabox' ) ):
    class Pluginever_Metabox {
        /**
         * @var array
         */
        public $settings = array();

        /**
         * @var array
         */
        public $fields = array();

        /**
         * @var null
         */
        private static $instance = null;

        /**
         * Metabox constructor.
         *
         * @param $settings
         * @param $fields
         */
        public function __construct( $settings, $fields ) {

            $this->settings = $this->prepare_settings( apply_filters( 'ever_metabox_settings', $settings ) );
            $this->fields   = apply_filters( 'ever_metabox_fields', $fields );

            if ( ! empty( $this->fields ) ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ), 15 );
                add_action( 'add_meta_boxes', array( $this, 'register_metabox' ) );
                add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
            }

        }


        /**
         * @param       $settings
         * @param array $fields
         *
         * @return null
         */
        public static function instance( $settings, $fields = array() ) {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self( $settings, $fields );
            }

            return self::$instance;
        }

        /**
         * Enqueue script and styles in admin side
         *
         * Add style and scripts to administrator
         *
         * @return void
         */
        public function load_assets() {

        }

        /**
         * Register the metabox
         *
         * call the wp function add_metabox to add the metabox
         *
         * @param $post_type
         *
         * @return void
         */
        public function register_metabox( $post_type ) {
            if ( in_array( $post_type, (array) $this->settings['post_type'] ) ) {
                add_meta_box( $this->settings['id'], $this->settings['title'], array( &$this, 'render_meta_box' ), $this->settings['post_type'], $this->settings['context'], $this->settings['priority'], $this->fields );
            }
        }

        public function render_meta_box( $post, $settings ) {
            $meta_value = get_post_meta( $post->ID );
            ?>
            <div class="ever-framework ever-metabox-framework <?php echo $this->settings['lazy_loading'] !== 'false' ? 'ever-lazy-loading loading' : ''; ?>" id="<?php echo esc_attr( $settings['id'] ); ?>">
                <?php echo wp_nonce_field( 'ever_fields_nonce', 'ever_metabox_nonce' ); ?>
                <?php do_action( 'ever_metabox_before', $this->settings, $this->fields ); ?>
                <div class="ever-metabox-container">
                    <?php
                    $field_ids = array();
                    foreach ( $this->fields as $field ) {
                        $default    = ( isset( $field['default'] ) ) ? $field['default'] : '';
                        $elem_id    = ( isset( $field['id'] ) ) ? $field['id'] : '';
                        $elem_value = ( is_array( $meta_value ) && isset( $meta_value[ $elem_id ] ) ) ? $meta_value[ $elem_id ] : $default;
                        if ( ! in_array( $elem_id, $field_ids ) ) {
                            echo ever_add_field( $field, $elem_value );
                            $field_ids[] = $elem_id;
                        } else {
                            _e( 'Duplicate Field ID', 'wp_ever_metabox' );
                        }
                    }
                    ?>
                </div>
                <?php do_action( 'ever_metabox_after', $this->settings, $this->fields ); ?>
            </div>
            <?php
        }

        /**
         * Save Post Data
         *
         * Save the post data in the database when save the post
         *
         * @param $post_id
         * @param $post
         *
         * @return int
         */
        public function save_post( $post_id, $post ) {

        }

        /**
         * Sanitizes the settings option
         *
         * @param $settings
         *
         * @return array
         */
        public function prepare_settings( $settings ) {
            $default = array(
                'id'           => 'ever-metabox-' . wp_generate_uuid4(),
                'title'        => __( 'Example Metabox', 'ever_framework' ),
                'post_type'    => 'post',   //or array( 'post-type1', 'post-type2')
                'context'      => 'normal', //('normal', 'advanced', or 'side')
                'priority'     => 'high',
                'lazy_loading' => 'false',
            );

            return wp_parse_args( $settings, $default );
        }

    }


endif;
