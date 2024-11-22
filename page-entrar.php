<?php
/**
 * Template Name: Entrar
 * Template Post Type: page
 */

get_header();
?>

<main>
    <section class="container justify-content-center py-5">
        <div class="row gx-5">
            <section id="login-form" class="col-md-6 pr-5">
                <h1>Área de <span class="destaque-1">participante</span></h1>
                <p><b>Entre</b> para acessar sua área de participante, conferir suas inscrições em nossas atividades e acompanhar seu progresso aqui no Hub ES+!</p>
                <?php
                    echo do_shortcode('[RM_Login]');
                ?>
            </section>
            <section id="login-form-ilustration-field" class="col-md-6 d-flex">
                <img class="login-ilustration" src="<?php the_post_thumbnail_url(); ?>" alt="<?php echo the_title(); ?>">
            </section>
        </div>  
    </section>

</main>

<?php
get_footer();