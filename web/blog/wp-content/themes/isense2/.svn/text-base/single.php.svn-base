<?php $title = "Nothing Found"; if(have_posts()){ $title = get_the_title(); } ?>

<?php get_header(); ?>

<div id="content">
    <div id="pagetitle">
        <?php
            $type = get_post_type();
            switch($type) {
                case "isense_event":
                    echo "Event:";
                    break;
                
                case "isense_documentation":
                    echo "Guide:";
                    break;
                case "isense_faq":
                    echo "FAQ: ";
                    break;
            }
        ?>
        <?= $title ?>
    </div>
    <div id="main">
        <?php if(have_posts()) { ?>
            <?php while (have_posts()) { the_post(); ?>
                <div class="post_content" style="border-bottom:1px solid #CCC;">
                    <?php if($type == "isense_faq") { ?>
                        <div class="post_section" style="margin:0px 0px 10px 0px;">
                            <div class="post_subheader" style="font-weight:bold">Answer</div>
                            <div class="post_text">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        <div class="post_section" style="margin:0px 0px 10px 0px;">
                            <div class="post_subheader" style="font-weight:bold">Audience</div>
                            <div class="post_text">
                                <?php get_custom_field('_isense_faq_audiance'); ?>
                            </div>
                        </div>
                    <?php } else if($type == "isense_event") { ?>
                        <div class="post_section" style="margin:0px 0px 10px 0px;">
                            <div class="post_subheader" style="font-weight:bold">Description</div>
                            <div class="post_text">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        
                        <div class="post_section" style="margin:0px 0px 10px 0px;">
                            <div class="post_subheader" style="font-weight:bold">RSVP</div>
                            <div class="post_text">
                                <?php get_custom_field('_isense_event_registration'); ?>
                            </div>
                        </div>
                        
                        <div class="post_section" style="margin:0px 0px 10px 0px;">
                            <div class="post_subheader" style="font-weight:bold">Contact</div>
                            <div class="post_text">
                                <?php get_custom_field('_isense_event_contact_name'); ?><br/>
                                <?php get_custom_field('_isense_event_contact_email'); ?><br/>
                                <?php get_custom_field('_isense_event_contact_phone'); ?><br/>
                            </div>
                        </div>
                        
                         <div class="post_section" style="margin:0px 0px 10px 0px;">
                            <div class="post_subheader" style="font-weight:bold">Location</div>
                            <div class="post_text">
                                <?php get_custom_field('_isense_event_common_name'); ?><br/>
                                <?php get_custom_field('_isense_event_address_1'); ?><br/>
                                <?php get_custom_field('_isense_event_city'); ?>, <?php get_custom_field('_isense_event_state'); ?> <?php get_custom_field('_isense_event_zip'); ?><br/>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="post_section" style="margin:0px 0px 10px 0px;">
                            <div class="post_text">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="post-subcontent" style="padding-top:10px;">
                    <div><?php isense_posted_on(''); ?></div>
                    <?php $tags_list = get_the_tag_list( '', ', ' ); ?>
                    <?php if($tags_list) { ?>
                        <div><?php printf( __( '<span class="%1$s">Tagged with</span> %2$s'), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list); ?></div>
                    <?php } ?>
                    <div>
                        <?php edit_post_link(__( 'Edit'), '<span class="edit-link">', '</span>' ); ?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>