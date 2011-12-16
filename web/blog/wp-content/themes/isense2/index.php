<?php get_header(); ?>

<div id="content">
    <div id="pagetitle">iSENSE Blog</div>
    <div id="main">
        <?php get_template_part('loop', 'index'); ?>
    </div>
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>