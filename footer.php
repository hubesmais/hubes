            <!-- Back to top button -->
            <button id="button"></button>
            <footer>
                <section class="container-fluid pt-2" id="contact-area">
                    <section class="container position-relative mt-5">
                        <img src="<?php echo esc_url(get_template_directory_uri()) . '/assets/images/grafismo02.svg'; ?>" class="position-absolute graf-b z-0" alt="">
                        <section class="contact-row row position-relative z-1">
                            <section class="col">
                                <section class="d-flex flex-column">
                                    <!--Logo-->
                                        <?php
                                            $args = array(
                                                'post_type' => 'page',
                                                'post_status' => 'publish',
                                                'posts_per_page' => 1,
                                                'title' => 'Footer'
                                            );
                                            
                                            $query = new WP_Query($args);
                                            
                                            if ($query->have_posts()) {
                                                $query->the_post();
                                                $footer_page_id = get_the_ID();
                                                // Obtém a imagem destacada
                                                if (has_post_thumbnail($footer_page_id)) {
                                                    $logo_id = get_post_thumbnail_id($footer_page_id);
                                                    $logo_url = wp_get_attachment_image_src($logo_id, 'full')[0];
                                                } else {
                                                    // Set a default logo URL if no featured image is found
                                                    $logo_url = '/assets/images/Footer_logo.png';
                                                }
                                                wp_reset_postdata();
                                            } else {
                                                // Handle case where footer page is not found
                                                $footer_page_id = 0;
                                                // Set a default logo URL if the footer page is not found
                                                $logo_url = '/assets/images/Footer_logo.png';
                                            }
                                            ?>
                                        <img class="logo py-3" src="<?php echo esc_url($logo_url); ?>" alt="Footer Logo">
									
                                        <!--Funcionamento-->
                                    <ul class="funcionamento list-unstyled z-1">
                                        <?php
                                        $home_page_id = get_option('page_on_front');
                                        if (have_rows('funcionamento', $home_page_id)) {
                                            while (have_rows('funcionamento', $home_page_id)) : the_row();
                                                $image = get_sub_field('icone');
                                                $info = get_sub_field('info');
                                                ?>
                                                <li class="d-flex align-items-center mb-2">
                                                    <?php echo $image; ?>
                                                    <p class="text-body-secondary mb-0 ms-2"><?php echo $info; ?></p>
                                                </li>
                                            <?php
                                            endwhile;
                                        }
                                        ?>
                                    </ul>
                                    <!--Sociais-->
                                    <ul class="socials-footer list-inline my-5 z-1">
                                        <?php
                                            $home_page_id = get_option('page_on_front');
                                            if (have_rows('sociais', $home_page_id)) {
                                                while (have_rows('sociais', $home_page_id)) : the_row();
                                                    $image = get_sub_field('icone');
                                                    $link = get_sub_field('link');
                                                ?>
                                                    <li class="list-inline-item me-3">
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
                            </section>
                            <!--Formulário Newsletter-->
                            <section class="col d-flex">
                            <?php
                                $args = array(
                                    'post_type' => 'page',
                                    'post_status' => 'publish',
                                    'posts_per_page' => 1,
                                    'title' => 'Footer'
                                );
                                
                                $query = new WP_Query($args);
                                
                                if ($query->have_posts()) {
                                    $query->the_post();
                                    $footer_page_id = get_the_ID();
                                    $footer_ilustra = get_field('footer-ilustra', $footer_page_id);
                                    wp_reset_postdata();
                                        if($footer_ilustra){
                                            ?>
                                        <img class="ilustra" src="<?php echo esc_url($footer_ilustra); ?>" alt="Ilustração Footer">
                                    <?php
                                        }
                                } 
                                ?>
                                <section class="news-form position-absolute z-1">
                                    <?php
                                    echo do_shortcode('[RM_Form id="5"]');
                                    ?>
                                </section>
                            </section>
                        </section>
                    </section>
                </section>
                <!--Menu Footer-->
                <section class="container ps-0 py-3 z-1 position-relative" id="footer-menu">
                    <?php
                        $args = array(
                            'theme_location' => 'footer', // Replace with your menu location
                            'menu_class'     => 'menu', // Replace with your menu slug or ID
                        );
                        wp_nav_menu($args);
                    ?>
                </section>
                <!--Régua de logos-->
                <section class="container-fluid position-relative regua-container w-100 overflow-hidden">
                    <section class="container py-5 position-relative z-1">
                        <section class="py-2 row" id="regua">
                            <p class="col-2">Realização:</p> <span class="col-10 line"></span>
                            <section class="row-cols d-flex flex-wrap align-items-center justify-content-between regua-col">
                                <?php
                                $home_page_id = get_option('page_on_front');
                                if (have_rows('regua', $home_page_id)) :
                                    while (have_rows('regua', $home_page_id)) : the_row();
                                        $logo = get_sub_field('logo');
                                        $link = get_sub_field('link');

                                        if (is_array($logo) && isset($logo['url'])) :
                                            $logo_url = $logo['url'];
                                ?>
                                            <a href="<?php echo esc_url($link); ?>" target="_blank">
                                                <img class="logo-footer" src="<?php echo esc_url($logo_url); ?>" alt="Logo realizador">
                                            </a>
                                <?php
                                        endif;
                                    endwhile;
                                endif;
                                ?>
                            </section>
                        </section>
                    </section>
                    <img src="<?php echo esc_url(get_template_directory_uri()) . '/assets/images/grafismo03.svg'; ?>" class="grafismo-final position-absolute" alt="">
                </section>
            </footer>
            <?php 
                wp_footer();
            ?>
<script src="https://cdn.userway.org/widget.js" data-account="gujbZ3fnVA"></script>
        </body>
    </html>