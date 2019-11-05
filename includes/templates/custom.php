<?php

  // AIRTABLE LIST TEMPLATE
  $value = $_GET['butterfly'];

  $id = $skb_butterfly["id"];
  $slug = $skb_butterfly["slug"];
  $butterfly = $skb_butterfly["fields"];
  $photos = $butterfly["Photo"];

  get_header();
?>
<div id="primary" class="content-area">
<main id="main" class="site-main">
    <div class="breadcrumbs">
        <?php gist_breadcrumb_options(); ?>
    </div>
    <article id="post-<?php $id; ?>" <?php post_class(); ?>>
        <div class="p-15">
            <header class="entry-header">
                <h1><?php echo ucwords($butterfly['Common Name']); ?></h1>
                <?php echo do_shortcode('[skb_breadcrumbs parent_title="Butterflies" parent_url="butterflies" current_title="'. $butterfly['Common Name'] .'" current_url="butterflies/?butterfly='. $id .'"]'); ?>
            </header>
            <!-- .entry-header -->

            <?php gist_post_thumbnail(); ?>

            <div class="entry-content">
                
                <?php
                echo "<div class='skb-butterfly-gallery'>";
                if( count($photos) > 1 ) {
                    foreach($photos as $photo) {
                        echo "<img src={$photo['url']} class='gallery-img'>";
                    }
                } elseif( count($photos) === 1 ) {
                    echo "<img src={$photos[0]['url']} class='gallery-img'>";
                }
                echo "</div>";

                echo "<p><em>{$butterfly['Genus']} {$butterfly['Species']}</em></p>";

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
    </main><!-- #main -->
</div><!-- #primary -->
<?php
gist_sidebar();
get_footer();