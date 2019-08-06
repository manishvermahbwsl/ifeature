<?php
/**
 * Theme Functions
 *
 * Please do not edit this file. This file is part of the Cyber Chimps Framework and all modifications
 * should be made in a child theme.
 *
 * @category CyberChimps Framework
 * @package  Framework
 * @since    1.0
 * @author   CyberChimps
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v3.0 (or later)
 * @link     https://www.cyberchimps.com/
 */

// Load text domain.
function cyberchimps_text_domain() {
	load_theme_textdomain( 'ifeature', get_template_directory() . '/inc/languages' );
}
add_action( 'after_setup_theme', 'cyberchimps_text_domain' );

if ( ! defined( 'ELEMENTOR_PARTNER_ID' ) ) {
	define( 'ELEMENTOR_PARTNER_ID', 2126 );
}

// Theme check function to determine whether the them is free or pro.
if( !function_exists( 'cyberchimps_theme_check' ) ) {
	function cyberchimps_theme_check() {
		$level = 'free';

		return $level;
	}
}

//Theme Name
function ifeature_options_theme_name() {
	$text = 'iFeature';

	return $text;
}
add_filter( 'cyberchimps_current_theme_name', 'ifeature_options_theme_name', 1 );

// Load Core
require_once( get_template_directory() . '/cyberchimps/init.php' );
require_once( get_template_directory() . '/inc/testimonial_template.php' );

// Set the content width based on the theme's design and stylesheet.
if( !isset( $content_width ) ) {
	$content_width = 640;
} /* pixels */

function ifeature_add_site_info() {
	?>
	<p>&copy; Company Name</p>
<?php
}

add_action( 'cyberchimps_site_info', 'ifeature_add_site_info' );

function ifeature_enqueue()
{
	$directory_uri  = get_template_directory_uri();
	wp_enqueue_script( 'jquery-flexslider', $directory_uri . '/inc/js/jquery.flexslider.js', 'jquery', '', true );
}
add_action( 'wp_enqueue_scripts', 'ifeature_enqueue' );

function ifeature_set_defaults()
{

	remove_filter( 'dynamic_sidebar_params', 'cyberchimps_footer_widgets' );
	add_filter( 'dynamic_sidebar_params', 'ifeature_footer_widget_param' );
	remove_action('testimonial', array( CyberChimpsTestimonial::instance(), 'render_display' ));
	add_action('testimonial', 'ifeature_testimonial_render_display');
}
add_action( 'init', 'ifeature_set_defaults' );

if( !function_exists( 'cyberchimps_comment' ) ) :
// Template for comments and pingbacks.
// Used as a callback by wp_list_comments() for displaying the comments.
	function cyberchimps_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
				?>
				<li class="post pingback">
				<p><?php _e( 'Pingback:', 'ifeature' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'ifeature' ), ' ' ); ?></p>
				<?php
				break;
			default :
				?>
					<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
					<article id="comment-<?php comment_ID(); ?>" class="comment hreview">
						<footer>
							<div class="comment-author reviewer vcard">
								<?php echo get_avatar( $comment, 40 ); ?>
								<?php printf( '%1$s <span class="says">%2$s</span>', sprintf( '<cite class="fn">%1$s</cite>',
								                                                              get_comment_author_link() ),
								              __( 'says', 'ifeature' ) ); ?>
							</div>
							<!-- .comment-author .vcard -->
							<?php if( $comment->comment_approved == '0' ) : ?>
								<em><?php _e( 'Your comment is awaiting moderation.', 'ifeature' ); ?></em>
								<br/>
							<?php endif; ?>

							<div class="comment-meta commentmetadata">
								<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" class="dtreviewed">
									<time pubdate datetime="<?php comment_time( 'c' ); ?>">
										<?php
										/* translators: 1: date, 2: time */
										printf( __( '%1$s at %2$s', 'ifeature' ), get_comment_date(), get_comment_time() ); ?>
									</time>
								</a>
								<?php edit_comment_link( __( '(Edit)', 'ifeature' ), ' ' );
								?>
							</div>
							<!-- .comment-meta .commentmetadata -->
						</footer>

						<div class="comment-content"><?php comment_text(); ?></div>

						<div class="reply">
							<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						</div>
						<!-- .reply -->
					</article><!-- #comment-## -->

				<?php
				break;
		endswitch;
	}
endif; // ends check for cyberchimps_comment()

// set up next and previous post links for lite themes only
function cyberchimps_next_previous_posts() {
	if( get_next_posts_link() || get_previous_posts_link() ): ?>
		<div class="more-content">
			<div class="row-fluid">
				<div class="span6 previous-post">
					<?php previous_posts_link(); ?>
				</div>
				<div class="span6 next-post">
					<?php next_posts_link(); ?>
				</div>
			</div>
		</div>
	<?php
	endif;
}

add_action( 'cyberchimps_after_content', 'cyberchimps_next_previous_posts' );

//Doc's URL
function ifeature_options_documentation_url() {
	$url = 'http://cyberchimps.com/guides/c-free/';

	return $url;
}

// Support Forum URL
function ifeature_options_support_forum() {
	$url = 'https://cyberchimps.com/help/forum/sub-forum/themes-support/free-themes/';

	return $url;
}

add_filter( 'cyberchimps_documentation', 'ifeature_options_documentation_url' );
add_filter( 'cyberchimps_support_forum', 'ifeature_options_support_forum' );

//upgrade bar
function cyberchimps_upgrade_bar_pro_title() {
	$title = 'iFeature Pro 5';

	return $title;
}

function ifeature_upgrade_link() {
	$link = 'http://cyberchimps.com/store/ifeaturepro5/';

	return $link;
}

add_filter( 'cyberchimps_upgrade_pro_title', 'cyberchimps_upgrade_bar_pro_title' );
add_filter( 'cyberchimps_upgrade_link', 'ifeature_upgrade_link' );
function cyberchimps_demodata()
{
	$link = 'https://cyberchimps.com/checkout/?add-to-cart=277283';
	return $link.'';
}
add_filter( 'cyberchimps_demodata', 'cyberchimps_demodata' );

function cyberchimps_gopro()
{
	$link = 'https://cyberchimps.com/store/ifeaturepro#whygopro';
	return $link;
}
add_filter( 'cyberchimps_gopro', 'cyberchimps_gopro' );

function cyberchimps_rating_link()
{
	$link = 'https://wordpress.org/support/theme/ifeature/reviews/#new-post/';
	return $link.'';
}
add_filter( 'cyberchimps_rating_link', 'cyberchimps_rating_link' );

// Help Section
function ifeature_options_help_header() {
	$text = 'iFeature';

	return $text;
}

function ifeature_options_help_sub_header() {
	$text = __( 'iFeature Responsive Drag and Drop WordPress Theme', 'ifeature' );

	return $text;
}

add_filter( 'cyberchimps_help_heading', 'ifeature_options_help_header' );
add_filter( 'cyberchimps_help_sub_heading', 'ifeature_options_help_sub_header' );

// Branding images and defaults

// Banner default
function ifeature_banner_default() {
	$url = '/images/branding/banner.jpg';

	return $url;
}

add_filter( 'cyberchimps_banner_img', 'ifeature_banner_default' );

//slider defaults
function ifeature_slider_image_1() {
	$image = '/images/branding/ifp5slider.jpg';

	return $image;
}

//add same image to all 3 slider image filters
add_filter( 'cyberchimps_slide_pro_img1', 'ifeature_slider_image_1' );
add_filter( 'cyberchimps_slide_pro_img2', 'ifeature_slider_image_1' );
add_filter( 'cyberchimps_slide_pro_img3', 'ifeature_slider_image_1' );

// Default for twitter bar handle
function cyberchimps_twitter_handle_filter() {
	return 'WordPress';
}

add_filter( 'cyberchimps_twitter_handle_filter', 'cyberchimps_twitter_handle_filter' );

// default header option
function ifeature_header_drag_and_drop_default() {
	$option = array(
		'cyberchimps_logo' => __( 'Logo', 'ifeature' )
	);

	return $option;
}

add_filter( 'header_drag_and_drop_default', 'ifeature_header_drag_and_drop_default' );

// set searchbar by default
function ifeature_searchbar_default() {
	$default = 'checked';

	return $default;
}

add_filter( 'searchbar_default', 'ifeature_searchbar_default' );

//theme specific skin options in array. Must always include option default
function ifeature_skin_color_options( $options ) {
	// Get path of image
	$imagepath = get_template_directory_uri() . '/inc/css/skins/images/';

	$options = array(
		'default' => $imagepath . 'default.png',
		'green'   => $imagepath . 'green.png',
		'legacy'   => $imagepath . 'legacy.png'
	);

	return $options;
}

add_filter( 'cyberchimps_skin_color', 'ifeature_skin_color_options', 1 );

// theme specific typography options
function ifeature_typography_sizes( $sizes ) {
	$sizes = array( '8', '9', '10', '12', '14', '16', '20' );

	return $sizes;
}

function ifeature_typography_faces( $faces ) {
	$faces = array(
		'Arial, Helvetica, sans-serif'                     => 'Arial',
		'Arial Black, Gadget, sans-serif'                  => 'Arial Black',
		'Comic Sans MS, cursive'                           => 'Comic Sans MS',
		'Courier New, monospace'                           => 'Courier New',
		'Georgia, serif'                                   => 'Georgia',
		'Impact, Charcoal, sans-serif'                     => 'Impact',
		'Lucida Console, Monaco, monospace'                => 'Lucida Console',
		'Lucida Sans Unicode, Lucida Grande, sans-serif'   => 'Lucida Sans Unicode',
		'Palatino Linotype, Book Antiqua, Palatino, serif' => 'Palatino Linotype',
		'Tahoma, Geneva, sans-serif'                       => 'Tahoma',
		'Times New Roman, Times, serif'                    => 'Times New Roman',
		'Trebuchet MS, sans-serif'                         => 'Trebuchet MS',
		'Verdana, Geneva, sans-serif'                      => 'Verdana',
		'Symbol'                                           => 'Symbol',
		'Webdings'                                         => 'Webdings',
		'Wingdings, Zapf Dingbats'                         => 'Wingdings',
		'MS Sans Serif, Geneva, sans-serif'                => 'MS Sans Serif',
		'MS Serif, New York, serif'                        => 'MS Serif',
	);

	return $faces;
}

function ifeature_typography_styles( $styles ) {
	$styles = array( 'normal' => 'Normal', 'bold' => 'Bold' );

	return $styles;
}

add_filter( 'cyberchimps_typography_sizes', 'ifeature_typography_sizes' );
add_filter( 'cyberchimps_typography_faces', 'ifeature_typography_faces' );
add_filter( 'cyberchimps_typography_styles', 'ifeature_typography_styles' );

function ifeature_post_tags( $tags ) {
	$tag = trim( $tags, 'Tags:' );
	$tag = explode( ',', $tag );
	$tag = implode( ' ', $tag );

	return $tag;
}

add_filter( 'cyberchimps_post_tags', 'ifeature_post_tags' );

/* remove meta seperator */
function ifeature_seperator( $sep ) {
	$sep = ' ';

	return $sep;
}

add_filter( 'cyberchimps_entry_meta_sep', 'ifeature_seperator' );
//add imenu section
function ifeature_sections_filter( $sections ) {
	$new_sections = array( array( 'id'      => 'cyberchimps_imenu_section',
	                              'label'   => __( 'iMenu Options', 'ifeature' ),
	                              'heading' => 'cyberchimps_header_heading'
	)
	);
	$sections     = array_merge( $sections, $new_sections );

	return $sections;
}

add_filter( 'cyberchimps_sections_filter', 'ifeature_sections_filter' );

// add top bar option and add contact information
function ifeature_fields_filter( $fields ) {
	$new_fields = array( array( 'name'    => __( 'Menu Home Icon', 'ifeature' ),
	                            'id'      => 'menu_home_button',
	                            'type'    => 'toggle',
	                            'std'     => 1,
	                            'section' => 'cyberchimps_imenu_section',
	                            'heading' => 'cyberchimps_header_heading'
	),
	);
	$fields     = array_merge( $fields, $new_fields );

	foreach( $fields as $key => $value ):
		// move the search bar to imenu section
		if( $value['id'] == 'searchbar' ):
			$fields[$key]['section'] = 'cyberchimps_imenu_section';
		endif;
	endforeach;

	return $fields;
}

add_filter( 'cyberchimps_field_filter', 'ifeature_fields_filter', 2 );

//add home button to menu
function ifeature_add_home_menu( $menu, $args ) {

	//check if the toggle is set. And if it is, then add the home button to the start of the primary menu.
	$is_home = cyberchimps_get_option( 'menu_home_button', 1 );
	if( $is_home == 1 && $args->theme_location == 'primary' ) {
		$home = '<li id="menu-item-ifeature-home"><a href="' . home_url() . '"><img src="' . get_template_directory_uri() . '/images/home.png" alt="Home" /></a></li>';
		$menu = $home . $menu;
	}

	return $menu;
}

add_filter( 'wp_nav_menu_items', 'ifeature_add_home_menu', 10, 2 );

/* fix full width container that disappears on horizontal scroll */
function cyberchimps_full_width_fix() {
	$responsive_design = cyberchimps_get_option( 'responsive_design' );
	$min_width         = cyberchimps_get_option( 'max_width' );
	if( !$responsive_design ) {
		$style = '<style rel="stylesheet" type="text/css" media="all">';
		$style .= '.container-full, #footer-widgets-wrapper, #footer-main-wrapper { min-width: ' . $min_width . 'px;}';
		$style .= '</style>';

		echo $style;
	}
}

add_action( 'wp_head', 'cyberchimps_full_width_fix' );

 /* Add iMenu Options in customizer and remove searchbar from header option */

    add_action( 'customize_register', 'ifeature_customize_register', 50 );

    function ifeature_customize_register( $wp_customize ) {
        $wp_customize->remove_setting( 'cyberchimps_options[searchbar]' );
        $wp_customize->remove_control( 'searchbar' );
        $wp_customize->add_section( 'cyberchimps_imenu', array(
            'priority' => 20,
            'capability' => 'edit_theme_options',
            'theme_supports' => '',
            'title' => __( 'iMenu Options', 'cyberchimps_core' ),
            'panel' => 'header_id',
        ) );
        $wp_customize->add_setting( 'cyberchimps_options[menu_home_button]', array( 'sanitize_callback' => 'cyberchimps_text_sanitization', 'type' => 'option' ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'menu_home_button', array(
            'label' => __( 'Menu Home Icon', 'cyberchimps_core' ),
            'section' => 'cyberchimps_imenu',
            'settings' => 'cyberchimps_options[menu_home_button]',
            'type' => 'checkbox'
        ) ) );
        $wp_customize->add_setting( 'cyberchimps_options[searchbar]', array( 'sanitize_callback' => 'cyberchimps_text_sanitization', 'type' => 'option' ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'searchbar', array(
            'label' => __( 'Searchbar', 'cyberchimps_core' ),
            'section' => 'cyberchimps_imenu',
            'settings' => 'cyberchimps_options[searchbar]',
            'type' => 'checkbox'
        ) ) );
    }

function cyberchimps_ifeature_upgrade_bar(){
	$upgrade_link = apply_filters( 'cyberchimps_upgrade_link', 'http://cyberchimps.com' );
	$pro_title = apply_filters( 'cyberchimps_upgrade_pro_title', 'CyberChimps Pro' );
?>
	<br>
	<div class="upgrade-callout">
		<p><img src="<?php echo get_template_directory_uri(); ?>/cyberchimps/options/lib/images/chimp.png" alt="CyberChimps"/>
			<?php printf(
				__( 'Welcome to iFeature! Get 30%% off on %1$s using Coupon Code <span style="color:red">IFEATURE30</span>', 'cyberchimps_core' ),
				'<a href="' . $upgrade_link . '" target="_blank" title="' . $pro_title . '">' . $pro_title . '</a> '
			); ?>
		</p>

	</div>
<?php
}

add_action('admin_init','remove_upgrade_bar');
function remove_upgrade_bar(){
remove_action( 'cyberchimps_options_before_container', 'cyberchimps_upgrade_bar');
}
if( cyberchimps_theme_check() == 'free' ) {
	add_action( 'cyberchimps_options_before_container', 'cyberchimps_ifeature_upgrade_bar' );
}

// enabling theme support for title tag
function ifeature_title_setup()
{
	add_theme_support( 'title-tag' );

	// enabling theme support for title tag

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );


}
add_action( 'after_setup_theme', 'ifeature_title_setup' );

/* BEGIN  Added by Swapnil - on 19-Oct 2016 for adding new feature for menu coloer change */

add_action( 'customize_register', 'ifeature_add_custmozier_field', 20 );
function ifeature_add_custmozier_field( $wp_customize ) {

$wp_customize->add_setting( 'cyberchimps_options[menu_background_colorpicker]', array(
        'default' => '',
        'type' => 'option',
        'sanitize_callback' => 'cyberchimps_text_sanitization'
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_background_colorpicker', array(
        'label' => __( 'Menu Background Color', 'ifeature' ),
        'section' => 'cyberchimps_design_section',
        'settings' => 'cyberchimps_options[menu_background_colorpicker]',
    ) ) );


$wp_customize->add_setting( 'cyberchimps_options[menu_hover_colorpicker]', array(
        'default' => '',
        'type' => 'option',
        'sanitize_callback' => 'cyberchimps_text_sanitization'
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_hover_colorpicker', array(
        'label' => __( 'Menu Hover Color', 'ifeature' ),
        'section' => 'cyberchimps_design_section',
        'settings' => 'cyberchimps_options[menu_hover_colorpicker]',
    ) ) );

$wp_customize->add_setting( 'cyberchimps_options[menu_text_colorpicker]', array(
        'default' => '',
        'type' => 'option',
        'sanitize_callback' => 'cyberchimps_text_sanitization'
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_text_colorpicker', array(
        'label' => __( 'Menu Text Color', 'ifeature' ),
        'section' => 'cyberchimps_design_section',
        'settings' => 'cyberchimps_options[menu_text_colorpicker]',
    ) ) );

        $wp_customize->add_setting( 'cyberchimps_options[flat_gradient_selector]', array(
        'type' => 'option',
        'sanitize_callback' => 'cyberchimps_sanitize_checkbox'
    ) );

    $wp_customize->add_control( 'cyberchimps_options[flat_gradient_selector]', array(
        'label' => __( 'Gradient Design', 'ifeature' ),
        'type' => 'checkbox',
        'section' => 'cyberchimps_design_section',
        'settings' => 'cyberchimps_options[flat_gradient_selector]',
    ) );

    $choices = apply_filters( 'ifeature_menu_design', array( 'default' => get_template_directory_uri() . '/inc/css/menu/images/default.jpg' ) );
    if ( count( $choices ) > 1 ) {
        $wp_customize->add_setting( 'cyberchimps_options[ifeature_menu_design]', array(
            'default' => array( 'default' => get_template_directory_uri() . '/inc/css/menu/images/default.jpg' ),
            'type' => 'option',
            'sanitize_callback' => 'cyberchimps_text_sanitization'
        ) );

        $wp_customize->add_control( new Cyberchimps_skin_selector( $wp_customize, 'skin_design', array(
            'label' => __( 'Choose a skin', 'ifeature' ),
            'section' => 'cyberchimps_design_section',
            'settings' => 'cyberchimps_options[ifeature_menu_design]',
            'choices' => $choices,
        ) ) );
    }


    $wp_customize->add_setting( 'cyberchimps_options[sticky_header]', array(
        'type' => 'option',
        'sanitize_callback' => 'cyberchimps_sanitize_checkbox'
    ) );

    $wp_customize->add_control( 'sticky_header', array(
        'label' => __( 'Sticky Header', 'ifeature' ),
        'section' => 'cyberchimps_header_section',
        'settings' => 'cyberchimps_options[sticky_header]',
        'type' => 'checkbox'
    ) );
	// Add footer widget layout option
	$imagefooterpath = get_template_directory_uri() . '/images/footer/';
	$footer_layout = apply_filters( 'cyberchimps_footer_widget_layout', array(
			'footer-4-col' => $imagefooterpath . 'footer-4-col.png',
			'footer-3-col' => $imagefooterpath . 'footer-3-col.png',
	) );
	$wp_customize->add_setting( 'cyberchimps_options[site_footer_option]', array(
			'default' => 'footer-4-col',
			'type' => 'option',
			'sanitize_callback' => 'cyberchimps_text_sanitization'
	) );

	$wp_customize->add_control( new Cyberchimps_skin_selector( $wp_customize, 'site_footer_option', array(
			'label' => __( 'Choose Footer Widgets Layout', 'cyberchimps_core' ),
			'section' => 'cyberchimps_footer_section',
			'settings' => 'cyberchimps_options[site_footer_option]',
			'choices' => $footer_layout,
	) ) );
}


add_filter( 'cyberchimps_sections_filter', 'ifeaturepro_extra_sections', 10);
function ifeaturepro_extra_sections($sections_list) {
	$sections_list[] = array(
		'id'      => 'cyberchimps_custom_skin_option_section',
		'label'   => __( 'Skin Options', 'cyberchimps_core' ),
		'heading' => 'cyberchimps_design_heading',
		'priority' => 40
	);

return $sections_list;
}

add_filter( 'cyberchimps_field_list', 'ifeature_add_field' , 30 , 1 );
function ifeature_add_field($fields_list){
$fields_list[] = array(
		'name'    => __( 'Menu Background Color', 'ifeature' ),
		'desc'    => __( 'Select menu background color', 'ifeature' ),
		'id'      => 'menu_background_colorpicker',
		'std'     => '',
		'type'    => 'color',
		'section' => 'cyberchimps_custom_colors_section',
		'heading' => 'cyberchimps_design_heading'
	);

$fields_list[] = array(
		'name'    => __( 'Menu Hover Color', 'ifeature' ),
		'desc'    => __( 'Select menu hover color', 'ifeature' ),
		'id'      => 'menu_hover_colorpicker',
		'std'     => '',
		'type'    => 'color',
		'section' => 'cyberchimps_custom_colors_section',
		'heading' => 'cyberchimps_design_heading'
	);

$fields_list[] = array(
		'name'    => __( 'Menu Text Color', 'ifeature' ),
		'desc'    => __( 'Select color for menu text', 'ifeature' ),
		'id'      => 'menu_text_colorpicker',
		'std'     => '',
		'type'    => 'color',
		'section' => 'cyberchimps_custom_colors_section',
		'heading' => 'cyberchimps_design_heading'
	);

		$fields_list[] = array(
		'name'    => __( 'Gradient Design', 'cyberchimps_core' ),
		'id'      => 'flat_gradient_selector',
		'type'    => 'toggle',
		'std'     => 'checked',
		'section' => 'cyberchimps_custom_colors_section',
		'heading' => 'cyberchimps_design_heading'
	);

		$fields_list[] = array(
		'name'    => __( 'Choose a skin', 'ifeature' ),
		'id'      => 'ifeature_menu_design',
		'desc'	  => '<a href="https://cyberchimps.com/guide/ifeature-modern-skin/" target="_blank">Recommended font settings for the Modern skin</a>',
		'std'     => 'default',
		'type'    => 'images',
		'options' => apply_filters( 'ifeature_menu_design', array(
			'default' => get_template_directory_uri() . '/inc/css/skins/images/default.png'
		) ),
		'section' => 'cyberchimps_custom_skin_option_section',
		'heading' => 'cyberchimps_design_heading'
	);

	$imagefooterpath = get_template_directory_uri() . '/images/footer/';
	$fields_list[] = array(
			'name'    => __( 'Choose Footer Widgets Layout', 'cyberchimps_core' ),
			'id'      => 'site_footer_option',
			'std'     => 'footer-4-col',
			'type'    => 'images',
			'options' => apply_filters( 'cyberchimps_footer_widget_layout', array(
					'footer-4-col' => $imagefooterpath . 'footer-4-col.png',
					'footer-3-col'  => $imagefooterpath . 'footer-3-col.png'
			) ),
			'section' => 'cyberchimps_footer_section',
			'heading' => 'cyberchimps_footer_heading'
	);

return $fields_list;

}

add_action( 'wp_head', 'ifeature_css_styles', 50 );
function ifeature_css_styles(){
	$menu_background = cyberchimps_get_option( 'menu_background_colorpicker' );
	$menu_text = cyberchimps_get_option( 'menu_text_colorpicker' );
	$menu_hover = cyberchimps_get_option( 'menu_hover_colorpicker' );
?>
	<style type="text/css" media="all">
		<?php if ( !empty( $menu_background ) ) : ?>
			.main-navigation .navbar-inner {
			background-color: <?php echo $menu_background; ?>;
			}
			.nav-collapse.in .nav li, .nav-collapse.in {
			background-color: <?php echo $menu_background; ?>;
			}
			@media (max-width: 767px) {
			.main-navigation .nav > li  {
			border-right: 1px solid <?php echo $menu_background; ?>;
			}
			}
		<?php endif; ?>

		<?php if ( !empty( $menu_hover ) ) : ?>
			.main-navigation .navbar-inner div > ul > li > a:hover {
			background-color: <?php echo $menu_hover; ?>;
			}
			.navbar-inverse .nav-collapse.in .nav > li > a:hover {
			background-color: <?php echo $menu_hover; ?>;
			}
		<?php endif; ?>

		<?php if ( !empty( $menu_text ) ) : ?>
			.main-navigation .nav > li > a, .main-navigation .nav > li > a:hover, .navbar-inverse .nav-collapse.in .nav li a,
			.navbar-inverse .nav-collapse.in .nav li a:hover {
			color: <?php echo $menu_text; ?>;
			}

		<?php endif; ?>
	</style>
<?php
}

/* END  Added by Swapnil - on 19-Oct 2016 for adding new feature for menu coloer change */


/*=========================== Fonts =====================================================*/

// Adding the default theme font Lobster in the list of fonts available in theme options
add_filter( 'cyberchimps_typography_faces', 'ifeature_typography_faces_new' );
function ifeature_typography_faces_new( $orig ) {
	$new = array(
		'"Fira Sans", sans-serif' => 'Fira Sans',
		'"Source Sans Pro",sans-serif' => 'Source Sans Pro'
	);
	$new = array_merge( $new, $orig );
	return $new;
}

// Setting defaults - body
add_filter( 'cyberchimps_typography_defaults', 'ifeature_typography_defaults' );
function ifeature_typography_defaults() {
	$default = array(
		'size' => '14px',
		'face' => '"Fira Sans", sans-serif',
		'style' => 'normal',
		'color' => ''
	);

	return $default;
}

// Setting defaults - h1
add_filter('cyberchimps_heading1_typography_defaults', 'ifeature_typography_h1');
function ifeature_typography_h1()
{
	$default = array(
		'size' => '26px',
		'face' => '"Fira Sans", sans-serif',
		'style' => '',
		'color' => '',
	);

	return $default;
}
// Setting defaults - h2
add_filter('cyberchimps_heading2_typography_defaults', 'ifeature_typography_h2');
function ifeature_typography_h2()
{
	$default = array(
		'size' => '22px',
		'face' => '"Fira Sans", sans-serif',
		'style' => '',
		'color' => '',
	);

	return $default;
}
// Setting defaults - h3
add_filter('cyberchimps_heading3_typography_defaults', 'ifeature_typography_h3');
function ifeature_typography_h3()
{
	$default = array(
		'size' => '18px',
		'face' => '"Fira Sans", sans-serif',
		'style' => '',
		'color' => '',
	);

	return $default;
}

function ifeature_customize_edit_links( $wp_customize ) {

   $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
   $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

   $wp_customize->selective_refresh->add_partial( 'blogname', array(
'selector' => '.site-title a',
'render_callback' => 'ifeature_customize_partial_blogname',
) );

	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector' => '.top-head-description',
		'render_callback' => 'ifeature_customize_partial_blogdescription',
	) );

	$wp_customize->selective_refresh->add_partial( 'cyberchimps_options[custom_logo]', array(
		'selector' => '#logo'
	) );

	$wp_customize->selective_refresh->add_partial( 'cyberchimps_options[theme_backgrounds]', array(
		'selector' => '#social'
	) );

	$wp_customize->selective_refresh->add_partial( 'cyberchimps_options[searchbar]', array(
		'selector' => '#navigation #searchform'
	) );

	$wp_customize->selective_refresh->add_partial( 'cyberchimps_options[footer_show_toggle]', array(
		'selector' => '#footer_wrapper'
	) );

	$wp_customize->selective_refresh->add_partial( 'cyberchimps_options[footer_copyright_text]', array(
		'selector' => '#copyright'
	) );

	$wp_customize->selective_refresh->add_partial( 'nav_menu_locations[primary]', array(
		'selector' => '#navigation'
	) );

}
function ifeature_customize_partial_blogname() {
bloginfo( 'name' );
}

function ifeature_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

add_action( 'customize_register', 'ifeature_customize_edit_links' );
add_theme_support( 'customize-selective-refresh-widgets' );

function ifeature_footer_widget_param( $params )
{
	global $footer_widget_counter_ifeature;
	$footer_widget_layout = cyberchimps_get_option('site_footer_option');

	if(isset($footer_widget_layout) && $footer_widget_layout != '')
		$layout = $footer_widget_layout;
	else
		$layout = '';
	$divider = 4;

	//Check if we are displaying "Footer Sidebar"
	if ( $params[0]['id'] == 'cyberchimps-footer-widgets' ) {

		//Check which footer layout is selcted
		if ($layout == 'footer-3-col')
		{
			// This is 3-col layout
			$class                      = 'class="span4 ';
			$divider = 3;
			$params[0]['before_widget'] = preg_replace('/class="/', $class, $params[0]['before_widget'],1 );

		}
		else if ($layout == 'footer-4-col')
		{
			// This is 4-col layout
			$divider = 4;
		}

		if ( $footer_widget_counter_ifeature % $divider == 0 ) {

			echo '</div> <div class="row-fluid">';
		}
		$footer_widget_counter_ifeature++;
	}

	return $params;
}
function ifeature_custom_category_widget( $arg ) {
	$excludecat = get_theme_mod( 'cyberchimps_exclude_post_cat' );

	if( $excludecat ){
		$excludecat = array_diff( array_unique( $excludecat ), array('') );
		$arg["exclude"] = $excludecat;
	}
	return $arg;
}
add_filter( "widget_categories_args", "ifeature_custom_category_widget" );
add_filter( "widget_categories_dropdown_args", "ifeature_custom_category_widget" );

function ifeature_exclude_post_cat_recentpost_widget($array){
	$s = '';
	$i = 1;
	$excludecat = get_theme_mod( 'cyberchimps_exclude_post_cat' );

	if( $excludecat ){
		$excludecat = array_diff( array_unique( $excludecat ), array('') );
		foreach( $excludecat as $c ){
			$i++;
			$s .= '-'.$c;
			if( count($excludecat) >= $i )
				$s .= ', ';
		}
	}

	$array['cat']=array($s);
	//$exclude = array( 'cat' => $s );

	return $array;
}
add_filter( "widget_posts_args", "ifeature_exclude_post_cat_recentpost_widget" );

if( !function_exists('ifeature_exclude_post_cat') ) :
function ifeature_exclude_post_cat( $query ){
	$excludecat = get_theme_mod( 'cyberchimps_exclude_post_cat' );

	if( $excludecat && ! is_admin() && $query->is_main_query() ){
		$excludecat = array_diff( array_unique( $excludecat ), array('') );
		if( $query->is_home() || $query->is_archive() ) {
			$query->set( 'category__not_in', $excludecat );
		}
	}
}
endif;
add_filter( 'pre_get_posts', 'ifeature_exclude_post_cat' );

add_action( 'cyberchimps_posted_by', 'ifeature_byline_author' );
function ifeature_byline_author()
{
	// Get url of all author archive( the page will contain all posts by the author).
$auther_posts_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );

// Set author title text which will appear on hover over the author link.
$auther_link_title = esc_attr( sprintf( __( 'View all posts by %s', 'cyberchimps_core' ), get_the_author() ) );

// Get value of post byline author toggle option from theme option for different pages.
if( is_single() ) {
	$show_author = ( cyberchimps_get_option( 'single_post_byline_author', 1 ) ) ? cyberchimps_get_option( 'single_post_byline_author', 1 ) : false;
}
elseif( is_archive() ) {
	$show_author = ( cyberchimps_get_option( 'archive_post_byline_author', 1 ) ) ? cyberchimps_get_option( 'archive_post_byline_author', 1 ) : false;
}
else {
	$show_author = ( cyberchimps_get_option( 'post_byline_author', 1 ) ) ? cyberchimps_get_option( 'post_byline_author', 1 ) : false;
}

	$posted_by = sprintf(
							'<span class="byline"> ' . __( 'by %s', 'cyberchimps_core' ),
								'<span class="author vcard">
									<a class="url fn n" href="' . $auther_posts_url . '" title="' . $auther_link_title . '" rel="author">' . esc_html( get_the_author() ) . '</a>
								</span>
								<span class="avatar">
									<a href="' . $auther_posts_url . '" title="' . $auther_link_title . '" rel="avatar">' . get_avatar( get_the_author_meta( 'ID' ), 20) . '</a>
								</span>
							</span>'

						);

	if( $show_author )
	{
			return $posted_by;
	}

	return;
}

//theme specific menu design options in array.
function ifeature_menu_design_options( $options ) {

	// Get path of image
	$imagepath = get_template_directory_uri(). '/inc/css/menu/images/';

	$options = array(
		'default'	=> $imagepath . 'default.jpg',
		'blackmenu'	=> $imagepath . 'modern.jpg'
	);
	return $options;
}
add_filter( 'ifeature_menu_design', 'ifeature_menu_design_options', 1 );



/**
 * [customizer_css description].
 *
 * @return string.
 */
function customizer_css() {

	$typography_options   = cyberchimps_get_option( 'typography_options' );
	$font_family_headings = cyberchimps_get_option( 'font_family_headings' );

	$font_family                = $typography_options['face'] ? $typography_options['face'] : '"Fira Sans", sans-serif';
	$font_size                  = $typography_options['size'] ? $typography_options['size'] : '14px';
	$font_weight                = $typography_options['style'] ? $typography_options['style'] : 'Normal';
	$color                      = cyberchimps_get_option( 'text_colorpicker' ) ? cyberchimps_get_option( 'text_colorpicker' ) : '#777';
	$link_colorpicker           = cyberchimps_get_option( 'link_colorpicker' ) ? cyberchimps_get_option( 'link_colorpicker' ) : '#0088cc';
	$link_hover_colorpicker     = cyberchimps_get_option( 'link_hover_colorpicker' ) ? cyberchimps_get_option( 'link_hover_colorpicker' ) : '#005580';
	$if_font_family_headings = $font_family_headings['face'] ? $font_family_headings['face'] : 'Arial, Helvetica, sans-serif';

	$get_background_color  = get_background_color() ? get_background_color() : 'fff';
	$get_background_image1 = get_template_directory_uri() . '/cyberchimps/lib/images/backgrounds/' . get_theme_mod( 'cyberchimps_background' ) . '.jpg';
	$get_background_image1 = $get_background_image1 ? $get_background_image1 : '';
	$get_background_image2 = get_background_image() ? get_background_image() : '';
	$get_background_image  = $get_background_image2 ? $get_background_image2 : $get_background_image1;

	$custom_css = ".editor-writing-flow,
	.editor-styles-wrapper{
		background-color:#{$get_background_color};
		background-image:url('{$get_background_image}');
		font-family: {$font_family};
		font-size: {$font_size};
		font-weight: {$font_weight};
		color: {$color};
		line-height: 1.5;
	}
	.wp-block-freeform.block-library-rich-text__tinymce h1,
	.wp-block-freeform.block-library-rich-text__tinymce h2,
	.wp-block-freeform.block-library-rich-text__tinymce h3,
	.wp-block-freeform.block-library-rich-text__tinymce h4,
	.wp-block-freeform.block-library-rich-text__tinymce h5,
	.wp-block-freeform.block-library-rich-text__tinymce h6,
	.wp-block-heading h1.editor-rich-text__tinymce,
	.wp-block-heading h2.editor-rich-text__tinymce,
	.wp-block-heading h3.editor-rich-text__tinymce,
	.wp-block-heading h4.editor-rich-text__tinymce,
	.wp-block-heading h5.editor-rich-text__tinymce,
	.wp-block-heading h6.editor-rich-text__tinymce {
		font-family: {$if_font_family_headings};
		font-weight: normal;
		margin-bottom: 15px;
	}

	.editor-post-title__block .editor-post-title__input{
		font-family: {$if_font_family_headings} !important;
	}

	.wp-block-freeform.block-library-rich-text__tinymce a,
	.editor-writing-flow a{
		color: {$link_colorpicker};
		text-decoration: none;
	}

	.wp-block-freeform.block-library-rich-text__tinymce a:hover,
	.wp-block-freeform.block-library-rich-text__tinymce a:focus,
	.editor-writing-flow a:hover,
	.editor-writing-flow a:focus{
		color:  {$link_hover_colorpicker};
	}";
	return $custom_css;
}


/**
 *  Enqueue block styles  in editor
 */
function ifeature_block_styles() {
	wp_enqueue_style( 'ifeature-google-font', 'https://fonts.googleapis.com/css?family=Open+Sans|Titillium+Web|Lobster', array(), '1.0' );

	wp_add_inline_style( 'ifeature-google-font', customizer_css() );

	wp_enqueue_style( 'ifeature-gutenberg-blocks', get_stylesheet_directory_uri() . '/inc/css/gutenberg-blocks.css', array(), '1.0' );
}
add_action( 'enqueue_block_editor_assets', 'ifeature_block_styles' );

// add styles for skin selection
function ifeature_menu_design_styles() {
	$skin = cyberchimps_get_option( 'ifeature_menu_design' );

	if( isset($skin) && $skin!= '' && $skin != 'default' ) {
		wp_enqueue_style( 'ifeature-menu-design', get_template_directory_uri() . '/inc/css/menu/' . $skin . '.css', array( 'style' ), '1.0' );
	}

	$skin = cyberchimps_get_option( 'cyberchimps_skin_color' );
	if(cyberchimps_get_option( 'flat_gradient_selector' )=='')
	{
	$skin=$skin.'-nongrad.css';
	if($skin !== 'default-nongrad.css' )
			wp_enqueue_style( 'ifeature-non-gradient-design', get_template_directory_uri() . '/inc/css/skins/' . $skin, array( 'style' ), '1.0' );
	}
}
add_action( 'wp_enqueue_scripts', 'ifeature_menu_design_styles', 55 );

add_action( 'admin_head', 'ifeaturepro_modern_skin_css');
function ifeaturepro_modern_skin_css()
{
?>
<style>
#cyberchimps_custom_skin_option_section .desc
{
	margin-left:50%;
}
</style>
<?php
}

function ifeature_blog_styles() {
	if(cyberchimps_get_option('sidebar_images')=="three-column")
	{
	wp_enqueue_style( 'three-column-blog', get_template_directory_uri() . '/inc/css/blog-layout/three-column.css', array( 'style' ), '1.0' );
	}
}
add_action( 'wp_enqueue_scripts', 'ifeature_blog_styles', 30 );

add_action( 'blog_layout_options', 'ifeature_blog_templates');
function ifeature_blog_templates()
{
$imagepath = get_template_directory_uri() . '/cyberchimps/lib/images/';

$vat=array(
			'full_width'    => $imagepath . '1col.png',
			'right_sidebar' => $imagepath . '2cr.png',
			'three-column' => get_template_directory_uri() . '/images/3col.png'
	);


return $vat;
}

function ifeature_featured_image() {
	global $post;

		$show = ( cyberchimps_get_option( 'post_featured_images', 1 ) ) ? cyberchimps_get_option( 'post_featured_images', 1 ) : false;


	if( $show ):
		if( has_post_thumbnail() ): ?>
			<div class="featured-image">
				<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cyberchimps_core' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
					<?php the_post_thumbnail( apply_filters( 'cyberchimps_post_thumbnail_size', 'full' ) ); ?>
				</a>
			</div>
		<?php    endif;
	endif;
}

/*function ifeature_cyberchimps_selected_blog_elements() {
	$options = array(
			'boxes_lite'     => __( 'Boxes Lite', 'cyberchimps_core' ),
			"portfolio_lite" => __( 'Portfolio Lite', 'cyberchimps_core' ),
			"page_section"   => __( 'Page', 'cyberchimps_core' ),
			"slider_lite"    => __( 'Slider Lite', 'cyberchimps_core' )

	);

	return $options;
}
add_filter( 'cyberchimps_elements_draganddrop_options', 'cyberchimps_selected_elements' );
add_filter( 'cyberchimps_elements_draganddrop_options', 'ifeature_cyberchimps_selected_blog_elements' );

function ifeature_cyberchimps_selected_page_elements() {
	$options = array(
			'boxes_lite'     => __( 'Boxes Lite', 'cyberchimps_core' ),
			"portfolio_lite" => __( 'Portfolio Lite', 'cyberchimps_core' ),
			"page_section"   => __( 'Page', 'cyberchimps_core' ),
			"slider_lite"    => __( 'Slider Lite', 'cyberchimps_core' ),
	);

	return $options;
}
add_filter( 'cyberchimps_elements_draganddrop_page_options', 'cyberchimps_selected_page_elements' );
add_filter( 'cyberchimps_elements_draganddrop_page_options', 'ifeature_cyberchimps_selected_page_elements' );
*/
