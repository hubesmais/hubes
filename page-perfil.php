<?php
/**
* Template Name: Perfil
*
* @package hubes
* @subpackage Twenty_Fourteen
* @since Twenty Fourteen 1.0
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