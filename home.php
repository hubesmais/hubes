<?php
get_header();
?>

<main id="blog-page" role="main" style="">
    <section class="container">
        <header class="page-header">
            <h1 class="page-title"><?php single_post_title(); ?></h1>
        </header>

        <?php
        if ( have_posts() ) :

            while ( have_posts() ) :
                the_post();
		?>
        
        <article <?php post_class('article-row d-flex'); ?>>
            <?php
            if ( has_post_thumbnail() ) :
                ?>
                <div class="post-thumbnail col">
                    <?php the_post_thumbnail(); ?>
                </div>
                <?php
            endif;
            ?>

            <div class="article-info col">
                <section class="entry-header">
                    <?php
                    ?>
                    <section class="entry-meta">
                        <span class="posted-on"><?php echo get_the_date(); ?></span>
                    </section>
                </section>
                
                <section class="entry-content">
                    <div class="entry-title">
                        <?php echo get_the_title(); ?>
                    </div>
                </section>

                <section class="entry-content">
                    <div class="entry-excerpt">
                        <?php echo get_the_excerpt(); ?>
                    </div>
                </section>

                <section class="entry-footer">
                    <a class="btn saiba-mais" href="<?php echo get_permalink(); ?>">Saiba mais</a>
                </section>
            </div>
        </article>
        <hr>


                <?php
		endwhile;

            the_posts_pagination();

        else :
            get_template_part( 'template-parts/content', 'none' );

        endif;
        ?>
    </section>
    <div class="graf-fin m-0 p-0">            
        <img src="<?php echo esc_url(get_template_directory_uri()) . '/assets/images/grafismoazulsuperior-01.svg'; ?>" alt="" style="background-color:#f7f3ea">        
    </div>
</main>

<?php
get_footer();
?>