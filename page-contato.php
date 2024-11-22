<?php
/**
 * Contato
 *
 * If the user has selected a static page for their homepage, this is the template
 * that is used to display the homepage content.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package hubes
 * @since 2024
 */

get_header();
?>

<main>
    <section class="container contact-page">
            <?php
                $ilustra = get_field('ilustra');

                if ($ilustra) {
                    echo '<img src="' . esc_url($ilustra['url']) . '" class="contact-ilustra col-8" alt="' . esc_attr($ilustra['alt']) . '">';
            }
            ?>
    	<div class="contact-form col">
        	<h1>Contato</h1>
        	<?php
            	echo do_shortcode('[RM_Form id=3]');
            ?>
        </div>
    </section>
    <img src="<?php echo esc_url(get_template_directory_uri()) . '/assets/images/grafismoazulsuperior-01.svg'; ?>" alt="" class="graf-superior">
</main>

<?php
get_footer();