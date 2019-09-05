<?php
	// AIRTABLE LIST TEMPLATE
    
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="p-15">
        <header class="entry-header">
        </header>
        <!-- .entry-header -->

        <?php gist_post_thumbnail(); ?>

        <div class="entry-content">
            <?php
            the_content();

            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'gist'),
                'after' => '</div>',
            ));
            ?>
        </div>
        <!-- .entry-content -->

        <?php if (get_edit_post_link()) : ?>
            <footer class="entry-footer">
                <?php
                edit_post_link(
                    sprintf(
                        wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                            __('Edit <span class="screen-reader-text">%s</span>', 'gist'),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        get_the_title()
                    ),
                    '<span class="edit-link">',
                    '</span>'
                );
                ?>
            </footer><!-- .entry-footer -->
        <?php endif; ?>
    </div>
    <!-- .p-15 -->
</article><!-- #post-<?php the_ID(); ?> -->