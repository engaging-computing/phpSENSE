<?php
    $title = 'Blog Archives';
    
    if(have_posts()) { 
        the_post();
        
        if(is_day()) {
            $title = "Daily Archives: " . get_the_date();
        }
        elseif(is_month()) {
            $title = "Monthly Archives: " . get_the_date('F Y');
        }
        elseif(is_year()) {
            $title = "Yearly Archives: " . get_the_date('Y');
        }
        
        rewind_posts();
    }
?>

<?php get_header(); ?>

<div id="content">
    <div id="pagetitle">
        <?= $title ?>
	</div>
    <div id="main">
        <?php get_template_part('loop', 'archive'); ?>
    </div>
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>