<?php
	
	if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['favs']) && !empty($_POST['favs']) )  $this->SaveSettingsPage();
		
		if( !function_exists('oas_get_page_anchor_tag') ) 
		{
?>
    <div id="plugin-not-found">
    	<strong>OAS ToolBox</strong>
    	<?php _e("plugin not found!"); ?> Sticky Posts <?php _e("may not function properly without!"); ?> OAS ToolBox plugin. <?php _e("You can download it here"); ?> http://wordpress.org/extend/plugins/oas-toolbox/
    </div>
<?php	} ?>

<div class="wrap">
  <div class="wrap nosubsub">
    <div id="icon-options-general" class="icon32"> <br /></div>
    	<h2><?php _e("Sticky Posts Settings"); ?></h2>
    
    <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
      
      <h3><?php _e("Sticky Posts"); ?></h3>
     
      <table id="table-favourite-posts-list" class="widefat post fixed" cellspacing="0">
        <thead>
          <tr>
            <th scope="col" id="cb" class="manage-column column-cb check-column" style="">&nbsp;</th>
            <th scope="col" id="title" class="manage-column column-title" style=""><?php _e('Post'); ?></th>
            <th scope="col" class="manage-column column-id"><?php _e('Post ID'); ?></th>
            <th scope="col" id="author" class="manage-column column-author" style=""><?php _e('Author'); ?></th>
            <th scope="col" id="date" class="manage-column column-date" style=""><?php _e('Date'); ?></th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th scope="col" id="cb" class="manage-column column-cb check-column" style="">&nbsp;</th>
            <th scope="col" id="title" class="manage-column column-title" style=""><?php _e('Post'); ?></th>
            <th scope="col" class="manage-column column-id"><?php _e('Post ID'); ?></th>
            <th scope="col" id="author" class="manage-column column-author" style=""><?php _e('Author'); ?></th>
            <th scope="col" id="date" class="manage-column column-date" style=""><?php _e('Date'); ?></th>
          </tr>
        </tfoot>
        <tbody>
	<?php
		$posts = get_posts('numberposts=-1');
		$FavouritePosts = $this->StickyPosts();
		foreach($posts as $post)
		{
    ?>
          <tr class='author-self status-publish iedit <?php echo $TableRowClass; ?>' valign="top">
            <th class="check-column"><input name="favs[]" <?php $this->GetCheckBox($post->ID, $FavouritePosts); ?> type="checkbox" value="<?php echo $post->ID; ?>" /></th>
            <td class="post-title column-title"><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></td>
            <td class="post-id column-id"><?php echo $post->ID; ?></td>
            <td class="author column-author"><?php echo get_userdata($post->post_author)->display_name; ?></td>
            <td class="categories column-date"><?php echo $post->post_date; ?></td>
          </tr>
	<?php 
    	} 
    ?>
        </tbody>
      </table>
      
      <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" /></p>
      
    </form>
  </div>
</div>
