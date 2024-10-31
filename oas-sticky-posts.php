<?php
	/*
	Plugin Name: OAS Sticky Posts
	Plugin URI: http://wordpress.org/extend/plugins/oas-sticky-posts/
	Description: OAS Sticky Posts is a side-bar widget that lists & links to important blog posts.
	Version: 1.0
	Author: Online Associates, UAE
	Author URI: http://www.onlineassociates.ae
	*/
	
	if ( ! defined( 'WP_CONTENT_URL' ) ) define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	if ( ! defined( 'WP_CONTENT_DIR' ) ) define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	if ( ! defined( 'WP_PLUGIN_URL' ) ) define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
	if ( ! defined( 'WP_PLUGIN_DIR' ) )	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
	
	if ( ! defined( 'OAS_STICKY_POST_URL' ) )	define( 'OAS_STICKY_POST_URL', WP_PLUGIN_URL . '/oas-sticky-posts/' );
	if ( ! defined( 'OAS_STICKY_POST_DIR' ) )	define( 'OAS_STICKY_POST_DIR', WP_PLUGIN_DIR . '/oas-sticky-posts/' );
	
	if ( !isset($G_OasStickyPost) )	$G_OasStickyPost = new OasStickyPost();
	
	class OasStickyPost
	{
	
		function OasStickyPost()
		{
			if ( version_compare($GLOBALS['wp_version'], '2.7', '>=') ) $this->ConfigInit();
		}
		
		function ConfigInit()
		{
			if( function_exists('add_action') )
			{
				add_action('init', array(&$this, 'LoaderInit') );
				add_action( 'plugins_loaded', array(&$this, 'RegisterWidgets') );
			}	
		}
		
		function RegisterWidgets()
		{
			register_sidebar_widget( 'OAS Sticky Posts', array(&$this, 'SidebarWidget') );
			register_widget_control( 'OAS Sticky Posts', array(&$this, 'WidgetOptions') );
			add_action( 'admin_head-widgets.php', array(&$this, 'AttachCSS') );
			add_action( 'admin_print_scripts-widgets.php', array(&$this, 'AttachJscripts') );			
		}
		
		function LoaderInit()
		{
			add_action('admin_menu', array(&$this, 'MenuPage') );
		}
		
		function MenuPage()
		{
			if( defined('OAS_ADMIN_MENU') )
			{
				$AddStickyPage = add_submenu_page( OAS_ADMIN_MENU, 'OAS Sticky Posts', 'OAS Sticky Posts', 8, __FILE__, array(&$this, 'SettingsPage') );
			}	
			else if( function_exists('add_options_page') )
			{
				$AddStickyPage = add_options_page("OAS Sticky Posts", "OAS Sticky Posts", 8, 'OasStickyPosts', array(&$this, 'SettingsPage') );
			}
			
			if($AddStickyPage)
			{
				add_action("admin_print_scripts-$AddStickyPage", array(&$this, 'AttachJscripts') );
				add_action("admin_head-$AddStickyPage", array(&$this, 'AttachCSS') );		
			}	
		}
		
		//
		function AttachJscripts()
		{
			wp_enqueue_script( 'oas-sticky-scripts', OAS_STICKY_POST_URL . "resources/widget.js", array('jquery', 'jquery-ui-core', 'jquery-ui-sortable') );
			wp_enqueue_script( 'jquery-tablesorter', OAS_STICKY_POST_URL . "resources/jquery.tablesorter.min.js", array('jquery') );		
		}
		
		//Attach CSS to admin page, action hooks pointed to Sticky post settings page so it won't appear on other pages.
		function AttachCSS()
		{
			$File = OAS_STICKY_POST_DIR . 'resources/widget.css';
			if( file_exists($File) ) echo '<link href="' .OAS_STICKY_POST_URL . 'resources/widget.css" rel="stylesheet" type="text/css" />' . "\n";	
		}
	
		
		/* *************** | Settings Page | ***************** */
		
		//Template : Settings (OAS Plugins) > OAS Sticky Post Main Admin Template
		function SettingsPage()
		{
			$File = OAS_STICKY_POST_DIR . 'resources/settings.php';
			if( file_exists( $File ) ) include($File);
			else _e('Favourite Post option page not found!');	
		}
		
		//Settings (OAS Plugins) > OAS Sticky Post ( Save the changes )
		function SaveSettingsPage()
		{
			$OrderedListData = get_option('oawp_favourite_posts');
			$NewFavourites = $_POST['favs'];
			
			if( !empty($OrderedListData) && !empty($NewFavourites) && is_array($NewFavourites) )
			{
				$PreExistingOnDB = array_intersect($OrderedListData, $NewFavourites);
				$NewlyAddedList = array_diff($NewFavourites, $OrderedListData);
				$UpdateList = array_merge($PreExistingOnDB, $NewlyAddedList);
			}
			else if( !empty($NewFavourites) && is_array($NewFavourites) )
			{
				$UpdateList = $NewFavourites;
			}
			
			if( !empty($UpdateList) ) update_option( 'oawp_favourite_posts', $UpdateList );	
		}
		
		//Retrieve the favourite post list from option table
		function StickyPosts()
		{
			 if( get_option('oawp_favourite_posts') )
			 {
				$PageList = get_option('oawp_favourite_posts');
				if(!empty($PageList)) return $PageList;
			 }
		}	
		
		//Restore if the option is checked
		function GetCheckBox($Current, $FavList)
		{
			if( is_array($FavList) && in_array($Current, $FavList) ) echo ' checked="checked" ';
		}
		
		
		/* *************** | Appearence Widget | ***************** */	
	
		//Widget Settings
		function WidgetSettings( $OptionName=false )
		{
			$Options = get_option('oawp_favourite_posts_widget');
			return ($OptionName) ? $Options[$OptionName] : $Options;
		}
		
		//Update Widget
		function SaveWidgetSettings()
		{
			if( !empty($_POST['oawp-favourite-posts-widget-order']) )
			{
				$Order = preg_replace('/[^0-9\,]/', '', $_POST['oawp-favourite-posts-widget-order']);
				$FavouriteOrder = explode(',', $Order);
				if( !empty($FavouriteOrder) ) update_option( 'oawp_favourite_posts', $FavouriteOrder );
			}
			
			$SaveWidget = array();
			$SaveWidget['title'] = attribute_escape($_POST['oa-favourite-post-title']);
			$SaveWidget['category'] = $_POST['oa-favourite-category-view'];
			
			update_option( 'oawp_favourite_posts_widget', $SaveWidget );
		}
		
		function WidgetOptions()
		{
			$File = OAS_STICKY_POST_DIR . 'resources/widget.php';
			if( file_exists( $File ) ) include($File);
			else _e('Widget Options Page not found!');		
		}
		
		
		/* *************** | Sidebar Widget | ***************** */
		
		//Actual User sidebar list
		function SidebarWidget($args)
		{
			if( !function_exists('oas_get_page_anchor_tag') ) return false;
			
			extract($args);
			
			$option = $this->WidgetSettings();
			$title = ( empty($option['title']) ) ? __('OAS Sticky Posts') : apply_filters('widget_title', stripslashes_deep($option['title']) );
			
			echo $before_widget . $before_title . $title . $after_title . '<ul>' . "\n";
			
			$FavouritePosts = $this->StickyPosts();
			
			if( is_array($FavouritePosts) && !empty($FavouritePosts) )
			{
				foreach($FavouritePosts as $post)
				{
					if( $option['category'] != 'N' )
					{
						$CategoryName = get_the_category($post);
						$CategoryName = ( ( !empty($CategoryName) )? ' (' . $CategoryName[0]->cat_name . ')' : '' );				
					}					
					?>
					<li><?php if( function_exists('oas_get_page_anchor_tag') ) echo oas_get_page_anchor_tag($post); ?><?php echo $CategoryName; ?></li>
					<?php
				}
			}		
			echo  '</ul>' . "\n" . $after_widget;
		}
		
		
	}
		
?>