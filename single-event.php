<?php
/**
 * The template for displaying all single events
 * @package WordPress
 * @subpackage hubes
 * @since 1.0
 */

get_header();
?>

<main id="main" class="site-main single-event-page" role="main">
    <?php
    global $EM_Event;
    /* @var $EM_Event EM_Event */

    if (!empty($args['id'])) {
        $id = esc_attr($args['id']);
    } else {
        $id = rand(100, getrandmax()); // prevent warnings
    }

    // Instantiate the EM_Event object
    $EM_Event = new EM_Event($id);
    global $wpdb;
    $event_id = $EM_Event->post_id;

    // Fetch event data:
    
    //Event name
    $event_name = get_the_title();

    //Event date
    $event_date = $EM_Event->event_start_date;
    $event_end_date = $EM_Event->event_end_date;
    $event_date_formatted = '';
    $event_end_date_formatted = '';
    $event_date_obj = DateTime::createFromFormat('Y-m-d', $event_date);
    $event_end_date_obj = DateTime::createFromFormat('Y-m-d', $event_end_date);
    $event_date_formatted = $event_date_obj->format('d/m');
    $event_end_date_formatted = $event_end_date_obj->format('d/m');

    //Event text
    $event_content = $EM_Event->post_content;

    //Event times
    $event_start_time = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT event_start_time FROM wp_2_em_events WHERE post_id = %d",
            $event_id
        )
    );
    $event_end_time = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT event_end_time FROM wp_2_em_events WHERE post_id = %d",
            $event_id
        )
    );
    $start_time_formatted = '';
    $end_time_formatted = '';
    $start_time_obj = DateTime::createFromFormat('H:i:s', $event_start_time);
    $end_time_obj = DateTime::createFromFormat('H:i:s', $event_end_time);
    $start_time_formatted = $start_time_obj->format('H\hi');
    $end_time_formatted = $end_time_obj->format('H\hi');
    $event_times = $start_time_formatted . ' às ' . $end_time_formatted;

    //Event location
    $location_id = $EM_Event->location_id;
    $location_name = '';
    $location_address = '';
    $location_town = '';

    // Check if location_id is set
    if (!empty($location_id)) {
        // Query to get the location details from wp_2_em_locations
        $location_details = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT location_name, location_address, location_town FROM wp_2_em_locations WHERE location_id = %d",
                $location_id
            )
        );

        // Check if the query returned a result
        if ($location_details) {
            $location_name = $location_details->location_name;
            $location_address = $location_details->location_address;
            $location_town = $location_details->location_town;
        }
    }

    //Category mapping
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
        'Masterclass' => 33,
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
        // Fetch the external subcategory field when 'Parceria' is selected
        $selected_subcategory = get_field('field_66da3d7e50abd', $event_id); // External subcategory
        $child_em_category_id = $acf_to_em_category_map[$selected_subcategory] ?? null;
    } elseif ($category_value === 'Hub ES+') {
        // Fetch the internal subcategory field when 'Hub ES+' is selected
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

    // Event partners info
    $partners_event_id = $event->ID;
    $palestrantes = get_field('palestrante', $partners_event_id);

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
    $instituicoes = get_field('instituicao', $partners_event_id);  // Correctly fetch the field
    $nomes_instituicoes = array();
    if (is_array($instituicoes) && !empty($instituicoes)) {
        foreach ($instituicoes as $instituicao) {
            if (is_array($instituicao) && isset($instituicao['nome-instituicao'])) {
                $nome_instituicao = trim($instituicao['nome-instituicao']);
                if (!empty($nome_instituicao)) {
                    $nomes_instituicoes[] = esc_html($nome_instituicao);
                }
            }
        }
    }
    ?>


    <section class="container">
        <button onclick="window.location.href='../../agenda'" class="back-ppage"></button>
        <section class="d-flex flex-wrap">
            <section class="ev-card-1 col-md-6">
                <section class="em-item-image <?php echo has_post_thumbnail() ? '' : 'has-placeholder'; ?>">
                    <?php $trilha = get_field('trilha', $event->ID); ?>
					<?php if ($trilha && isset($parent_em_category) && $parent_em_category->name === 'Hub ES+') { ?>
                        <div class="trilha-info">
                            <p><?php echo esc_html($trilha); ?></p>
                        </div>
                    <?php } ?>
                    <?php
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('event-image');
                    } else {
                        $thumbnail_size = 'event-image';
                        $thumbnail_size_info = wp_get_additional_image_sizes()[$thumbnail_size];
                        $width = $thumbnail_size_info['width'];
                        $height = $thumbnail_size_info['height'];

                        $categories = $EM_Event->get_categories();
                        //$count_cats = count($categories->categories) > 0;
                        $one_image = false;

                        if (!empty($categories->categories)) {
                            foreach ($categories->categories as $EM_Category) {
                                if ($EM_Category->get_image_url() != '') {
                                    // Exibe a primeira imagem de categoria encontrada
                                    echo '<img src="' . esc_url($EM_Category->get_image_url()) . '" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '" alt="' . esc_attr($EM_Category->name) . '">';
                                    $one_image = true;
                                    break; // Sai do loop após exibir a primeira imagem
                                }
                            }
                        }

                        if (!$one_image) {
                            echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/placeholder-image.jpg') . '" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '" alt="Placeholder">';
                        }
                    }
                    ?>
                </section>
                <section class="ev-details">
                	<div class="d-flex justify-content-between align-items-center gap-1">
                        <section class="ev-categories">
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
                                    // Display category with background color
                                    echo '<p style="background-color: ' . esc_attr($child_meta_value) . ';">' . esc_html($child_em_category->name) . '</p>';
                                } else {
                                    // Display category without color
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
                        </section>
                        <section class="d-flex align-items-center gap-2">
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
                        </section>
                    </div>
                    <div>
                        <section class="ev-name">
                            <h1><?php echo esc_html($event_name); ?></h1>
                        </section>
                        <?php if (!empty($nomes_formatados)): ?>
                            <section class="em-event-speaker">
                                <p>com <?php echo $nomes_formatados; ?></p>
                            </section>
                        <?php endif; ?>
                    </div>
                    <div>
                        <section class="ev-date">
                            <?php
                            echo '<p>' . esc_html($event_date_formatted) . ' • ' . esc_html($event_times) . '</p>';
                            ?>
                        </section>
                    </div>
                </section>
            </section>
            <section class="ev-card-2 col-md-6">
                <section class="ev-desc">
                    <div>
                        <?php echo wp_kses_post($event_content); ?>
                    </div>

                    <?php 
                    $publico_value = get_field('field_66f5955d3acbf', $event_id); // Valor do público
                    $categoria_value = get_field('categoria', $event_id); // Valor da categoria

                    if ($categoria_value === 'Parceria') { // Verifica se a categoria é 'Parceria'
                        if ($publico_value): // Verifica se um valor de público foi selecionado ?>
                            <div class="publico-evento">
                                <h3><?php echo esc_html($publico_value); ?></h3>
                            </div>
                        <?php endif;
                    }
                    ?>
                </section>
                <hr>
                <section class="ev-loc">
                    <?php
                    echo '<p>' . esc_html($location_name) . '<br>' .
                        '<span class="ev-loc-det">' . esc_html($location_address) . '</span><br>' .
                        '<span class="ev-loc-det">' . esc_html($location_town) . '</span>' .
                        '</p>';
                    ?>
                </section>
                <section class="ev-free">
                    <p>EVENTO GRATUITO</p>
                </section>
                <?php
                global $wpdb;
                $table_name = $wpdb->prefix . 'em_events'; // Dynamically get the correct table name
                
                $sql = $wpdb->prepare(
                    "SELECT event_active_status, event_rsvp FROM {$table_name} WHERE post_id = %d",
                    $event_id
                );

                $event_data = $wpdb->get_row($sql, ARRAY_A);

                // Determine if the event is active
                $is_active = (isset($event_data['event_active_status']) && $event_data['event_active_status'] == '1'); // Use loose comparison
                $has_bookings = (isset($event_data['event_rsvp']) && $event_data['event_rsvp'] == '1'); // Use loose comparison
                
                // Fetch the ACF field value (URL field)
                $acf_registration_url = get_field('field_66f595953acc0', $event_id); // Adjust field key accordingly
                

                // Fetch all categories using previously set $parent_em_category and $child_em_category
                $categories = [$parent_em_category];
                if ($child_em_category) {
                    $categories[] = $child_em_category;
                }

                // Find the main category (top-level category)
                $main_category = null;
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        if ($category && $category->parent == 0) { // Check if the category is a top-level category
                            $main_category = $category;
                            break;
                        }
                    }
                }

                $main_category_name = $main_category ? $main_category->name : 'no-category';

                // Sanitize the category name to use as a class
                $sanitized_class = sanitize_title($main_category_name);
                ?>

                <section class="ev-form <?php echo esc_attr($sanitized_class); ?>">
                    <?php
                    // Section content
                    if ($is_active) {
                        if ($has_bookings || !empty($acf_registration_url)) {
                            // Use the ACF URL if available, otherwise, use a default URL
                            $registration_url = !empty($acf_registration_url) ? esc_url($acf_registration_url) : 'https://forms.gle/E9utwJ1rTLkXdWC59';
                            echo '<a href="' . $registration_url . '" class="btn btn-primary" target="_blank">Inscreva-se!</a>';
                        }
                    }
                    // Check conditions for the cancelled message
                    if (!$has_bookings && $sanitized_class === 'parceria') {
                        // Do not show cancelled message if it's a "parceria" and no bookings
                    } elseif (!$is_active) {
                        echo '<div class="em-event-cancelled"><p>Essa atividade foi cancelada.</p></div>';
                    }
                    ?>
                </section>

            </section>
            <section class="fac-list">
                <?php foreach ($palestrantes as $index => $palestrante): ?>
                    <?php if (is_array($palestrante)): ?>
                        <?php
                        $foto_palestrante = isset($palestrante['foto_palestrante']) ? $palestrante['foto_palestrante'] : array();
                        $nome_palestrante = isset($palestrante['nome_palestrante']) ? $palestrante['nome_palestrante'] : 'Nome não informado';
                        $minibio = isset($palestrante['minibio']) ? $palestrante['minibio'] : 'Minibio não informada';
                        $minibio_instituicao = isset($instituicao['minibio-instituicao']) ? $instituicao['minibio-instituicao'] : 'Minibio não informada';
                        ?>
                        <section class="ev-fac-card">
                            <section class="ev-fac-photo p-0">
                                <?php if (!empty($foto_palestrante)): ?>
                                    <img alt="<?= esc_attr($foto_palestrante['alt']); ?>"
                                        src="<?= esc_url($foto_palestrante['url']); ?>" class="img-fluid" />
                                <?php endif; ?>
                            </section>
                            <section class="ev-fac-bio">
                                <?php if (!empty($nome_palestrante)): ?>
                                    <h2><?= esc_html($nome_palestrante); ?></h2>
                                <?php endif;
                                if (!empty($minibio)): ?>
                                    <p><?= esc_html($minibio); ?></p>
                                <?php endif; ?>
                            </section>
                        </section>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Display the institution info if available -->
                <?php if (!empty($nomes_instituicoes)): ?>
                    <section class="instituicao-card">
                        <?php if (!empty($nome_instituicao)): ?>
                            <h2><?= esc_html($nome_instituicao); ?></h2>
                        <?php endif;
                        if (!empty($minibio_instituicao)): ?>
                            <p><?= esc_html($minibio_instituicao); ?></p>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>
            </section>
        </section>
    </section>
    <div class="graf-fin m-0 p-0">
        <img src="<?php echo esc_url(get_template_directory_uri()) . '/assets/images/grafismoazulsuperior-01.svg'; ?>"
            alt="">
    </div>
</main>
<?php get_footer(); ?>