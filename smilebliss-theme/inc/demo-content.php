<?php
/**
 * One-click starter content.
 *
 * Builds the licensing page from the same copy and imagery the static build
 * shipped with, so a fresh install is a working page rather than an empty
 * Flexible Content field. Everything it creates is ordinary content and can be
 * edited or deleted afterwards.
 *
 * @package Smilebliss
 */

declare( strict_types = 1 );

require_once SMILEBLISS_DIR . '/inc/starter-sections.php';

const SMILEBLISS_IMPORT_FLAG = 'smilebliss_starter_imported';

/**
 * Admin screen under the Smilebliss menu.
 */
function smilebliss_starter_menu(): void {
	add_submenu_page(
		'smilebliss-settings',
		__( 'Starter Content', 'smilebliss' ),
		__( 'Starter Content', 'smilebliss' ),
		'edit_theme_options',
		'smilebliss-starter',
		'smilebliss_starter_screen'
	);
}
add_action( 'admin_menu', 'smilebliss_starter_menu', 20 );

/**
 * Render the screen and handle the submission.
 */
function smilebliss_starter_screen(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do that.', 'smilebliss' ) );
	}

	$done = false;
	$page_id = 0;

	if ( isset( $_POST['smilebliss_starter'] ) && check_admin_referer( 'smilebliss_starter' ) ) {
		$page_id = smilebliss_import_starter_content();
		$done    = $page_id > 0;
	}

	$already = (int) get_option( SMILEBLISS_IMPORT_FLAG, 0 );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Smilebliss starter content', 'smilebliss' ); ?></h1>

		<?php if ( $done ) : ?>
			<div class="notice notice-success"><p>
				<?php
				printf(
					/* translators: %s: link to the created page. */
					wp_kses_post( __( 'Created and set as the front page. <a href="%s">Edit the page</a>.', 'smilebliss' ) ),
					esc_url( (string) get_edit_post_link( $page_id ) )
				);
				?>
			</p></div>
		<?php elseif ( $already ) : ?>
			<div class="notice notice-info"><p>
				<?php esc_html_e( 'Starter content has been imported before. Running it again creates a second page; it never overwrites what is already there.', 'smilebliss' ); ?>
			</p></div>
		<?php endif; ?>

		<?php if ( ! smilebliss_has_acf_pro() ) : ?>
			<div class="notice notice-error"><p>
				<?php esc_html_e( 'Advanced Custom Fields PRO is not active, so sections cannot be written yet.', 'smilebliss' ); ?>
			</p></div>
		<?php else : ?>
			<p><?php esc_html_e( 'Builds a page with all ten sections filled in, sideloads the bundled photography and icons into the media library, and sets the page as the site front page.', 'smilebliss' ); ?></p>
			<form method="post">
				<?php wp_nonce_field( 'smilebliss_starter' ); ?>
				<p>
					<button type="submit" name="smilebliss_starter" value="1" class="button button-primary">
						<?php esc_html_e( 'Import starter content', 'smilebliss' ); ?>
					</button>
				</p>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Copy a bundled asset into the media library, once per filename.
 *
 * @return int Attachment ID, or 0 on failure.
 */
function smilebliss_sideload_asset( string $filename ): int {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_smilebliss_asset', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $filename, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	$source = SMILEBLISS_DIR . '/assets/img/' . $filename;
	if ( ! file_exists( $source ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';

	$upload = wp_upload_bits( $filename, null, (string) file_get_contents( $source ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$type = wp_check_filetype( $filename, null );
	$id   = wp_insert_attachment(
		array(
			'post_mime_type' => $type['type'] ?: 'image/webp',
			'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}

	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_post_meta( $id, '_smilebliss_asset', $filename );

	return (int) $id;
}

/**
 * Build the page and write every section.
 *
 * @return int New page ID, or 0 on failure.
 */
function smilebliss_import_starter_content(): int {
	if ( ! function_exists( 'update_field' ) ) {
		return 0;
	}

	$page_id = wp_insert_post(
		array(
			'post_title'   => __( 'Join Smilebliss', 'smilebliss' ),
			'post_name'    => 'join-smilebliss',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '',
		)
	);

	if ( is_wp_error( $page_id ) || ! $page_id ) {
		return 0;
	}

	$img = static fn( string $file ): int => smilebliss_sideload_asset( $file );

	update_field( 'sections', smilebliss_starter_sections( $img ), $page_id );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $page_id );
	update_option( SMILEBLISS_IMPORT_FLAG, time() );

	// Seed the site-wide settings too, so the header and footer are not bare.
	update_field( 'logo', smilebliss_sideload_asset( 'smilebliss-logo.webp' ), 'option' );
	update_field( 'logo_reversed', smilebliss_sideload_asset( 'smilebliss-logo-reverse.webp' ), 'option' );
	update_field(
		'header_cta',
		array(
			'title'  => __( "Let's Do This", 'smilebliss' ),
			'url'    => '#contact',
			'target' => '',
		),
		'option'
	);
	update_field( 'contact_email', 'info@smilebliss.com', 'option' );
	update_field( 'contact_phone', '833-833-1148', 'option' );
	update_field( 'footer_blurb', __( 'A proven, ready-to-go orthodontic practice model. Marketing, revenue cycle management, procurement, peer support and training, all done for you.', 'smilebliss' ), 'option' );
	update_field( 'footer_legal', __( 'Projected results are estimates and are not a guarantee of results.', 'smilebliss' ), 'option' );

	return (int) $page_id;
}
