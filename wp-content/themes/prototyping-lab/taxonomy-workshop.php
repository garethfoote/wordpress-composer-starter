<?php
/**
 * The Template for displaying posts within taxonomy:worksohp
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::get_context();
$context['posts'] = Timber::get_posts();
$context['term'] = Timber::get_term('workshop');
Timber::render( 'taxonomy-workshop.twig', $context );
