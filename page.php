<?php
/**
 * Template Name: Default Page Template
 * Template Post Type: page
 *
 * This is the template that displays all pages by default.
 */

get_header();
?>

<main>
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
        the_content();
        endwhile;
    endif;
    ?>
</main>

<?php
get_footer();