<?php 
    $title = 'Nothing Found';
    if(have_posts()) {
        $title = 'Search Results for ' . get_search_query();
    }
?>

<?get_header(); ?>

<div id="content">
    <div id="pagetitle">
        <?= $title ?>
    </div>
    <div id="main">
        <?php
            if(have_posts()) {
                get_template_part('loop', 'search');
            }
            else {
                _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.');
            }
        ?>
    </div>
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>