<?php

/**
 * The header for your theme.
 *
 * The header template file usually contains your site’s document type, meta information, links to stylesheets and scripts, 
 * and other data.
 * @package hubes
 */
?>

<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
    <meta name="author" content="Coordenação Web do HUBES+" />
    <meta name="description" content="Plataforma do HUBES+">
    <meta property="og:image" content="https://" />
    <meta property="og:description" content="Plataforma do HUBES+" />
    <meta property="og:title" content="HUBES+" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php wp_head(); ?>

<!-- Matomo -->
<script>
  var _paq = window._paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//esmaiscriativo.es.gov.br/matomo/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->

</head>

<body <?php body_class('site-container'); ?> >

    <?php wp_body_open(); ?>

    <header>
        <nav id="navbar" class="navbar sticky-top navbar-expand-lg">
            <section class="header-top container py-2">
                <?php if (has_custom_logo()) :
                    // Get the Custom Logo URL
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
                    $desktop_logo_url = $image[0];
                ?>
                <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>" rel="home">
                    <div id="logo-container">
                        <img id="logo" class="logo" src="<?php echo esc_url($desktop_logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" />
                        <div id="logo-animation" class="logo" data-json="<?php echo esc_attr('http://esmaiscriativo.es.gov.br/hubesmais/wp-content/uploads/sites/2/2024/06/hublogolaranja-1.json'); ?>"></div>

                    </div>
                </a>
                <?php else : ?>
                    <div class="navbar-brand site-name fw-bold"><?php bloginfo('name'); ?></div>
                <?php endif; ?>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!--Navbar-->
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <!-- Chama as páginas do menu 'Header' em item-->
                    <?php
                    $args = array(
                        'theme_location' => 'header', // Replace with your menu location
                        'menu_class'     => 'navbar-nav', // Replace with your menu slug or ID
                    );
                    wp_nav_menu($args);
                    ?>
                </div>
            </section>
        </nav>
        <section class="container">
            <ul class="py-2 m-0 socials">
                <?php
                // Get the ID of the "Home" page
                $home_page_id = get_option('page_on_front');
                if (have_rows('sociais', $home_page_id)) {
                while (have_rows('sociais', $home_page_id)) : the_row();
                    $image = get_sub_field('icone');
                    $link = get_sub_field('link');
                ?>
                <li class="ms-3">
                    <a class="text-body-secondary" href="<?php echo $link; ?>" target="_blank" rel="noopener noreferrer">
                        <?php echo $image;?>
                    </a>
                </li>
                <?php
                endwhile;
                }
                ?>
            </ul>
        </section>
    </header>
