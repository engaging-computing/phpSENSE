<?php
/**
 * Template Name: One column, no sidebar
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
 
    if(have_posts()) { 
        the_post();
        $title = get_the_title();
        rewind_posts();
    }
    else {
        $title = 'Page';
    }
?>

<?php get_header(); ?>

<div id="content">
    <div id="pagetitle">
        <?= $title ?>
    </div>
    <div id="main-full">
        <div class="post_content" style="border-bottom:1px solid #CCC;">
            <?php the_content(); ?>
        </div>
        <div class="post-subcontent" style="padding-top:10px;">
            <div><?php isense_posted_on(''); ?></div>
            <div>
                <?php edit_post_link(__( 'Edit'), '<span class="edit-link">', '</span>' ); ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>