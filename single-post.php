<?php
get_header();
?>

<main id="single-post" role="main" class="container">

    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
    ?>
        <article <?php post_class('single-page-post'); ?>>
            <section class="entry-header">
            	<div>
                	<span class="posted-on"><?php echo esc_html( get_the_date() ); ?></span>
                </div>
                <div>
                <h1 class="entry-title"><?php echo esc_html( get_the_title() ); ?></h1>
                    <div class="entry-excerpt">
                        <?php echo get_the_excerpt(); ?>
                    </div>
				</div>               
            </section>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="post-thumbnail">
                    <?php the_post_thumbnail( 'full' ); // Use 'full' or other size if needed ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
                <?php
                the_content();
                ?>
            </div>
        </article>

    <?php
        endwhile;
    else :
        echo '<p>' . esc_html__( 'Sorry, no posts matched your criteria.', 'your-textdomain' ) . '</p>';
    endif;
    ?>
</main>
    <div class="graf-fin m-0 p-0">            
        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/grafismoazulsuperior-01.svg' ); ?>" alt="" style="background-color:#f7f3ea">        
    </div>


<?php
get_footer();
?>