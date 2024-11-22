<?php
/**
 * Front Page template
 *
 * If the user has selected a static page for their homepage, this is the template
 * that is used to display the homepage content.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package hubes
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();
?>

<main role="main" data-bs-spy="scroll" data-bs-target="#navbar" data-bs-smooth-scroll="true">
    <!-- Hero -->
    <section id="banner">
       <!-- <?php if ( has_post_thumbnail() ) : ?>
            <?php
            $image_id = get_post_thumbnail_id();
            $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
            $image_url = wp_get_attachment_image_src( $image_id, 'full' )[0]; 
            ?>-->

            <svg class="animate-svg-left position-absolute gb-1 d-none d-md-block" viewBox="0 -65 300 716.53">
            	<path class="cls-1" d="M103.07,639.36V136.58C103.07,89.98,56.92.5,0,.5h0"/>
            </svg>            
            <svg class="animate-svg-left position-absolute gb-2 d-none d-md-block z-1" viewBox="10 10 1700 170">
                <path class="cls-1" d="M1681.6,0v74.96c0,18.88-15.3,34.18-34.18,34.18H0"/>
            </svg>
            <svg class="animate-svg-right position-absolute gb-3 d-none d-md-block z-2" viewBox="-50 0 738.99 716.53">
            	<path class="cls-1" d="M614.29,0v131.47c0,73.61-59.67,133.28-133.28,133.28H151.14C67.94,264.74.5,332.19.5,415.38v301.16"/>
            </svg>           
            <svg class="animate-svg-right position-absolute gb-4 d-none d-md-block z-3" viewBox="0 0 303.64 355.34">
                <path class="cls-1" d="M303.64,65h-83.51c-85.68,0-155.13,69.46-155.13,155.13v135.2"/>
            </svg>
            <svg class="animate-svg-right position-absolute gb-5 d-none d-md-block z-0" viewBox="-10 10 274 700">
                <path class="cls-1" d="M204.87,0v517.37c0,18.5-15,33.51-33.51,33.51H34.29c-18.66,0-33.79,15.13-33.79,33.79v131.88"/>
            </svg>
            
            <section class="featured-image-container container">
                <section class="main-text z-3">
                    <?php
                    $homepage_id = get_option('page_on_front');
                    $homepage_content = get_post_field('post_content', $homepage_id);
                    echo apply_filters('the_content', $homepage_content);
                    ?>
                    <a href="#participe" class="btn btn-primary">Participe!</a>
                </section>
            </section>
        <?php endif; ?>
    </section>

    <!-- Sobre Section -->
    <section id="sobre" class="container">
        <section class="d-flex flex-row align-items-center">
            <div class="px-0">
                <?php
                $home_page_id = get_option('page_on_front');
                $home_section_one_text = get_field('home_section_one_text', $home_page_id);
                    if ($home_section_one_text) {
                        echo apply_filters('the_content', $home_section_one_text);
                    }
                ?>
            </div>
            <div class="px-0">
                <?php
                    $home_section_one_image = get_field('home_section_one_image', $home_page_id);
                    if($home_section_one_image): ?>
                        <div class="home-section-one-image">
                            <img src="<?php echo esc_url($home_section_one_image['url']); ?>" alt="<?php echo esc_attr($home_section_one_image['alt']); ?>" style="max-width: 100%; height: auto;">
                        </div>
                    <?php endif; 
                ?>
            </div>
        </section>
    </section>

    <!-- Participe Section -->
    <section id="participe" class="container my-5 p-0 d-flex">
        <section class="participe-container row justify-content-between">
            <?php
                if (have_rows('participe-cards', $home_page_id)) {
                    while (have_rows('participe-cards')) : the_row();
                        $image = get_sub_field('image');
                        $legenda = get_sub_field('legenda');
                        $descricao = get_sub_field('descricao');
                        $botao = get_sub_field('botao');
                        $link_botao = get_sub_field('link-botao');
						$link = get_sub_field('link');
                        $image_wrapper = get_sub_field_object('image')['wrapper'];
                        $image_class = isset($image_wrapper['class']) ? esc_attr($image_wrapper['class']) : '';
            ?>
            <section class="participe-card p-0 m-0">
                <img src="<?php echo $image['url']; ?>" class="card-img" alt="<?php echo $image['alt']; ?>">
                <section class="card-body d-flex align-items-end p-2">
                    <section class="card-content p-4">
                        <div class="legenda">
                            <?php echo $legenda; ?>
                        </div>
                        <div class="descricao py-3">
                            <?php echo $descricao; ?>
                        </div>
                        <div class="botao">
                            <a role="button" href="<?php echo $link; ?>" target="_blank" class="btn btn-primary"><?php echo $botao; ?></a>
                        </div>
                    </section>
                </section>
            </section>
            <?php
                endwhile;
            }
            ?>
        </section>
    </section>

    <!-- Agenda Section -->
    <section id="agenda" class="d-flex">
        <section class="container">
        	<h1 class="mt-5 text-center">Agenda de <span class="destaque">Eventos</span></h1>
            <section id="carrossel-agenda">
                <?php
                $args = apply_filters('em_content_events_args', $args);
                if (empty($args['id'])) $args['id'] = rand(100, getrandmax());
                $id = esc_attr($args['id']);
                ?>

                <div class="<?php em_template_classes('view-container'); ?>" id="em-view-<?php echo $id; ?>" data-view="list">
                    <div class="events-slider <?php em_template_classes('event-slider'); ?>" id="em-events-list-<?php echo $id; ?>" data-view-id="<?php echo $id; ?>">
                        <?php

                        // Fetch events
                        $events = EM_Events::get($args);

                        if ($events) {
                            foreach ($events as $event) {

                                // Event infos
                                $event_name = esc_html($event->event_name);
                                $event_start_date = esc_html(date('d/m', strtotime($event->event_start_date)));
                                $event_location = esc_html($event->location);

                                // Event times
                                $event_start_time = $event->event_start_time;
                                $formatted_time = !empty($event_start_time) ? date('H\hi', strtotime($event_start_time)) : 'Horário não disponível';                

                                // Event images
                                $event_image = get_the_post_thumbnail_url($event->ID, 'event-image');

                                // Event url
                                $event_slug = $event->event_slug; 
                                $event_query = new WP_Query(array(
                                    'name'        => $event_slug,
                                    'post_type'   => 'event', 
                                    'post_status' => 'publish',
                                    'posts_per_page' => 1
                                ));

                                if ($event_query->have_posts()) {
                                    $event_query->the_post();
                                    $event_id = get_the_ID();
                                    $event_url = get_permalink($event_id);
                                } else {
                                    $event_url = '#'; 
                                }
                                wp_reset_postdata();

                                $is_cancelled = $event->is_cancelled ? 'em-event-cancelled' : '';
                                $is_bookings_closed = $event->bookings_closed ? 'esgotado' : '';

                                // Event partners info
                                $event_id = $event->ID;
                                $palestrantes = get_field('palestrante', $event_id);
                                $nomes_palestrantes = array();

                                if (is_array($palestrantes) && !empty($palestrantes)) {
                                    foreach ($palestrantes as $palestrante) {
                                        if (is_array($palestrante) && isset($palestrante['nome_palestrante'])) {
                                            $nome_palestrante = trim($palestrante['nome_palestrante']);
                                            if (!empty($nome_palestrante)) {
                                                $nomes_palestrantes[] = esc_html($nome_palestrante);
                                            }
                                        }
                                    }
                                }

                                // Formatting
                                $total_palestrantes = count($nomes_palestrantes);
                                if ($total_palestrantes > 1) {
                                    $ultimo_nome = array_pop($nomes_palestrantes);
                                    $nomes_formatados = implode(', ', $nomes_palestrantes) . ' e ' . $ultimo_nome;
                                } elseif ($total_palestrantes === 1) {
                                    $nomes_formatados = $nomes_palestrantes[0];
                                } else {
                                    $nomes_formatados = '';
                                }

                                // Event institucional info
                                $nome_instituicao = get_field('nome-instituicao', $event_id);
                                $nome_instituicao_formatado = esc_html(trim($nome_instituicao));

                                // Category mapping
                                $acf_to_em_category_map = [
                                'Hub ES+' => 30,
                                'Es+ Café' => 32,
                                'Apresentação cultural' => 39,
                                'Cerimônia' => 40,
                                'Conversa Criativa' => 34,
                                'Conversa Inovadora' => 35,
                                'Exposição' => 42,
                                'Feira' => 56,
                                'Maratona Criativa' => 37,
                                'Maratona Inovadora' => 38,
                                'Oficina Maker' => 36,
                                'Painel' => 44,
                                'Recepção' => 43,
                                'Reunião IBCA' => 41,
                                'Outros' => 45,
                                'Parceria' => 31,
                                'Apresentação cultural' => 47,
                                'Cerimônia' => 48,
                                'Encontro' => 52,
                                'Exposição' => 50,
                                'Feira' => 54,
                                'Formação' => 46,
                                'Lançamento' => 53,
                                'Recepção' => 51,
                                'Reunião' => 49,
                                'Outros' => 55,
                                ];

                                $category_value = get_field('categoria', $event_id); // Parent category
                                $parent_em_category_id = $acf_to_em_category_map[$category_value] ?? null;

                                $selected_subcategory = ''; 
                                $child_em_category_id = null;

                                if ($category_value === 'Parceria') {
                                    $selected_subcategory = get_field('field_66da3d7e50abd', $event_id); // External subcategory
                                    $child_em_category_id = $acf_to_em_category_map[$selected_subcategory] ?? null;
                                } elseif ($category_value === 'Hub ES+') {
                                    $selected_subcategory = get_field('field_66da3cf359ad4', $event_id); // Internal subcategory
                                    $child_em_category_id = $acf_to_em_category_map[$selected_subcategory] ?? null;
                                     // Check if the "ES+ Café" checkbox is selected
                    $es_cafe_checkbox = get_field('field_66da3dc850abe', $event_id);
                    if (is_array($es_cafe_checkbox) && in_array('ES+ Café', $es_cafe_checkbox)) {
                        // Display "ES+ Café" as a child category
                        $es_cafe_category_id = $acf_to_em_category_map['Es+ Café'] ?? null;
                        $es_cafe_category = $es_cafe_category_id ? get_term_by('id', $es_cafe_category_id, 'event-categories') : null;
                    }
                }
                $parent_em_category = $parent_em_category_id ? get_term_by('id', $parent_em_category_id, 'event-categories') : null;
                $child_em_category = $child_em_category_id ? get_term_by('id', $child_em_category_id, 'event-categories') : null;
                ?>

                <div class="event-item">
                    <section class="em-event em-item event-card <?php echo $is_cancelled; ?>">
                        <section class="em-event-image <?php echo $is_bookings_closed; ?>">
                            <?php $trilha = get_field('trilha', $event->ID); ?>
							<?php if ($trilha && isset($parent_em_category) && $parent_em_category->name === 'Hub ES+') { ?>
                            <div class="trilha-info">
                            	<p><?php echo esc_html($trilha); ?></p>
                            </div>
                            <?php } ?>
                            <img src="<?php echo esc_url($event_image ? $event_image : $event->category_image_url); ?>" alt="<?php echo $event_name; ?>">
                            <?php if ($event->bookings_closed) { ?>
                                <div class="vagas-esgotadas">
                                    <p>Vagas preenchidas</p>
                                </div>
                            <?php } ?>
                        </section>
                        <section class="em-event-meta em-item-meta">
                            <section class="event-details container">
                                <div>                                
                                	<div class="d-flex justify-content-between align-items-center gap-1">
                                        <div class="carousel-categories ev-categories">
                                            <?php
                                            // Output Parent Category with ACF and Events Manager integration
                                            if ($parent_em_category) {
                                                global $wpdb;
                                                $meta_key = 'category-bgcolor'; // Ensure this is the correct meta key
                                                $parent_meta_value = $wpdb->get_var($wpdb->prepare(
                                                    "SELECT meta_value FROM wp_2_em_meta WHERE meta_key = %s AND object_id = %d",
                                                    $meta_key,
                                                    $parent_em_category->term_id
                                                ));
                                                if ($parent_em_category->name === 'Parceria') {
                                                    echo '<div class="ev-category parent-categories">';
                                                    if ($parent_meta_value) {
                                                        echo '<p style="background-color: ' . esc_attr($parent_meta_value) . ';">' . esc_html($parent_em_category->name) . '</p>';
                                                    } else {
                                                        echo '<p>' . esc_html($parent_em_category->name) . '</p>';
                                                    }
                                                    echo '</div>';
                                                }
                                            }
                                            // Output Child Category with ACF and Events Manager integration
                                            if ($child_em_category) {
                                                global $wpdb;
                                                $child_meta_value = $wpdb->get_var($wpdb->prepare(
                                                    "SELECT meta_value FROM wp_2_em_meta WHERE meta_key = %s AND object_id = %d",
                                                    $meta_key,
                                                    $child_em_category->term_id
                                                ));
                                                echo '<div class="ev-category child-categories">';
                                                if ($child_meta_value) {
                                                    echo '<p style="background-color: ' . esc_attr($child_meta_value) . ';">' . esc_html($child_em_category->name) . '</p>';
                                                } else {
                                                    echo '<p>' . esc_html($child_em_category->name) . '</p>';
                                                }
                                                // Output the "ES+ Café" category if selected
                                                $selected_fair = get_field('field_66da3dc850abe', $event_id); // Checkbox field for "ES+ Café"
                                                if ($category_value === 'Hub ES+' && is_array($selected_fair) && in_array('ES+ Café', $selected_fair)) {
                                                    $es_cafe_em_category_id = 32; // The ID for the "ES+ Café" child category
                                                    $es_cafe_category = get_term_by('id', $es_cafe_em_category_id, 'event-categories');

                                                    if ($es_cafe_category) {
                                                        $es_cafe_meta_value = $wpdb->get_var($wpdb->prepare(
                                                            "SELECT meta_value FROM wp_2_em_meta WHERE meta_key = %s AND object_id = %d",
                                                            $meta_key,
                                                            $es_cafe_category->term_id
                                                        ));

                                                        if ($es_cafe_meta_value) {
                                                            echo '<p style="background-color: ' . esc_attr($es_cafe_meta_value) . ';">' . esc_html($es_cafe_category->name) . '</p>';
                                                        } else {
                                                            echo '<p>' . esc_html($es_cafe_category->name) . '</p>';
                                                        }
                                                    }
                                                }
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
                                        <div  class="d-flex align-items-center gap-2">
                                            <?php
                                            if ($parent_em_category->name === 'Hub ES+') {
                                            //Maturidade
                                            $nivel_value = get_field('nivel', $event_id);
                                            $icon = '';
                                            $number = '';

                                            switch ($nivel_value) {
                                                case 'Pré-incubação':
                                                case 'Pré-aceleração':
                                                    $number = '<div class="icon-ped icon-nivel-1"><p>1</p></div>';
                                                    break;
                                                case 'Incubação':
                                                case 'Aceleração':
                                                case 'Tração':
                                                    $number = '<div class="icon-ped icon-nivel-2"><p>2</p></div>';
                                                    break;
                                                case 'Consolidação':
                                                    $number = '<div class="icon-ped icon-nivel-3"><p>3</p></div>';
                                                    break;
                                            }

                                            if ($number) {
                                                echo $number; // Exibe o número
                                            } elseif ($icon) {
                                                echo $icon; // Exibe o ícone se aplicável
                                            }

                                            //MMF
                                            $mmf_value = get_field('mmf', $event_id);
                                            if (!empty($mmf_value)) {
                                                $icon = '';

                                                switch ($mmf_value) {
                                                    case 'Mentalidade':
                                                        $icon = '<div class="icon-ped icon-mmf"><i class="bi bi-lightbulb-fill"></i></div>';
                                                        break;
                                                    case 'Método':
                                                        $icon = '<div class="icon-ped icon-mmf"><i class="bi bi-list-check"></i></div>';
                                                        break;
                                                    case 'Ferramenta':
                                                        $icon = '<div class="icon-ped icon-mmf"><i class="bi bi-tools"></i></div>';
                                                        break;
                                                }

                                                if ($icon) {
                                                    echo $icon;
                                                }
                                            }

                                            $nivel_value = get_field('nivel', $event_id);

                                            $icon = '';

                                            //Estagios
                                            switch ($nivel_value) {
                                                case 'Pré-incubação':
                                                case 'Incubação':
                                                        $icon = '<div class="icon-ped icon-stage"><i class="bi bi-triangle-fill"></i></div>';
                                                    break;
                                                case 'Pré-aceleração':
                                                case 'Aceleração':
                                                        $icon = '<div class="icon-ped icon-stage"><i class="bi bi-square-fill"></i></div>';
                                                    break;
                                                case 'Tração':
                                                case 'Consolidação':
                                                        $icon = '<div class="icon-ped icon-stage"><i class="bi bi-pentagon-fill"></i></div>';
                                                    break;
                                            }

                                            if ($icon) {
                                                    echo $icon;
                                            }}
                                            ?>
                                        </div>
                                    </div>    
                                    <h3 class="em-item-title m-0"><?php echo $event_name; ?></h3>
                                    <?php if (!empty($nomes_formatados)): ?>
                                        <section class="em-event-speaker">
                                            <p>com <?php echo $nomes_formatados; ?></p>
                                        </section>
                                    <?php endif; ?>
                                    <?php if (!empty($nome_instituicao_formatado)): ?>
                                        <section class="em-event-institution">
                                            <p><?php echo $nome_instituicao_formatado; ?></p>
                                        </section>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <section class="em-item-meta-line em-event-date em-event-meta-datetime">
                                        <section class="event-date">
                                            <p>
                                                <span class="em-icon-calendar em-icon"></span>
                                                <?php echo $event_start_date; ?>
                                                <span class="detail">•</span>
                                                <?php echo $formatted_time; ?>
                                            </p>
                                        </section>
                                    </section>
                                    <section class="em-event-info d-flex flex-wrap align-items-center justify-content-between">
                                        <p class="em-event-free">EVENTO GRATUITO</p>
                                        <a role="button" class="text-center saiba-mais-button em-item-read-more button" href="<?php echo $event_url; ?>">Saiba mais</a>
                                    </section>
                                </div>
                            </section>
                        </section>
                    </section>
                </div>
                <?php
            }
        } else {
            echo '<p>Não há eventos disponíveis no momento.</p>';
        }
        ?>
    </div>
</div>

			</section>
            <div class="text-center">
                	<a href="http://esmaiscriativo.es.gov.br/hubesmais/agenda/" class="botao-agenda btn btn-primary" role="button"> Confira a agenda completa </a>	
            </div>
        </section>
    </section>
    <img src="http://esmaiscriativo.es.gov.br/hubesmais/wp-content/themes/hubes/assets/images/grafismoazulsuperior-01.svg" alt="" class="graf-superior" style="background-color:#272727;">

<?php
get_footer();
?>