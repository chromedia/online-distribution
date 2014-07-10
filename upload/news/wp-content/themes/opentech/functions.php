<?php
	
	// Add RSS links to <head> section
	automatic_feed_links();
	
	// Load jQuery
	if ( !is_admin() ) {
	   wp_deregister_script('jquery');
	   wp_enqueue_script('jquery');
	}
	add_theme_support( 'post-thumbnails');
	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');
    
    if (function_exists('register_sidebar')) {
    	register_sidebar(array(
    		'name' => 'Sidebar Widgets',
    		'id'   => 'sidebar-widgets',
    		'description'   => 'These are widgets for the sidebar.',
    		'before_widget' => '<div id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</div>',
    		'before_title'  => '<h3>',
    		'after_title'   => '</h3>'
    	));
    }
    #-----------------------------------------------------------------#
# Widget areas
#-----------------------------------------------------------------#
if(function_exists('register_sidebar')) {
    
    register_sidebar(array('name' => 'Blog Sidebar', 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget'  => '</div>', 'before_title'  => '<h3>', 'after_title'   => '</h3>'));
    register_sidebar(array('name' => 'Page Sidebar', 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget'  => '</div>', 'before_title'  => '<h3>', 'after_title'   => '</h3>'));
    register_sidebar(array('name' => 'WooCommerce Sidebar', 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget'  => '</div>', 'before_title'  => '<h3>', 'after_title'   => '</h3>'));
    

    register_sidebar(array('name' => 'Page2 Sidebar', 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget'  => '</div>', 'before_title'  => '<h3>', 'after_title'   => '</h3>'));
    register_sidebar(array('name' => 'Page3 Sidebar', 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget'  => '</div>', 'before_title'  => '<h3>', 'after_title'   => '</h3>'));
    

    register_sidebar(array('name' => 'Footer Area 1', 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget'  => '</div>', 'before_title'  => '<h3>', 'after_title'   => '</h3>'));
    register_sidebar(array('name' => 'Footer Area 2', 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget'  => '</div>', 'before_title'  => '<h3>', 'after_title'   => '</h3>'));
    
    global $options; 
    $footerColumns = (!empty($options['footer_columns'])) ? $options['footer_columns'] : '4';
    if($footerColumns == '3' || $footerColumns == '4'){
        register_sidebar(array('name' => 'Footer Area 3', 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget'  => '</div>', 'before_title'  => '<h3>', 'after_title'   => '</h3>'));
    }
    if($footerColumns == '4'){
        register_sidebar(array('name' => 'Footer Area 4', 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget'  => '</div>', 'before_title'  => '<h3>', 'after_title'   => '</h3>'));
    }
}
#-----------------------------------------------------------------#
# Custom widgets
#-----------------------------------------------------------------#

//Recent Posts Extra
include('includes/custom-widgets/recent-posts-extra-widget.php');

//Recent portfolio items
// include('includes/custom-widgets/recent-projects-widget.php');

?>