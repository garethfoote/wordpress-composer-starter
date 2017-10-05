<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});

	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

Timber::$dirname = array('templates', 'views');
Routes::map('workshop/:name/:page', function($params){
    $query_workshop = sprintf('post_type=workshop&post_status=publish&numberposts=1&name=%s', $params['name']);
    $query_post = sprintf('post_type=post&post_status=publish&numberposts=1&name=%s', $params['page']);

    // Does workshop and post exist
    $workshop = Timber::query_post($query_workshop);
    $post = Timber::query_post($query_post);

    // Does post contain this workshop in 'terms'. i.e. does workshop contain this post.
    $found = false;
    foreach ($workshop->get_field('related_posts') as $related) {
      if($related->id == $workshop->id){
        $found = true;
      }
    }

    // Does post contain this workshop in 'terms'. i.e. does workshop contain this post.
    // $found = false;
    // foreach ($post->terms as $term) {
    //   if($term->id == $workshop->id){
    //     $found = true;
    //   }
    // }

    if(!$workshop->id || !$post->id || $found == false){
      Routes::load('404.php', null, null, 404);
    } else {
      $params['workshop'] = $workshop;
      Routes::load('single.php', $params, $query, 200);
    }
});

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}

	function register_post_types() {
		//this is where you can register custom post types
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function add_to_context( $context ) {
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::get_context();';
		$context['menu'] = new TimberMenu();
		$context['site'] = $this;
		return $context;
	}

	function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
		return $twig;
	}

}

new StarterSite();

/* ------ ADMIN ------- */
Jigsaw::add_column('post', 'Workshop', function($pid){
  $data = array();
	$data['post'] = new TimberPost($pid);
  Timber::render('admin/admin-tax-workshops.twig', $data);
}, 3);


/* ------ Bidirectional relationship ------- */

function bidirectional_acf_update_value( $value, $post_id, $field  ) {
	// vars
	$field_name = $field['name'];
	$field_key = $field['key'];
	$global_name = 'is_updating_' . $field_name;

	// bail early if this filter was triggered from the update_field() function called within the loop below
	// - this prevents an inifinte loop
	if( !empty($GLOBALS[ $global_name ]) ) return $value;

	// set global variable to avoid inifite loop
	// - could also remove_filter() then add_filter() again, but this is simpler
	$GLOBALS[ $global_name ] = 1;

	// loop over selected posts and add this $post_id
	if( is_array($value) ) {
		foreach( $value as $post_id2 ) {
			// load existing related posts
			$value2 = get_field($field_name, $post_id2, false);
			// allow for selected posts to not contain a value
			if( empty($value2) ) {
				$value2 = array();
			}
			// bail early if the current $post_id is already found in selected post's $value2
			if( in_array($post_id, $value2) ) continue;
			// append the current $post_id to the selected post's 'related_posts' value
			$value2[] = $post_id;
			// update the selected post's value (use field's key for performance)
			update_field($field_key, $value2, $post_id2);
		}
	}

	// find posts which have been removed
	$old_value = get_field($field_name, $post_id, false);
	if( is_array($old_value) ) {
		foreach( $old_value as $post_id2 ) {
			// bail early if this value has not been removed
			if( is_array($value) && in_array($post_id2, $value) ) continue;
			// load existing related posts
			$value2 = get_field($field_name, $post_id2, false);
			// bail early if no value
			if( empty($value2) ) continue;
			// find the position of $post_id within $value2 so we can remove it
			$pos = array_search($post_id, $value2);
			// remove
			unset( $value2[ $pos] );
			// update the un-selected post's value (use field's key for performance)
			update_field($field_key, $value2, $post_id2);
		}
	}
	// reset global varibale to allow this filter to function as per normal
	$GLOBALS[ $global_name ] = 0;

	// return
    return $value;
}

add_filter('acf/update_value/name=related_posts', 'bidirectional_acf_update_value', 10, 3);
