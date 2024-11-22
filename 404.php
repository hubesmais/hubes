<?php
/**
 * Error 404 template
 *
 * If the user has selected a static page for their homepage, this is the template
 * that is used to display the homepage content.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package hubes
 * @subpackage 
 * @since 
 */

get_header();
?>

<main>
	<section class="error-bg">
		<img src="wp-content\themes\hubes\assets\images\ilustra-404.svg">
    	<section class="container
        error-404 error-text">
            <h1>404</h1>
                <p>
                	<span class="tex-1">Ooops...</span><br>
                	<span class="tex-2">Lamentamos, mas algo deu errado.</span>
                </p>
            <a role="button" href="/hubesmais" class="btn back-btn">Voltar</a>
        </section>

    </section>
	<img src="<?php echo esc_url(get_template_directory_uri()) . '/assets/images/grafismoazulsuperior-01.svg'; ?>" alt="" class="graf-superior" style="background-color: #d6d4cd">
</main>

<?php
get_footer();
?>