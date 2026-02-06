<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Catch Themes
 * @subpackage Catch Kathmandu
 * @since Catch Kathmandu 1.0
 */
?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'wp_body_open' );  ?>

<?php
/**
 * catchkathmandu_before hook
 */
do_action( 'catchkathmandu_before' ); ?>

<div id="page" class="hfeed site">

	<?php
    /**
     * catchkathmandu_before_header hook
     */
    do_action( 'catchkathmandu_before_header' ); ?>

	<header id="masthead" role="banner">

    	<?php
		/**
		 * catchkathmandu_before_hgroup_wrap hook
		 *
		 * HOOKED_FUNCTION_NAME PRIORITY
		 *
		 * catchkathmandu_header_top 10
		 */
		do_action( 'catchkathmandu_before_hgroup_wrap' ); ?>

    	<div id="hgroup-wrap" class="container">

       		<?php
			/**
			 * catchkathmandu_hgroup_wrap hook
			 *
			 * HOOKED_FUNCTION_NAME PRIORITY
			 *
			 * catchkathmandu_header_image 10
			 * catchkathmandu_header_right 15
			 */
			do_action( 'catchkathmandu_hgroup_wrap' ); ?>

        </div><!-- #hgroup-wrap -->

        <?php
		/**
		 * catchkathmandu_after_hgroup_wrap hook
		 *
		 * HOOKED_FUNCTION_NAME PRIORITY
		 *
		 * catchkathmandu_featured_overall_image 10
		 * catchkathmandu_secondary_menu 20
		 * catchkathmandu_breadcrumb_display 30
		 */
		do_action( 'catchkathmandu_after_hgroup_wrap' ); ?>

	</header><!-- #masthead .site-header -->

	<?php
    /**
     * catchkathmandu_after_header hook
     */
    do_action( 'catchkathmandu_after_header' ); ?>

	<?php
    /**
     * catchkathmandu_before_main hook
	 *
	 * HOOKED_FUNCTION_NAME PRIORITY
	 *
	 * catchkathmandu_slider_display 10
	 * catchkathmandu_homepage_headline 15
     */
    do_action( 'catchkathmandu_before_main' ); ?>

    <div id="main" class="container">

		<?php
        /**
         * catchkathmandu_main hook
         *
         * HOOKED_FUNCTION_NAME PRIORITY
         *
	 	 * catchkathmandu_homepage_featured_display 10
         */
        do_action( 'catchkathmandu_main' ); ?>

		<?php
        /**
         * catchkathmandu_content_sidebar_start hook
         *
         * HOOKED_FUNCTION_NAME PRIORITY
         *
	 	 * catchkathmandu_homepage_featured_display 10
         */
        do_action( 'catchkathmandu_content_sidebar_start' );
