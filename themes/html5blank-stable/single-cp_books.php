<?php
/*
Template Name: Elementor Canvas CPT Books
Template Post Type: cp_books
*/


get_header();

// Elementor `single` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
	get_template_part( 'template-parts/single' );
}

// echo get_page_template_slug();

get_footer();