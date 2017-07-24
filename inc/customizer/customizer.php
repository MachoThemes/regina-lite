<?php


function regina_lite_customize_register( $wp_customize ) {

	$prefix = 'regina_lite';

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';


	/**********************************************/
	/*************** INIT ************************/
	/**********************************************/

	# Include Custom Controls
	require get_template_directory() . '/inc/customizer/custom-controls/pro-controls-selector.php';
	require get_template_directory() . '/inc/customizer/custom-controls/radio-img-selector.php';
	require get_template_directory() . '/inc/customizer/custom-controls/slider-selector.php';
	require get_template_directory() . '/inc/customizer/custom-controls/class-regina-custom-panel.php';

	#
	# Main Upsell features
	#

	$wp_customize->add_section( $prefix . '_color_controls', array(
		'title'    => __( 'Color Controls', 'regina-lite' ),
		'priority' => 0,
		'panel' => 'regina_lite_panel_general'
	) );

	$wp_customize->add_setting( $prefix . '_color_controls', array(
		'sanitize_callback' => $prefix . '_sanitize_pro_version',
	) );

	$wp_customize->add_control( new Epsilon_Control_Upsell( $wp_customize, $prefix . '_color_controls', array(
        'section'      => $prefix . '_color_controls',
        'priority'     => 0,
        'options'      => array(
            esc_html__( 'More Color Options', 'regina-lite' ),
        ),
        'requirements' => array(
            esc_html__( 'The PRO version of Regina allows for a greater degree of customisability. Get multiple professionally designed color schemes with the purchase of the PRO version. ', 'regina-lite' ),
        ),
        'button_url'   => esc_url_raw( get_admin_url() . 'themes.php?page=regina-lite-welcome&tab=features' ),
        'button_text'  => esc_html__( 'See PRO vs Lite', 'regina-lite' ),
        'second_button_url'  => esc_url_raw( 'https://www.machothemes.com/theme/regina-pro/?utm_source=worg&utm_medium=customizer&utm_campaign=upsell' ),
        'second_button_text' => esc_html__( 'Get PRO now!', 'regina-lite' ),
        'separator' => '- or -'
    ) ) );



	$wp_customize->add_section( $prefix . '_dropdown_menus', array(
		'title'    => __( 'Dropdown Menus', 'regina-lite' ),
		'priority' => 202,
		'panel' => 'nav_menus'
	) );

	$wp_customize->add_setting( $prefix . '_dropdown_menus', array(
		'sanitize_callback' => $prefix . '_sanitize_pro_version',
	) );

	$wp_customize->add_control( new Regina_Lite_Upsell_Render_Panel(
			$wp_customize,
			$prefix . '_dropdown_menus',
			array(
				'section' => $prefix . '_dropdown_menus',
				'choices' => array(
					'title' => __( 'Regina PRO supports 3rd level drop-down menus. It also comes bundled with 3 different header layouts.', 'regina-lite'),
					'show_demo_button' => true,
					'show_pro_button' => true
				),
			)
		)
	);

	#
	# END Up Sell Features
	#

	$wp_customize->add_panel( new Regina_Custom_Panel( $wp_customize, 'regina_lite_frontpage_sections', array(
		'title'    => esc_html__( 'Front Page Sections', 'regina-lite' ),
		'priority' => 30,
	) ) );

	// Change panel for Header Image
	$site_title2        = $wp_customize->get_section( 'header_image' );
	$site_title2->panel = 'regina_lite_frontpage_sections';
	$site_title2->priority = 1;

	// upsell - google maps
	$wp_customize->add_section( $prefix . '_maps_section', array(
		'title'    => __( 'Google Maps', 'regina-lite' ),
		'priority' => 60,
		'panel' => 'regina_lite_frontpage_sections'
	) );

	$wp_customize->add_setting( $prefix . '_maps_section', array(
		'sanitize_callback' => $prefix . '_sanitize_pro_version',
	) );
    $wp_customize->add_control( new Epsilon_Control_Upsell( $wp_customize, $prefix . '_maps_section', array(
        'section'      => $prefix . '_maps_section',
        'priority'     => 0,
        'options'      => array(
            esc_html__( 'Google Map Sections', 'regina-lite' ),
        ),
        'requirements' => array(
            esc_html__( 'Unlimited Google Maps are available in the PRO version of Regina.', 'regina-lite' ),
        ),
        'button_url'   => esc_url_raw( get_admin_url() . 'themes.php?page=regina-lite-welcome&tab=features' ),
        'button_text'  => esc_html__( 'See PRO vs Lite', 'regina-lite' ),
        'second_button_url'  => esc_url_raw( 'https://www.machothemes.com/theme/regina-pro/?utm_source=worg&utm_medium=customizer&utm_campaign=upsell' ),
        'second_button_text' => esc_html__( 'Get PRO now!', 'regina-lite' ),
        'separator' => '- or -'
    ) ) );



	/* General Site Panel */
	require_once get_template_directory() . '/inc/customizer/panels/site.php';

	/* Features Panel */
	require_once get_template_directory() . '/inc/customizer/panels/features.php';

	/* Blog Panel */
	require_once get_template_directory() . '/inc/customizer/panels/blog.php';

	/* Advanced Panel */
	require_once get_template_directory() . '/inc/customizer/panels/advanced.php';

	/* Our Team Panel */
	require_once get_template_directory() . '/inc/customizer/panels/our-team.php';

	/* Testimonials Panel */
	require_once get_template_directory() . '/inc/customizer/panels/testimonials.php';

	/* Speak Panel */
	require_once get_template_directory() . '/inc/customizer/panels/speak.php';

	/* Latest News */
	require_once get_template_directory() . '/inc/customizer/panels/news.php';

}

add_action( 'customize_register', 'regina_lite_customize_register' );

if ( ! function_exists( 'regina_lite_sanitize_number' ) ) {
	/**
	 * Simple function used to sanitize numbers
	 *
	 * @param $input
	 *
	 * @return mixed
	 */
	function regina_lite_sanitize_number( $input ) {
		return force_balance_tags( $input );
	}
}

if ( ! function_exists( 'regina_lite_sanitize_file_url' ) ) {
	/**
	 * Function to sanitize file URLS
	 *
	 * Used mostly for sanitizing image field types
	 *
	 * @param $url
	 *
	 * @return string
	 */
	function regina_lite_sanitize_file_url( $url ) {

		$output = '';

		$filetype = wp_check_filetype( $url );
		if ( $filetype["ext"] ) {
			$output = esc_url( $url );
		}

		return $output;
	}
}


if ( ! function_exists( 'regina_lite_sanitize_radio_buttons' ) ) {
	/**
	 * Simple function to validate choices from radio buttons
	 *
	 * @param $input
	 *
	 * @return string
	 */
	function regina_lite_sanitize_radio_buttons( $input, $setting ) {

		global $wp_customize;

		$control = $wp_customize->get_control( $setting->id );

		if ( array_key_exists( $input, $control->choices ) ) {
			return $input;
		} else {
			return $setting->default;
		}
	}
}

if ( ! function_exists( 'regina_lite_sanitize_checkbox' ) ) {
	/**
	 * Function to sanitize checkboxes
	 *
	 * @param $value
	 *
	 * @return int
	 */
	function regina_lite_sanitize_checkbox( $value ) {
		if ( $value == 1 ) {
			return 1;
		} else {
			return 0;
		}
	}
}

if ( ! function_exists( 'regina_lite_sanitize_pro_version' ) ) {

	function regina_lite_sanitize_pro_version( $input ) {
		return force_balance_tags( $input );
	}
}

if ( ! function_exists( 'regina_lite_customizer_js_load' ) ) {
	/**
	 * Function to load JS into the customizer
	 */
	function regina_lite_customizer_js_load() {

		// Customizer JS
		wp_enqueue_script( 'rl-customizer-script', get_template_directory_uri() . '/inc/customizer/assets/js/customizer_panel.js', array(
			'jquery',
			'customize-controls',
		), '1.0', true );
		
	}

	add_action( 'customize_controls_enqueue_scripts', 'regina_lite_customizer_js_load' );
}

if ( ! function_exists( 'regina_lite_customizer_preview_js' ) ) {
	/**
	 * Function to load JS into the customizer preview
	 */
	function regina_lite_customizer_preview_js() {
		// Customizer preview JS
		wp_enqueue_style( 'rl-previewer-css', get_template_directory_uri() . '/inc/customizer/assets/css/preview.css' );
		wp_enqueue_script( 'rl-customizer-script', get_template_directory_uri() . '/inc/customizer/assets/js/customizer.js', array( 'customize-preview' ), '1.0', true );
	}

	add_action( 'customize_preview_init', 'regina_lite_customizer_preview_js' );
}


if ( ! function_exists( 'regina_lite_customizer_css_load' ) ) {
	/**
	 * Function to load CSS into the customizer
	 */
	function regina_lite_customizer_css_load() {
		wp_enqueue_style( 'mt-customizer-css', get_template_directory_uri() . '/inc/customizer/assets/css/pro-version.css' );

	}

	// add_action( 'customize_controls_print_styles', 'regina_lite_customizer_css_load' );
}


// Partials callback functions
function pixova_render_appointment_link() {
	$top_box_bookappointmenturl = $top_box_bookappointmenturl = get_theme_mod( 'regina_lite_top_box_bookappointmenturl' );
	$book_appointment_button_label = get_theme_mod( 'regina_lite_book_appointment_button_label' );

	return '<a href="' . esc_url( $top_box_bookappointmenturl ) . '" class="button white outline" title="' . esc_attr( $book_appointment_button_label ) . '">' . esc_attr( $book_appointment_button_label ) . '<span class="nc-icon-glyph arrows-1_bold-right"></span></a>';
}

function pixova_render_feature1_link() {
	$feature_url   = get_theme_mod( 'regina_lite_features_feature1_buttonurl' );
	return '<a href="' . esc_url( $features_feature1_buttonurl ) . '" class="link small">' . __( 'Read more', 'regina-lite' ) . '<span class="nc-icon-glyph arrows-1_bold-right"></span></a>';
}

function pixova_render_feature2_link() {
	$feature_url   = get_theme_mod( 'regina_lite_features_feature2_buttonurl' );
	return '<a href="' . esc_url( $features_feature1_buttonurl ) . '" class="link small">' . __( 'Read more', 'regina-lite' ) . '<span class="nc-icon-glyph arrows-1_bold-right"></span></a>';
}

function pixova_render_feature3_link() {
	$feature_url   = get_theme_mod( 'regina_lite_features_feature3_buttonurl' );
	return '<a href="' . esc_url( $features_feature1_buttonurl ) . '" class="link small">' . __( 'Read more', 'regina-lite' ) . '<span class="nc-icon-glyph arrows-1_bold-right"></span></a>';
}

function pixova_render_feature4_link() {
	$feature_url   = get_theme_mod( 'regina_lite_features_feature4_buttonurl' );
	return '<a href="' . esc_url( $features_feature1_buttonurl ) . '" class="link small">' . __( 'Read more', 'regina-lite' ) . '<span class="nc-icon-glyph arrows-1_bold-right"></span></a>';
}

function pixova_render_testimonial_image1() {
	$testimonials_general_image = get_theme_mod( 'regina_lite_testimonials_general_image1' );
	return '<img src="' . esc_url( $testimonials_general_image ) . '" data-original="' . esc_url( $testimonials_general_image ) . '" class="lazy">';
}

function pixova_render_testimonial_image2() {
	$testimonials_general_image = get_theme_mod( 'regina_lite_testimonials_general_image2' );
	return '<img src="' . esc_url( $testimonials_general_image ) . '" data-original="' . esc_url( $testimonials_general_image ) . '" class="lazy">';
}

function pixova_render_testimonial_image3() {
	$testimonials_general_image = get_theme_mod( 'regina_lite_testimonials_general_image3' );
	return '<img src="' . esc_url( $testimonials_general_image ) . '" data-original="' . esc_url( $testimonials_general_image ) . '" class="lazy">';
}

function pixova_render_testimonial_image4() {
	$testimonials_general_image = get_theme_mod( 'regina_lite_testimonials_general_image4' );
	return '<img src="' . esc_url( $testimonials_general_image ) . '" data-original="' . esc_url( $testimonials_general_image ) . '" class="lazy">';
}

function pixova_render_spd_link() {
	$speak_general_buttonurl       = get_theme_mod( 'regina_lite_speak_general_buttonurl' );
	$book_appointment_button_label = get_theme_mod( 'regina_lite_book_appointment_button_label', __( 'Book Appointment', 'regina-lite' ) );
	return '<a href="' . esc_url( $speak_general_buttonurl ) . '" class="button" title="' . esc_attr( $book_appointment_button_label ) . '">' . esc_html( $book_appointment_button_label ) . '<span class="nc-icon-glyph arrows-1_bold-right"></span></a>';
}

function pixova_render_facebook_link(){
	$facebook_url = get_theme_mod( 'regina_lite_contact_bar_facebook_url', '#' );
	return '<a href="' . esc_url( $facebook_url ) . '" title="' . __( 'Facebook', 'regina-lite' ) . '"><span class="nc-icon-glyph socials-1_logo-facebook"></span></a>';
}
function pixova_render_twitter_link(){
	$twitter_url = get_theme_mod( 'regina_lite_contact_bar_twitter_url', '#' );
	return '<a href="' . esc_url( $twitter_url ) . '" title="' . __( 'Twitter', 'regina-lite' ) . '"><span class="nc-icon-glyph socials-1_logo-twitter"></span></a>';
}
function pixova_render_youtube_link(){
	$youtube_url = get_theme_mod( 'regina_lite_contact_bar_youtube_url', '#' );
	return '<a href="' . esc_url( $youtube_url ) . '" title="' . __( 'YouTube', 'regina-lite' ) . '"><span class="nc-icon-glyph socials-1_logo-youtube"></span></a>';
}
function pixova_render_linkedin_link(){
	$linkedin_url = get_theme_mod( 'regina_lite_contact_bar_linkedin_url', '#' );
	return '<a href="' . esc_url( $linkedin_url ) . '" title="' . __( 'LinkedIn', 'regina-lite' ) . '"><span class="nc-icon-glyph socials-1_logo-linkedin"></span></a>';
}
function pixova_render_instagram_link(){
	$instagram_url = get_theme_mod( 'regina_lite_contact_bar_instagram_url', '#' );
	return '<a href="' . esc_url( $instagram_url ) . '" title="' . __( 'Instagram', 'regina-lite' ) . '"><span class="nc-icon-glyph socials-1_logo-instagram"></span></a>';
}
function pixova_render_email_link(){
	$email_addr = get_theme_mod( 'regina_lite_email' );
	return '<a href="mailto: ' . sanitize_email( $email_addr ) . '">' . sanitize_email( $email_addr ) . '</a>';
}