<?php 
    if(have_posts()) { 
        the_post(); 
        $title = "Author Archives: " . get_the_author();
        rewind_posts();
    } else { 
        $title = 'Author Archives';
    }
?>

<?php get_header(); ?>

<div id="content">
    <div id="pagetitle">
        <?= $title ?>
	</div>
    <div id="main">
        <?php get_template_part('loop', 'author'); ?>
    </div>
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>