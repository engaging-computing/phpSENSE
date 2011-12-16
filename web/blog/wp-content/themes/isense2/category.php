<?php $title = 'Category Archives: ' . single_cat_title('', false); ?>
<?php get_header(); ?>

<div id="content">
    <div id="pagetitle">
        <?= $title ?>
	</div>
    <div id="main">
        <?php get_template_part('loop', 'category'); ?>
    </div>
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>