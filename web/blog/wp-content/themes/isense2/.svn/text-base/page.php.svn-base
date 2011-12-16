<?php
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
    <div id="main">
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
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>