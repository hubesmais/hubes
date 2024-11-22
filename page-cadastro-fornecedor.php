<?php
/**
 * Template Name: Entrar
 * Template Post Type: page
 */

get_header();
?>

<main>
    <section class="container">
        <section class="d-flex justify-content-center">
            <section class="signup-form">
                        <?php
                            echo do_shortcode('[RM_Form id=11]');
                        ?>
            </section>
        </section>
    </section>
</main>

<?php
get_footer();
