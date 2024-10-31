<?php		
        if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['oa-favourite-post-title']) ) $this->SaveWidgetSettings();
		
		$option = $this->WidgetSettings();
?>
        <p>
        	<label for="oa-favourite-post-title"><strong><?php _e('Title:'); ?></strong>
        		<input class="widefat" id="oa-favourite-post-title" name="oa-favourite-post-title" value="<?php echo stripslashes_deep($option['title']); ?>" type="text" />
        	</label>
        </p>
        <p>
            	<select name="oa-favourite-category-view" id="oa-favourite-category-view">
                	<option value="Y">Show category next to favourite link</option>
                    <option value="N" <?php echo ($option['category'] == 'N')? ' selected="selected" ' : ''; ?>>Do not show category</option>
                </select>
        </p>
        <p>
        	<label><strong><?php _e('Display Order:'); ?></strong></label>
        	<ul class="sortable_favourite_list" id="oawp-favourite-posts-widget">
			<?php
            	$FavouritePosts = $this->StickyPosts();
				foreach($FavouritePosts as $post)
				{
					?>
					<li id="oafp-<?php echo $post; ?>"><?php echo get_post_field( 'post_title', $post ); ?></li>
					<?php
				}				
			?>
            </ul>
            <input type="hidden" id="oawp-favourite-posts-widget-order" name="oawp-favourite-posts-widget-order" value=""  />
        </p>