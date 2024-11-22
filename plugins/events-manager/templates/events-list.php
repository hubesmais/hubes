<?php
/*
 * Custom Events List Template
 * @package WordPress
 * @subpackage hubes
 * @since 1.0
 */

$args = apply_filters('em_content_events_args', $args);
if (empty($args['id'])) {
    $args['id'] = rand(100, getrandmax());
}
$id = esc_attr($args['id']);
?>

<div class="<?php em_template_classes('view-container'); ?> container px-0" id="em-view-<?php echo $id; ?>" data-view="list">
    <button onclick="window.location.href='../agenda'" class="back-ppage"></button>
    <div id="em-events-list-<?php echo $id; ?>" data-view-id="<?php echo $id; ?>">
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
                    'name' => $event_slug,
                    'post_type' => 'event',
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
                
                // Event institutional info
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

                <div class="event-item col-md-4">
                    <section class="em-event em-item event-card <?php echo $is_cancelled; ?>">
                        <section class="em-event-image <?php echo $is_bookings_closed; ?>">
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

                                            echo '<div class="ev-category parent-categories">';
                                            if ($parent_meta_value) {
                                                echo '<p style="background-color: ' . esc_attr($parent_meta_value) . ';">' . esc_html($parent_em_category->name) . '</p>';
                                            } else {
                                                echo '<p>' . esc_html($parent_em_category->name) . '</p>';
                                            }
                                            echo '</div>';
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
                                    <h3 class="em-item-title m-0"><?php echo $event_name; ?></h3>
                                    <?php if (!empty($nomes_formatados)): ?>
                                        <section class="em-event-speaker">
                                            <p>com <?php echo esc_html($nomes_formatados); ?></p>
                                        </section>
                                    <?php endif; ?>
                                    <?php if (!empty($nome_instituicao_formatado)): ?>
                                        <section class="em-event-institution">
                                            <p><?php echo esc_html($nome_instituicao_formatado); ?></p>
                                        </section>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <section class="em-item-meta-line em-event-date em-event-meta-datetime">
                                        <section class="event-date">
                                            <p>
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