<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$context = Timber::get_context();

$args = array(
	'numberposts'	=> -1,
	'post_type'		=> 'post',
	'meta_query'	=> array(
    'relation'		=> 'OR',
		array(
			'key'	 	=> 'only_workshop',
			'value'	  	=> 0,
			'compare' 	=> '=',
		),
    array(
      'key'	 	=> 'only_workshop',
      'compare' 	=> 'NOT EXISTS',
    )
	),
);

$context['posts'] = Timber::get_posts($args);
$context['foo'] = 'bar';
$templates = array( 'index.twig' );
$context['workshops'] = Timber::get_posts('post_type=workshop');

if ( is_home() ) {
	array_unshift( $templates, 'home.twig' );
}
Timber::render( $templates, $context );
