<?php

    $tag = single_tag_title('', false);
    $title = "Tag Archives: " . $tag;
    $handle = "tag";
    
    
    if($tag == "events" || $tag == "guides" || $tag == "news" || $tag == "faqs") {
        $title = ucwords($tag);
        $handle = "custom";
    }
    

?>

<?php get_header(); ?>

<div id="content">
    <div id="pagetitle">
        <?php echo $title; ?>
	</div>
    <div id="main">
        <?php get_template_part('loop', $handle); ?>
    </div>
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>