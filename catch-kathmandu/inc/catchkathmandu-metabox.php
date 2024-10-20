<?php
/**
 * Catch Kathmandu Custom meta box
 *
 * @package Catch Themes
 * @subpackage Catch Kathmandu
 * @since Catch Kathmandu 1.0
 */

 // Add the Meta Box
function catchkathmandu_add_custom_box() {
    add_meta_box(
        'catch-kathmandu-option',                                   //Unique ID
        esc_html__( 'Catch Kathmandu Options', 'catch-kathmandu' ), //Title
        'catchkathmandu_metabox_show',                  //Callback function
        array( 'page', 'post' ),                                            //show metabox in page
        'side'                                          //show metabox in pages
    );
}
add_action( 'add_meta_boxes', 'catchkathmandu_add_custom_box' );

/**
 * @renders metabox to for sidebar layout
 */
function catchkathmandu_metabox_show() {
    global $post;

    $options = array(
        'catchkathmandu-header-image' => array(
            'label'   => esc_html__( 'Header Featured Image', 'catch-kathmandu'),
            'options' => array(
                'default' => esc_html__( 'Default', 'catch-kathmandu' ),
                'enable'  => esc_html__( 'Enable', 'catch-kathmandu' ),
                'disable' => esc_html__( 'Disable', 'catch-kathmandu' )
            ),
        ),
        'catchkathmandu-sidebarlayout' => array(
            'label'   => esc_html__( 'Sidabar Layout', 'catch-kathmandu'),
            'options' => array(
                'default'               => esc_html__( 'Default Layout Set in Customize Options', 'catch-kathmandu' ),
                'right-sidebar'         => esc_html__( 'Right sidebar', 'catch-kathmandu' ),
                'left-sidebar'          => esc_html__( 'Left sidebar', 'catch-kathmandu' ),
                'no-sidebar'            => esc_html__( 'No sidebar', 'catch-kathmandu' ),
            ),
        ),
        'catchkathmandu-featured-image' => array(
            'label'   => esc_html__( 'Single Page/Post Image Layout', 'catch-kathmandu'),
            'options' => array(
                'default'  => esc_html__( 'Default', 'catch-kathmandu' ),
                'featured' => esc_html__( 'Featured Image Size (750x470)', 'catch-kathmandu' ),
                'full'     => esc_html__( 'Full size', 'catch-kathmandu' ),
                'slider'   => esc_html__( 'Slider', 'catch-kathmandu' ),
                'disabled' => esc_html__( 'Disabled', 'catch-kathmandu' ),
            ),
        ),
    );

    // Use nonce for verification
    wp_nonce_field( basename( __FILE__ ), 'custom_meta_box_nonce' );

    foreach ( $options as $key => $value ) : ?>
        <p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="<?php echo esc_attr( $key ); ?>"><?php echo $value['label']; ?></label></p>
        <select class="widefat" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>">
             <?php
                $meta_value = get_post_meta( $post->ID, $key, true );
                
                if ( empty( $meta_value ) ){
                    $meta_value = 'default';
                }
                
                foreach ( $value['options'] as $field => $label ) {  
                ?>
                    <option value="<?php echo esc_attr( $field ); ?>" <?php selected( $meta_value, $field ); ?>><?php echo esc_html( $label ); ?></option>
                <?php
                } // end foreach
            ?>
        </select>
    <?php
    endforeach;
}


/**
 * save the custom metabox data
 * @hooked to save_post hook
 */
function catchkathmandu_save_custom_meta( $post_id ) {
    global $post;

    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'custom_meta_box_nonce' ] ) || !wp_verify_nonce( $_POST[ 'custom_meta_box_nonce' ], basename( __FILE__ ) ) )
        return;

    // Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)
        return;

    if ('page' == $_POST['post_type']) {
        if (!current_user_can( 'edit_page', $post_id ) )
            return $post_id;
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
    }

    $meta_options = array( 
        'catchkathmandu-header-image',
        'catchkathmandu-sidebarlayout',
        'catchkathmandu-featured-image',
    );

    foreach ( $meta_options as $meta_option ) {
        if ( ! update_post_meta ( $post_id, $meta_option, sanitize_key( $_POST[$meta_option] ) ) ) {
            add_post_meta( $post_id, $meta_option, sanitize_key( $_POST[$meta_option] ), true );
        }
    }
}
add_action('save_post', 'catchkathmandu_save_custom_meta');
