<?php
/**
 * SVG icons related functions and filters.
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 */

/**
 * Add SVG definitions to the footer.
 */
function twentyseventeen_include_svg_icons() {

	// Define SVG sprite file.
	$svg_icons = get_template_directory() . '/assets/images/svg-icons.svg';

	// If it exists, include it.
	if ( file_exists( $svg_icons ) ) {
		echo file_get_contents( $svg_icons );
	}

}
add_action( 'wp_footer', 'twentyseventeen_include_svg_icons', 9999 );

/**
 * Return SVG markup.
 *
 * @param  array  $args {
 *     Parameters needed to display an SVG.
 *
 *     @param string $icon Required. Use the icon filename, e.g. "facebook-square".
 *     @param string $title Optional. SVG title, e.g. "Facebook".
 *     @param string $desc Optional. SVG description, e.g. "Share this post on Facebook".
 * }
 * @return string SVG markup.
 */
function twentyseventeen_get_svg( $args = array() ) {

	// Make sure $args are an array.
	if ( empty( $args ) ) {
		return esc_html__( 'Please define default parameters in the form of an array.', 'twentyseventeen' );
	}

	// Define an icon.
	if ( false === array_key_exists( 'icon', $args ) ) {
		return esc_html__( 'Please define an SVG icon filename.', 'twentyseventeen' );
	}

	// Set defaults.
	$defaults = array(
		'icon'        => '',
		'title'       => '',
		'desc'        => '',
		'aria_hidden' => true, // Hide from screen readers.
	);

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Set aria hidden.
	$aria_hidden = '';

	if ( true === $args['aria_hidden'] ) {
		$aria_hidden = ' aria-hidden="true"';
	}

	// Set ARIA.
	$aria_labelledby = '';

	if ( $args['title'] && $args['desc'] ) {
		$aria_labelledby = ' aria-labelledby="title desc"';
	}

	// Begin SVG markup.
	$svg = '<svg class="icon icon-' . esc_html( $args['icon'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img">';

		// If there is a title, display it.
		if ( $args['title'] ) {
			$svg .= '<title>' . esc_html( $args['title'] ) . '</title>';
		}

		// If there is a description, display it.
		if ( $args['desc'] ) {
			$svg .= '<desc>' . esc_html( $args['desc'] ) . '</desc>';
		}

		// Use absolute path in the Customizer so that icons show up in there.
		if ( is_customize_preview() ) {
			$svg .= '<use xlink:href="' . get_template_directory_uri() . '/assets/images/svg-icons.svg' . '#icon-' . esc_html( $args['icon'] ) . '"></use>';
		} else {
			$svg .= '<use xlink:href="#icon-' . esc_html( $args['icon'] ) . '"></use>';
		}

		$svg .= '</svg>';

	return $svg;

}

/**
 * Display an SVG icon.
 *
 * @param  array $args Parameters needed to display an SVG.
 */
function twentyseventeen_do_svg( $args = array() ) {
	echo twentyseventeen_get_svg( $args );
}

/**
 * Display SVG icons in social links menu.
 *
 * @param string  $item_output The menu item output.
 * @param WP_Post $item        Menu item object.
 * @param int     $depth       Depth of the menu.
 * @param array   $args        wp_nav_menu() arguments.
 * @return string $item_output The menu item output with social icon.
 */
function twentyseventeen_nav_menu_social_icons( $item_output, $item, $depth, $args ) {

	// Get supported social icons.
	$social_icons = twentyseventeen_social_links_icons();

	// Change SVG icon inside social links menu if there is supported URL.
	if ( 'social' == $args->theme_location ) {
		foreach ( $social_icons as $attr => $value ) {
			if ( false !== strpos( $item_output, $attr ) ) {
				$item_output = str_replace( $args->link_after, '</span>' . twentyseventeen_get_svg( array( 'icon' => esc_attr( $value ) ) ), $item_output );
			}
		}
	}

	return $item_output;

}
add_filter( 'walker_nav_menu_start_el', 'twentyseventeen_nav_menu_social_icons', 10, 4 );

/**
 * Returns an array of supported social links (URL and icon name).
 *
 * @return array $social_links_icons
 */
function twentyseventeen_social_links_icons() {

	// Supported social links icons.
	$social_links_icons = array(
		'codepen.io'      => 'codepen',
		'digg.com'        => 'digg',
		'dribbble.com'    => 'dribbble',
		'dropbox.com'     => 'dropbox',
		'facebook.com'    => 'facebook',
		'flickr.com'      => 'flickr',
		'foursquare.com'  => 'foursquare',
		'plus.google.com' => 'googleplus',
		'github.com'      => 'github',
		'instagram.com'   => 'instagram',
		'linkedin.com'    => 'linkedin-alt',
		'mailto:'         => 'mail',
		'path.com'        => 'pinterest-alt',
		'pinterest.com'   => 'pinterest-alt',
		'getpocket.com'   => 'pocket',
		'polldaddy.com'   => 'polldaddy',
		'reddit.com'      => 'reddit',
		'skype.com'       => 'skype',
		'skype:'          => 'skype',
		'soundcloud.com'  => 'cloud',
		'spotify.com'     => 'spotify',
		'stumbleupon.com' => 'stumbleupon',
		'tumblr.com'      => 'tumblr',
		'twitch.tv'       => 'twitch',
		'twitter.com'     => 'twitter',
		'vimeo.com'       => 'vimeo',
		'wordpress.org'   => 'wordpress',
		'wordpress.com'   => 'wordpress',
		'youtube.com'     => 'youtube',
	);

	return apply_filters( 'twentyseventeen_social_links_icons', $social_links_icons );

}
