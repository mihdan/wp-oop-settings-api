<?php
/**
 * Main Class file for `WP_OSA`
 *
 * Main class that deals with all other classes.
 *
 * @since   1.0.0
 * @package WPOSA
 */

namespace Mihdan\WPOSA;

if ( ! class_exists( __NAMESPACE__ . '\\WPOSA' ) ) :

	/**
	 * WP_OSA.
	 *
	 * WP Settings API Class.
	 *
	 * @since 1.0.0
	 */
	class WPOSA {

		/**
		 * Allowed HTML tags and attributes for wp_kses().
		 */
		private const ALLOWED_HTML = [
			'strong'   => [],
			'b'        => [],
			'i'        => [],
			'code'     => [],
			'ul'       => [],
			'ol'       => [],
			'li'       => [],
			'br'       => [
				'class' => true,
			],
			'fields'   => [],
			'label'    => [
				'for' => true,
			],
			'select'   => [
				'class' => true,
				'name'  => true,
				'id'    => true,
			],
			'option'   => [
				'value'    => true,
				'selected' => true,
			],
			'div'      => [
				'id'    => true,
				'style' => true,
				'class' => true,
			],
			'a'        => [
				'id'      => true,
				'class'   => true,
				'href'    => true,
				'style'   => true,
				'title'   => true,
				'onclick' => true,
				'target'  => true,
			],
			'img'      => [
				'src' => true,
			],
			'p'        => [
				'class' => true,
			],
			'h1'       => [
				'class' => true,
			],
			'h2'       => [
				'class' => true,
			],
			'nav'      => [
				'class'      => true,
				'aria-label' => true,
			],
			'span'     => [
				'class' => true,
				'style' => true,
			],
			'table'    => [
				'class' => true,
			],
			'tbody'    => [
				'class' => true,
			],
			'tr'       => [
				'class' => true,
			],
			'th'       => [
				'class' => true,
			],
			'td'       => [
				'class' => true,
			],
			'textarea' => [
				'name'  => true,
				'class' => true,
				'id'    => true,
				'rows'  => true,
				'cols'  => true,
			],
			'input'    => [
				'id'          => true,
				'class'       => true,
				'type'        => true,
				'name'        => true,
				'value'       => true,
				'placeholder' => true,
				'checked'     => true,
				'readonly'    => true,
				'disabled'    => true,
			],
		];

		/**
		 * Plugin name.
		 *
		 * @var string
		 */
		private string $plugin_name;

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		private string $plugin_version;

		/**
		 * Plugin slug.
		 *
		 * @var string
		 */
		private string $plugin_slug;

		/**
		 * Plugin prefix.
		 *
		 * @var string
		 */
		private string $plugin_prefix;

		/**
		 * Sections array.
		 *
		 * @var   array
		 * @since 1.0.0
		 */
		private array $sections_array = [];

		/**
		 * Fields array.
		 *
		 * @var   array
		 * @since 1.0.0
		 */
		private array $fields_array = [];

		/**
		 * Sidebar card array.
		 *
		 * @var array
		 */
		private array $sidebar_cards = [];

		/**
		 * Constructor.
		 *
		 * @param string $plugin_name    Plugin name.
		 * @param string $plugin_version Plugin version.
		 * @param string $plugin_slug    Plugin slug.
		 * @param string $plugin_prefix  Plugin prefix.
		 *
		 * @since  1.0.0
		 */
		public function __construct( string $plugin_name = 'WPOSA', string $plugin_version = '0.1', string $plugin_slug = 'WPOSA', string $plugin_prefix = 'WPOSA' ) {
			$this->plugin_name    = $plugin_name;
			$this->plugin_version = $plugin_version;
			$this->plugin_slug    = $plugin_slug;
			$this->plugin_prefix  = $plugin_prefix;
		}

		/**
		 * Get plugin name.
		 *
		 * @return string
		 */
		public function get_plugin_name(): string {
			return $this->plugin_name;
		}

		/**
		 * Set plugin name.
		 *
		 * @param string $plugin_name Plugin name.
		 *
		 * @return WPOSA
		 */
		public function set_plugin_name( string $plugin_name ): WPOSA {
			$this->plugin_name = $plugin_name;

			return $this;
		}

		/**
		 * Get plugin version.
		 *
		 * @return string
		 */
		public function get_plugin_version(): string {
			return $this->plugin_version;
		}

		/**
		 * Set plugin version.
		 *
		 * @param string $plugin_version Plugin version.
		 *
		 * @return WPOSA
		 */
		public function set_plugin_version( string $plugin_version ): WPOSA {
			$this->plugin_version = $plugin_version;

			return $this;
		}

		/**
		 * Get plugin slug.
		 *
		 * @return string
		 */
		public function get_plugin_slug(): string {
			return $this->plugin_slug;
		}

		/**
		 * Set plugin slug.
		 *
		 * @param string $plugin_slug Plugin slug.
		 *
		 * @return WPOSA
		 */
		public function set_plugin_slug( string $plugin_slug ): WPOSA {
			$this->plugin_slug = $plugin_slug;

			return $this;
		}

		/**
		 * Get plugin prefix.
		 *
		 * @return string
		 */
		public function get_plugin_prefix(): string {
			return $this->plugin_prefix;
		}

		/**
		 * Set plugin prefix.
		 *
		 * @param string $plugin_prefix Plugin prefix.
		 *
		 * @return WPOSA
		 */
		public function set_plugin_prefix( string $plugin_prefix ): WPOSA {
			$this->plugin_prefix = $plugin_prefix;

			return $this;
		}

		/**
		 * Setup hooks.
		 *
		 * @return void
		 */
		public function setup_hooks(): void {
			// Enqueue the admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

			// Hook it up.
			add_action( 'admin_init', array( $this, 'admin_init' ) );

			// Menu.
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		/**
		 * Admin Scripts.
		 *
		 * @since 1.0.0
		 */
		public function admin_scripts() {
			// jQuery is needed.
			wp_enqueue_script( 'jquery' );

			// Color Picker.
			wp_enqueue_script(
				'iris',
				admin_url( 'js/iris.min.js' ),
				array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
				$this->get_plugin_version(),
				true
			);

			// Media Uploader.
			wp_enqueue_media();
		}

		/**
		 * Set Sections.
		 *
		 * @param array $sections Sections array.
		 *
		 * @return WPOSA
		 */
		public function set_sections( array $sections ): WPOSA {

			// Assign to the sections array.
			$this->sections_array = $sections;

			return $this;
		}

		/**
		 * Add a single section.
		 *
		 * @param array $section Section array.
		 *
		 * @return WPOSA
		 */
		public function add_section( array $section ): WPOSA {

			$section['id'] = $this->get_plugin_prefix() . '_' . $section['id'];

			// Assign the section to sections array.
			$this->sections_array[] = $section;

			return $this;
		}

		/**
		 * Set Fields.
		 *
		 * @param array $fields Fields array.
		 *
		 * @return WPOSA
		 */
		public function set_fields( array $fields ): WPOSA {

			// Assign the fields.
			$this->fields_array = $fields;

			return $this;
		}

		/**
		 * Add a single field.
		 *
		 * @param string $section     Section ID.
		 * @param array  $field_array Field data.
		 *
		 * @return WPOSA
		 */
		public function add_field( string $section, array $field_array ): WPOSA {
			// Set the defaults.
			$defaults = array(
				'id'   => '',
				'name' => '',
				'desc' => '',
				'type' => 'text',
			);

			// Combine the defaults with user's arguments.
			$arg = wp_parse_args( $field_array, $defaults );

			// Each field is an array named against its section.
			$this->fields_array[ $this->get_plugin_prefix() . '_' . $section ][] = $arg;

			return $this;
		}

		/**
		 * Add sidebar card.
		 *
		 * @param array $card Card data.
		 *
		 * @return $this
		 */
		public function add_sidebar_card( array $card ): WPOSA {
			$this->sidebar_cards[] = $card;

			return $this;
		}

		/**
		 * Get sidebar cards.
		 *
		 * @return array
		 */
		public function get_sidebar_cards(): array {
			return $this->sidebar_cards;
		}

		/**
		 * Get sidebar cards total.
		 *
		 * @return int
		 */
		public function get_sidebar_cards_total(): int {
			return count( $this->get_sidebar_cards() );
		}

		/**
		 * Convert array of attributes to html attributes.
		 *
		 * @param array $args Arguments.
		 *
		 * @return string
		 */
		private function convert_array_to_attributes( array $args ): string {
			$result = [];

			if ( count( $args ) ) {
				foreach ( $args as $attr_key => $attr_value ) {
					if ( $attr_value === true || $attr_value === false ) {
						if ( $attr_value === true ) {
							$result[] = esc_attr( $attr_key );
						}
					} else {
						$result[] = sprintf(
							'%s="%s"',
							esc_attr( $attr_key ),
							esc_attr( $attr_value )
						);
					}
				}
			}

			return implode( ' ', $result );
		}

		/**
		 * Initialize API.
		 *
		 * Initializes and registers the settings sections and fields.
		 * Usually this should be called at `admin_init` hook.
		 *
		 * @since  1.0.0
		 */
		public function admin_init(): void {
			/**
			 * Register the sections.
			 *
			 * Sections array is like this:
			 *
			 * $sections_array = array (
			 *   $section_array,
			 *   $section_array,
			 *   $section_array,
			 * );
			 *
			 * Section array is like this:
			 *
			 * $section_array = array (
			 *   'id'    => 'section_id',
			 *   'title' => 'Section Title'
			 * );
			 *
			 * @since 1.0.0
			 */
			foreach ( $this->sections_array as $section ) {
				if ( get_option( $section['id'] ) === false ) {
					// Add a new field as section ID.
					add_option( $section['id'], '', '', false );
				}

				// Deals with sections description.
				if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
					// Build HTML.
					$section['desc'] = '<div class="inside wposa-section-description">' . wp_kses( $section['desc'], self::ALLOWED_HTML ) . '</div>';

					// Create the callback for description.
					$callback = function () use ( $section ) {
						echo wp_kses( str_replace( '"', '\"', $section['desc'] ), self::ALLOWED_HTML );
					};

				} elseif ( isset( $section['callback'] ) ) {
					$callback = $section['callback'];
				} else {
					$callback = null;
				}

				/**
				 * Add a new section to a settings page.
				 *
				 * @param string $id
				 * @param string $title
				 * @param callable $callback
				 * @param string $page | Page is same as section ID.
				 *
				 * @since 1.0.0
				 */
				add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
			} // foreach ended.

			/**
			 * Register settings fields.
			 *
			 * Fields array is like this:
			 *
			 * $fields_array = array (
			 *   $section => $field_array,
			 *   $section => $field_array,
			 *   $section => $field_array,
			 * );
			 *
			 *
			 * Field array is like this:
			 *
			 * $field_array = array (
			 *   'id'   => 'id',
			 *   'name' => 'Name',
			 *   'type' => 'text',
			 * );
			 *
			 * @since 1.0.0
			 */
			foreach ( $this->fields_array as $section => $field_array ) {
				foreach ( $field_array as $field ) {
					// ID.
					$id = isset( $field['id'] ) ? $field['id'] : false;

					// Type.
					$type = isset( $field['type'] ) ? $field['type'] : 'text';

					// Name.
					$name = isset( $field['name'] ) ? $field['name'] : 'No Name Added';

					// Label for.
					$label_for = "{$section}[{$field['id']}]";

					// Description.
					$description = isset( $field['desc'] ) ? $field['desc'] : '';

					// Size.
					$size = isset( $field['size'] ) ? $field['size'] : null;

					// Options.
					$options = isset( $field['options'] ) ? $field['options'] : '';

					// Standard default value.
					$default = isset( $field['default'] ) ? $field['default'] : '';

					// Standard default placeholder.
					$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';

					// Readonly attribute.
					$readonly = $field['readonly'] ?? false;

					// Sanitize Callback.
					$sanitize_callback = isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : '';

					$help_tab = $field['help_tab'] ?? '';
					$class    = $field['class'] ?? "wposa-form-table__row wposa-form-table__row_type_{$type} wposa-form-table__row_{$section}_{$id}";

					$args = array(
						'id'                => $id,
						'type'              => $type,
						'name'              => $name,
						'label_for'         => $label_for,
						'desc'              => $description,
						'section'           => $section,
						'size'              => $size,
						'options'           => $options,
						'std'               => $default,
						'placeholder'       => $placeholder,
						'sanitize_callback' => $sanitize_callback,
						'attributes'        => [
							'readonly' => $readonly,
						],
						'class'             => $class,
					);

					if ( $help_tab ) {
						$name .= $this->show_help_tab_toggle( $help_tab );
					}

					/**
					 * Add a new field to a section of a settings page.
					 *
					 * @param string $id
					 * @param string $title
					 * @param callable $callback
					 * @param string $page
					 * @param string $section = 'default'
					 * @param array $args = array()
					 *
					 * @since 1.0.0
					 */
					// @param string 	$id
					$field_id = $section . '[' . $field['id'] . ']';

					add_settings_field(
						$field_id,
						$name,
						array( $this, 'callback_' . $type ),
						$section,
						$section,
						$args
					);
				} // foreach ended.
			} // foreach ended.

			// Creates our settings in the fields table.
			foreach ( $this->sections_array as $section ) {
				/**
				 * Registers a setting and its sanitization callback.
				 *
				 * @param string $field_group | A settings group name.
				 * @param string $field_name | The name of an option to sanitize and save.
				 * @param callable $sanitize_callback = ''
				 *
				 * @since 1.0.0
				 */
				register_setting( $section['id'], $section['id'], array( $this, 'sanitize_fields' ) );
			} // foreach ended.

		} // admin_init() ended.


		/**
		 * Sanitize callback for Settings API fields.
		 *
		 * @param array $fields Fields.
		 *
		 * @return array
		 */
		public function sanitize_fields( array $fields ): array {
			foreach ( $fields as $field_slug => $field_value ) {
				$sanitize_callback = $this->get_sanitize_callback( $field_slug );

				// If callback is set, call it.
				if ( $sanitize_callback ) {
					$fields[ $field_slug ] = call_user_func( $sanitize_callback, $field_value );
					continue;
				}
			}

			return $fields;
		}


		/**
		 * Get sanitization callback for given option slug
		 *
		 * @param string $slug option slug.
		 *
		 * @return mixed string | bool false
		 * @since  1.0.0
		 */
		public function get_sanitize_callback( $slug = '' ) {
			if ( empty( $slug ) ) {
				return false;
			}

			// Iterate over registered fields and see if we can find proper callback.
			foreach ( $this->fields_array as $section => $field_array ) {
				foreach ( $field_array as $field ) {
					if ( $field['name'] !== $slug ) {
						continue;
					}

					// Return the callback name.
					return isset( $field['sanitize_callback'] ) && is_callable( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : false;
				}
			}

			return false;
		}


		/**
		 * Get field description for display
		 *
		 * @param array $args settings field args.
		 */
		public function get_field_description( $args ) {
			if ( ! empty( $args['desc'] ) ) {
				$desc = sprintf(
					'<p class="description">%s</p>',
					is_callable( $args['desc'] )
						? call_user_func( $args['desc'] )
						: $args['desc']
				);
			} else {
				$desc = '';
			}

			return wp_kses( $desc, self::ALLOWED_HTML );
		}


		/**
		 * Displays a title field for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_title( $args ) {
			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );

			echo esc_html( $value );
		}


		/**
		 * Displays a text field for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_text( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$type  = isset( $args['type'] ) ? $args['type'] : 'text';

			$attributes = $this->convert_array_to_attributes( $args['attributes'] );

			$html = sprintf(
				'<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s" placeholder="%6$s" %7$s/>',
				esc_attr( $type ),
				esc_attr( $size ),
				esc_attr( $args['section'] ),
				esc_attr( $args['id'] ),
				esc_attr( $value ),
				esc_attr( $args['placeholder'] ),
				$attributes
			);

			$html .= $this->get_field_description( $args );

			echo wp_kses( $html, self::ALLOWED_HTML );
		}

		/**
		 * Displays a url field for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_url( $args ) {
			$this->callback_text( $args );
		}

		/**
		 * Displays a number field for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_number( $args ) {
			$this->callback_text( $args );
		}

		/**
		 * Displays a checkbox for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_checkbox( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$html  = '<fieldset>';
			$html .= sprintf( '<label for="wposa-%1$s[%2$s]">', $args['section'], $args['id'] );
			$html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
			$html .= sprintf( '<input type="checkbox" class="checkbox" id="wposa-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked( $value, 'on', false ) );
			$html .= sprintf( '%1$s</label>', $args['desc'] );
			$html .= '</fieldset>';

			echo wp_kses( $html, self::ALLOWED_HTML );
		}

		/**
		 * Displays a iOS switch checkbox for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_switch( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$html  = '<fieldset>';
			$html .= sprintf( '<label for="wposa-%1$s[%2$s]">', $args['section'], $args['id'] );
			$html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
			$html .= sprintf( '<input type="checkbox" class="wposa-field wposa-field--switch" id="wposa-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked( $value, 'on', false ) );
			$html .= sprintf( '%1$s</label>', $args['desc'] );
			$html .= '</fieldset>';

			echo wp_kses( $html, self::ALLOWED_HTML );
		}

		/**
		 * Displays a multicheckbox a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_multicheck( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['std'] );

			$html = '<fieldset>';
			foreach ( $args['options'] as $key => $label ) {
				$checked = isset( $value[ $key ] ) ? $value[ $key ] : '0';
				$html   .= sprintf( '<label for="wposa-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
				$html   .= sprintf( '<input type="checkbox" class="checkbox" id="wposa-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $checked, $key, false ) );
				$html   .= sprintf( '%1$s</label><br>', $label );
			}
			$html .= $this->get_field_description( $args );
			$html .= '</fieldset>';

			echo wp_kses( $html, self::ALLOWED_HTML );
		}

		/**
		 * Displays a multicheckbox a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_radio( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['std'] );

			$html = '<fieldset>';
			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<label for="wposa-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
				$html .= sprintf( '<input type="radio" class="radio" id="wposa-%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ) );
				$html .= sprintf( '%1$s</label><br>', $label );
			}
			$html .= $this->get_field_description( $args );
			$html .= '</fieldset>';

			echo wp_kses( $html, self::ALLOWED_HTML );
		}

		/**
		 * Displays a selectbox for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_select( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'] );
			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
			}
			$html .= '</select>';
			$html .= $this->get_field_description( $args );

			echo wp_kses( $html, self::ALLOWED_HTML );
		}

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_textarea( $args ) {

			$value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html  = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</textarea>', $size, $args['section'], $args['id'], $value );
			$html .= $this->get_field_description( $args );

			echo wp_kses( $html, self::ALLOWED_HTML );
		}

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_html( $args ) {
			echo wp_kses( $this->get_field_description( $args ), self::ALLOWED_HTML );
		}

		/**
		 * Displays a file upload field for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_file( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$id    = $args['section'] . '[' . $args['id'] . ']';
			$label = $args['options']['button_label'] ?? __( 'Choose File' );

			$html  = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
			$html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
			$html .= $this->get_field_description( $args );

			echo wp_kses( $html, self::ALLOWED_HTML );
		}

		/**
		 * Displays an image upload field with a preview
		 *
		 * @param array $args settings field args.
		 */
		public function callback_image( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$id    = $args['section'] . '[' . $args['id'] . ']';
			$label = $args['options']['button_label'] ?? __( 'Choose Image' );

			$html  = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
			$html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
			$html .= $this->get_field_description( $args );
			$html .= '<p class="wpsa-image-preview"><img src=""/></p>';

			echo wp_kses( $html, self::ALLOWED_HTML );
		}

		/**
		 * Displays a password field for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_password( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html  = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
			$html .= $this->get_field_description( $args );

			echo wp_kses( $html, self::ALLOWED_HTML );
		}

		/**
		 * Displays a color picker field for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_color( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'], $args['placeholder'] ) );
			$size  = $args['size'] ?? 'regular';

			$html  = sprintf( '<input type="text" class="%1$s-text color-picker" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" placeholder="%6$s" />', $size, $args['section'], $args['id'], $value, $args['std'], $args['placeholder'] );
			$html .= $this->get_field_description( $args );

			echo wp_kses( $html, self::ALLOWED_HTML );
		}


		/**
		 * Displays a separator field for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_separator( $args ) {
			?>
			<div class="wposa-field wposa-field--separator"></div>
			<?php
		}

		/**
		 * Displays a Button field for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_button( $args ) {
			$value = $args['placeholder'] ?? __( 'Submit' );
			$class = 'button-secondary';
			$id    = $args['id'] ?? time();
			?>
			<input
				type="button"
				id="<?php echo esc_attr( $id ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
				class="button <?php echo esc_attr( $class ); ?>"
			/>
			<?php
		}

		/**
		 * Displays a Button field for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_hidden( $args ) {
			$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
			?>
			<input
				type="hidden"
				name="<?php echo esc_attr( $args['section'] ); ?>[<?php echo esc_attr( $args['id'] ); ?>]"
				value="<?php echo esc_attr( $value ); ?>"
			/>
			<?php
		}

		/**
		 * Get the value of a settings field
		 *
		 * @param string $option settings field name.
		 * @param string $section the section name this field belongs to.
		 * @param mixed  $default default text if it's not found.
		 *
		 * @return mixed
		 */
		public function get_option( string $option, string $section, $default = '' ) {
			$section = str_replace( $this->get_plugin_prefix() . '_', '', $section );
			$options = get_option( $this->get_plugin_prefix() . '_' . $section );

			if ( isset( $options[ $option ] ) ) {
				return apply_filters( 'wposa/get_option', $options[ $option ], $option, $section, $default );
			}

			return apply_filters( 'wposa/get_option', $default, $option, $section, $default );
		}

		/**
		 * Set option.
		 *
		 * @param string $option  Option name.
		 * @param mixed  $value   Option value.
		 * @param string $section Section name.
		 *
		 * @return bool
		 */
		public function set_option( string $option, $value, string $section ): bool {
			$name = $this->get_plugin_prefix() . '_' . $section;

			// Get option.
			$options = get_option( $name );

			if ( ! $options ) {
				return false;
			}

			// Update option.
			$options[ $option ] = $value;

			return update_option( $name, $options );
		}

		/**
		 * Add submenu page to the Settings main menu.
		 *
		 * @author Ahmad Awais
		 * @since  [version]
		 */
		public function admin_menu() {
			add_menu_page(
				$this->get_plugin_name(),
				$this->get_plugin_name(),
				'manage_options',
				$this->get_plugin_slug(),
				array( $this, 'plugin_page' ),
				'dashicons-rest-api'
			);
		}

		/**
		 * Render plugin page.
		 *
		 * @return void
		 */
		public function plugin_page() {
			?>
			<div class="wrap wposa">
				<h1 class="wposa__title wposa__title--h1"><?php echo esc_html( $this->plugin_name ); ?> <span
						style="font-size:50%;">v<?php echo esc_html( $this->plugin_version ); ?></span></h1>
				<?php $this->show_navigation(); ?>
				<div class="wposa__grid">
					<div class="wposa__column">
						<?php $this->show_forms(); ?>
					</div>
					<?php if ( $this->get_sidebar_cards_total() ) : ?>
						<div class="wposa__column">
							<?php foreach ( $this->get_sidebar_cards() as $card ) : ?>
								<div
									class="card wposa-card wposa-card--<?php echo esc_attr( $this->get_plugin_prefix() ); ?>_<?php echo esc_attr( $card['id'] ); ?>">
									<?php if ( ! empty( $card['title'] ) ) : ?>
										<h2 class="title wposa__title wposa__title--h2 wposa-card__title"><?php echo esc_html( $card['title'] ); ?></h2>
									<?php endif; ?>
									<div class="wposa-card__content">
										<?php echo wp_kses( $card['desc'], self::ALLOWED_HTML ); ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		/**
		 * Show navigations as tab
		 *
		 * Shows all the settings section labels as tab.
		 */
		public function show_navigation() {
			$html = sprintf(
				'<nav class="nav-tab-wrapper" aria-label="%s">',
				esc_html__( 'Secondary Navigation', 'wposa' )
			);

			foreach ( $this->sections_array as $tab ) {
				if ( isset( $tab['disabled'] ) && $tab['disabled'] === true ) {
					if ( isset( $tab['badge'] ) ) {
						$html .= sprintf( '<span class="nav-tab wposa-nav-tab wposa-nav-tab--disabled" id="%1$s-tab">%2$s <span class="wposa-badge">%3$s</span></span>', $tab['id'], $tab['title'], $tab['badge'] );
					} else {
						$html .= sprintf( '<span class="nav-tab wposa-nav-tab wposa-nav-tab--disabled" id="%1$s-tab">%2$s</span>', $tab['id'], $tab['title'] );
					}
				} else {
					$html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
				}
			}

			$html .= '</nav>';

			echo wp_kses( $html, self::ALLOWED_HTML );
		}

		/**
		 * Show the section settings forms
		 *
		 * This function displays every section in a different form.
		 */
		public function show_forms() {
			$default = array(
				'label_submit' => null,
				'submit_type'  => 'primary',
				'wrap'         => true,
				'attributes'   => null,
			);
			?>
			<div class="metabox-holder">
				<?php foreach ( $this->sections_array as $form ) : ?>
					<?php
					$form = wp_parse_args( $form, $default );
					?>
					<!-- style="display: none;" -->
					<div id="<?php echo esc_attr( $form['id'] ); ?>" class="group">
						<form class="wposa__form" method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
							<?php
							do_action( 'wsa_form_top_' . $form['id'], $form );
							settings_fields( $form['id'] );
							do_settings_sections( $form['id'] );
							do_action( 'wsa_form_bottom_' . $form['id'], $form );
							?>
							<div style="padding-left: 10px">
								<?php submit_button( $form['label_submit'], $form['submit_type'], 'submit_' . $form['id'], $form['wrap'], $form['attributes'] ); ?>
							</div>
						</form>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
			$this->script();
		}

		/**
		 * Show help tab toggle.
		 *
		 * @param string $tab_id Tab identified.
		 * @param string $tab_icon Tab icon.
		 */
		private function show_help_tab_toggle( string $tab_id, string $tab_icon = '?' ) {
			ob_start();
			?>
			<a
				title="<?php echo esc_attr__( 'Click to show Help tab', 'mihdan-index-now' ); ?>"
				class="wpsa-help-tab-toggle"
				data-tab="<?php echo esc_attr( $tab_id ); ?>">
				<?php echo esc_html( $tab_icon ); ?>
			</a>
			<?php
			return ob_get_clean();
		}

		/**
		 * Tabbable JavaScript codes & Initiate Color Picker,
		 * this code uses localstorage for displaying active tabs.
		 */
		public function script() {
			?>
			<script>
				jQuery(document).ready(function ($) {

					const $show_settings_toggler = $('.show-settings');
					const $help = $('.wpsa-help-tab-toggle');

					$help.on(
						'click',
						function () {
							const $this = $(this);
							const tab = '#tab-link-<?php echo esc_js( $this->get_plugin_prefix() ); ?>_' + $this.data('tab');

							if ($show_settings_toggler.attr('aria-expanded') === 'false') {
								$show_settings_toggler.trigger('click');
							}

							$(tab).find('a').trigger('click');
						}
					);

					//Initiate Color Picker.
					$('.color-picker').iris();

					// Switches option sections
					$('.group').hide();
					var activetab = '';
					if ('undefined' != typeof localStorage) {
						activetab = localStorage.getItem('activetab');
					}
					if ('' !== activetab && $(activetab).length) {
						$(activetab).fadeIn();
					} else {
						$('.group:first').fadeIn();
					}
					$('.group .collapsed').each(function () {
						$(this)
							.find('input:checked')
							.parent()
							.parent()
							.parent()
							.nextAll()
							.each(function () {
								if ($(this).hasClass('last')) {
									$(this).removeClass('hidden');
									return false;
								}
								$(this)
									.filter('.hidden')
									.removeClass('hidden');
							});
					});

					if ('' !== activetab && $(activetab + '-tab').length) {
						$(activetab + '-tab').addClass('nav-tab-active');
					} else {
						$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
					}
					$('.nav-tab-wrapper a').click(function (evt) {
						$('.nav-tab-wrapper a').removeClass('nav-tab-active');
						$(this)
							.addClass('nav-tab-active')
							.blur();
						var clicked_group = $(this).attr('href');
						if ('undefined' != typeof localStorage) {
							localStorage.setItem('activetab', $(this).attr('href'));
						}
						$('.group').hide();
						$(clicked_group).fadeIn();
						evt.preventDefault();
					});

					$('.wpsa-browse').on('click', function (event) {
						event.preventDefault();

						var self = $(this);

						// Create the media frame.
						var file_frame = (wp.media.frames.file_frame = wp.media({
							title: self.data('uploader_title'),
							button: {
								text: self.data('uploader_button_text')
							},
							multiple: false
						}));

						file_frame.on('select', function () {
							attachment = file_frame
								.state()
								.get('selection')
								.first()
								.toJSON();

							self
								.prev('.wpsa-url')
								.val(attachment.url)
								.change();
						});

						// Finally, open the modal
						file_frame.open();
					});

					$('input.wpsa-url')
						.on('change keyup paste input', function () {
							var self = $(this);
							self
								.next()
								.parent()
								.children('.wpsa-image-preview')
								.children('img')
								.attr('src', self.val());
						})
						.change();

				});

			</script>

			<style>
				#wpbody-content .wposa .metabox-holder {
					padding-top: 5px;
				}

				.wpsa-image-preview img {
					height: auto;
					max-width: 70px;
				}

				.wposa-field--separator {
					background: #ccc;
					border: 0;
					color: #ccc;
					height: 1px;
					position: absolute;
					left: 0;
					width: 99%;
				}

				.group .form-table input.color-picker {
					max-width: 100px;
				}

				.wpsa-help-tab-toggle {
					display: inline-block;
					width: 14px;
					height: 14px;
					line-height: 14px;
					text-align: center;
					border-radius: 50%;
					border: 2px solid #2271b1;
					cursor: help;
					font-size: 12px;
					vertical-align: text-bottom;
					user-select: none;
				}

				.wposa__grid {
					display: grid;
					grid-gap: 20px;
					grid-template-columns: auto 300px;
				}

				input.wposa-field--switch {
					position: relative;
					-webkit-appearance: none;
					appearance: none;
					outline: none;
					width: 40px;
					height: 20px;
					background-color: #ffffff;
					border: 1px solid #D9DADC;
					border-radius: 50px;
					box-shadow: inset -20px 0 0 0 #ffffff;
				}

				input.wposa-field--switch:before {
					display: none !important;
				}

				input.wposa-field--switch:after {
					content: "";
					position: absolute;
					top: 0;
					left: 1px;
					width: 18px;
					height: 18px;
					background-color: transparent;
					border-radius: 50%;
					box-shadow: 2px 0 6px rgba(0, 0, 0, 0.2);
					transition-property: left;
					transition-duration: 3s;
				}

				input.wposa-field--switch:checked {
					border-color: #135e96;
					box-shadow: inset 20px 0 0 0 #135e96;
				}

				input.wposa-field--switch:checked:after {
					left: auto;
					right: 1px;
					box-shadow: -2px 0 3px rgba(0, 0, 0, 0.05);
				}

				.wposa-nav-tab {
				}

				.wposa-nav-tab--disabled {
					cursor: not-allowed;
				}

				.wposa-badge {
					font-size: 0.8em;
					background-color: #d63638;
					color: #fff;
					border-radius: 2px;
					padding: 0 5px;
					display: inline-block;
					font-weight: normal;
				}

				.wposa-section-description {
					max-width: 600px;
				}

				.wposa-form-table__row_type_hidden {
					display: none;
				}

				.wposa-form-table__row_type_number .regular-text {
					width: 50px;
				}

				.wposa__form {
					max-width: 600px;
				}

				.wpsa-card img {
					display: block;
					border: 0;
				}

				@media (max-width: 782px) {
					.wposa__grid {
						grid-template-columns: 1fr;
					}
				}
			</style>
			<?php
		}
	}
endif;
