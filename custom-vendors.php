<?php
/**
 * Plugin Name: Custom Vendors
 * Plugin URI: https://fernandoardila.com
 * Description: Este plugin Añade un CPT (Tipo de contenido personalizado) llamado Vendedores, y lo integra con WooCommerce permitiendo mostrar todos los venderores agregados através del CPT en un field de tipo select donde el usuario final/custumer/cliente, le asigna la venta al vendedor que elija en dicho select. De esta forma el administrador de la tienda podrá llevar un control del # de ventas realizadas por cada uno de los vendedores que añade através del CPT.
 * Version: 1.0
 * Author: Fernando Ardila
 * Author URI: https://fernandoardila.com
 */

// Asegurarse de que WordPress ha sido cargado
defined('ABSPATH') or die('Acceso denegado.');

/**
 * Registrar el tipo de contenido personalizado Vendedores.
 */
function cv_register_vendors_cpt() {
    $labels = array(
        'name'                  => _x('Vendedores', 'Post type general name', 'custom-vendors'),
        'singular_name'         => _x('Vendedor', 'Post type singular name', 'custom-vendors'),
        'menu_name'             => _x('Vendedores', 'Admin Menu text', 'custom-vendors'),
        'name_admin_bar'        => _x('Vendedor', 'Add New on Toolbar', 'custom-vendors'),
        'add_new'               => __('Añadir nuevo', 'custom-vendors'),
        'add_new_item'          => __('Añadir nuevo Vendedor', 'custom-vendors'),
        'new_item'              => __('Nuevo Vendedor', 'custom-vendors'),
        'edit_item'             => __('Editar Vendedor', 'custom-vendors'),
        'view_item'             => __('Ver Vendedor', 'custom-vendors'),
        'all_items'             => __('Todos los Vendedores', 'custom-vendors'),
        'search_items'          => __('Buscar Vendedores', 'custom-vendors'),
        'parent_item_colon'     => __('Vendedor padre:', 'custom-vendors'),
        'not_found'             => __('No se encontraron vendedores.', 'custom-vendors'),
        'not_found_in_trash'    => __('No se encontraron vendedores en la papelera.', 'custom-vendors')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'vendedor'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'author'),
        'show_in_rest'       => true
    );

    register_post_type('vendedor', $args);
}

add_action('init', 'cv_register_vendors_cpt');

/**
 * Funciones adicionales para integrar con WooCommerce y demás lógica requerida aqui
 * 
 * 
 */

 function cv_add_vendor_meta_boxes() {
    add_meta_box(
        'vendor_details',
        __('Detalles del Vendedor', 'custom-vendors'),
        'cv_vendor_details_callback',
        'vendedor',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'cv_add_vendor_meta_boxes');

function cv_vendor_details_callback($post) {
    wp_nonce_field(basename(__FILE__), 'vendor_nonce');
    $vendor_code = get_post_meta($post->ID, '_vendor_code', true);
    $vendor_address = get_post_meta($post->ID, '_vendor_address', true);

    ?>
    <p>
        <label for="vendor_code"><?php _e('Código del Vendedor', 'custom-vendors'); ?></label>
        <input type="text" id="vendor_code" name="vendor_code" value="<?php echo esc_attr($vendor_code); ?>" class="widefat">
    </p>
    <p>
        <label for="vendor_address"><?php _e('Dirección del Vendedor', 'custom-vendors'); ?></label>
        <textarea id="vendor_address" name="vendor_address" class="widefat"><?php echo esc_textarea($vendor_address); ?></textarea>
    </p>
    <?php
}

function cv_save_vendor_meta($post_id) {
    if (!isset($_POST['vendor_nonce']) || !wp_verify_nonce($_POST['vendor_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if ('vendedor' !== $_POST['post_type'] || !current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    update_post_meta($post_id, '_vendor_code', sanitize_text_field($_POST['vendor_code']));
    update_post_meta($post_id, '_vendor_address', sanitize_textarea_field($_POST['vendor_address']));
}

add_action('save_post', 'cv_save_vendor_meta');


 /**
 * Añadir un campo desplegable para seleccionar vendedor en el checkout de WooCommerce.
 */
function cv_add_vendor_field_to_checkout($checkout) {
    echo '<div id="cv_custom_checkout_field"><h2>' . __('Seleccione un Vendedor', 'custom-vendors') . '</h2>';

    $vendors = get_posts(array(
        'post_type' => 'vendedor',
        'numberposts' => -1,
        'post_status' => 'publish'
    ));

    $options = array();
    if ($vendors) {
        foreach ($vendors as $vendor) {
            $vendor_code = get_post_meta($vendor->ID, '_vendor_code', true);
            $options[$vendor->ID] = $vendor->post_title . ' (' . $vendor_code . ')';
        }
    }

    woocommerce_form_field('cv_vendor', array(
        'type'        => 'select',
        'class'       => array('cv-vendor-class form-row-wide'),
        'label'       => __('Vendedor', 'custom-vendors'),
        'options'     => $options,
        'required'    => true,
    ), $checkout->get_value('cv_vendor'));

    echo '</div>';
}

add_action('woocommerce_after_order_notes', 'cv_add_vendor_field_to_checkout');

/**
 * Validar que el campo vendedor esté seleccionado.
 */
/**
 * Validar que el campo vendedor esté seleccionado en el checkout.
 *
 * @param array $fields Array de campos para el formulario de checkout.
 * @param object $errors Objeto de errores de WC donde se pueden agregar errores de validación.
 */

/**
 * Validar que el campo vendedor esté seleccionado.
 */
function cv_validate_vendor_field() {
    if (empty($_POST['cv_vendor'])) {
        wc_add_notice(__('Por favor, seleccione un vendedor.', 'custom-vendors'), 'error');
    }
}

add_action('woocommerce_checkout_process', 'cv_validate_vendor_field');






/**
 * Guardar el valor del campo vendedor en los detalles del pedido y después Incrementar el contador de ventas de un vendedor cuando se completa un pedido..
 */
function cv_save_vendor_field($order_id) {
    if (!empty($_POST['cv_vendor'])) {
        $vendor_id = intval($_POST['cv_vendor']);
        // Asegúrate de que realmente es un ID de un vendedor válido
        if (get_post_type($vendor_id) === 'vendedor') {
            update_post_meta($order_id, '_cv_vendor_id', $vendor_id);
            error_log("Vendedor ID {$vendor_id} guardado para el pedido ID {$order_id}");

            // Incrementar el contador de ventas justo después de guardar el ID del vendedor
            $sales_count = (int) get_post_meta($vendor_id, '_cv_sales_count', true);
            update_post_meta($vendor_id, '_cv_sales_count', $sales_count + 1);
            error_log("Contador de ventas incrementado para Vendedor ID {$vendor_id}. Nuevo total: " . ($sales_count + 1));
        } else {
            error_log("Intento de guardar un ID no válido ({$vendor_id}) como vendedor en el pedido ID {$order_id}");
        }
    }
}

add_action('woocommerce_checkout_update_order_meta', 'cv_save_vendor_field', 20); // Asegúrate de que se ejecuta después de otras acciones





//agregando shorecode para mostrar los resultados de los vendedores
function cv_display_vendor_sales($atts) {
    $atts = shortcode_atts(array(
        'id' => '', // Default o sin ID
    ), $atts, 'vendor_sales');

    if (empty($atts['id'])) {
        return 'ID de vendedor no especificado.';
    }

    $sales_count = get_post_meta($atts['id'], '_cv_sales_count', true);
    return 'Ventas totales: ' . ($sales_count ? $sales_count : '0');
}

add_shortcode('vendor_sales', 'cv_display_vendor_sales');


//agregando shorecode para mostrar tabla de vendedores
function cv_list_all_vendors_sales() {
    $vendors = get_posts(array(
        'post_type' => 'vendedor',
        'numberposts' => -1,
        'post_status' => 'publish'
    ));

    if (empty($vendors)) {
        return 'No hay vendedores registrados.';
    }

    $output = '<table><thead><tr><th>Vendedor</th><th>Ventas</th></tr></thead><tbody>';

    foreach ($vendors as $vendor) {
        $sales_count = get_post_meta($vendor->ID, '_cv_sales_count', true);
        $output .= '<tr><td>' . esc_html($vendor->post_title) . '</td><td>' . esc_html($sales_count ? $sales_count : '0') . '</td></tr>';
    }

    $output .= '</tbody></table>';

    return $output;
}

add_shortcode('list_vendors_sales', 'cv_list_all_vendors_sales');

//agregando la funcionalidad de mostrar el vendedor en el pedido realizado

function cv_display_vendor_in_admin_order_meta($order) {
    $vendor_id = get_post_meta($order->get_id(), '_cv_vendor_id', true);
    if ($vendor_id) {
        $vendor_post = get_post($vendor_id);
        echo '<p><strong>' . __('Vendedor:', 'custom-vendors') . '</strong> ' . $vendor_post->post_title . '</p>';
    }
}

add_action('woocommerce_admin_order_data_after_billing_address', 'cv_display_vendor_in_admin_order_meta', 10, 1);




?>
