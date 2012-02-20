<?php
    // Override the built in Wordpress Query, so we can get out custom events
    global $wp_query;
    parse_str($query_string, $args);
    $args['post_type'] = array('post', 'isense_event', 'isense_documentation');
    query_posts($args);
?>

<?php while(have_posts()) { ?>
    <?php the_post(); // This is just dumb ?>
    <div class="result">
    
        <table width="100%" cellpaddding="0" cellspacing="0">
			<tr>
			    <td valign="top">
			        <div class="name">
                        <a href="<?php the_permalink(); ?>">
                            <?php
                                $type = get_post_type();
                                switch($type) {
                                    case "isense_event":
                                        echo "Event:";
                                        break;
                                    
                                    case "isense_documentation":
                                        echo "Guide:";
                                        break;
                                }
                            ?>
                            <? the_title(); ?>
                        </a>
                    </div>
                    <div class="description">
                        <?php
                            if(has_excerpt()) {
                                echo str_replace("</p>", "", str_replace("<p>", "", $post->post_excerpt));
                            }
                            else {
                                echo "No Excerpt";
                            }
                        ?>
                    </div>
                    <div class="sub">
                        <?php isense_posted_on(); ?>
                        <?php edit_post_link(__( 'Edit'), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
                    </div>
                    <?php $tags_list = get_the_tag_list( '', ', ' ); ?>
                    <?php if($tags_list) { ?>
                        <div class="sub">
                            <?php printf( __( '<span class="%1$s">Tagged with</span> %2$s'), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list); ?>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </div>
<?php } ?>

<?php if($wp_query->max_num_pages > 1) { ?>
	<div id="nav-below" class="navigation">
		<div class="nav-previous">
		    <?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts')); ?>
		</div>
		<div class="nav-next">
		    <?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>')); ?>
		</div>
	</div><!-- #nav-above -->
<?php } ?>