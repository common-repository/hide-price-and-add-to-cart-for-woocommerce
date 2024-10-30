<?php
/**
 * WooManager Hide price by user roles.
 *
 * @package woo-managers-hide-price-lite
 */

defined( 'ABSPATH' ) || exit;

global $wp_roles, $post;

$rule_id = $post->ID;

$user_roles = $wp_roles->get_names();

$wmhp_users       = (array) get_post_meta( $rule_id, 'wmhp_users', true );
$wmhp_users_roles = (array) get_post_meta( $rule_id, 'wmhp_users_roles', true );

?>
<div class="wp-metabox metabox wmhp_metabox">
	<table class="wmhp_metabox_table">
		<tr>
			<th>
				<?php esc_html_e( 'Select User(s)', 'wmhp_hide_price' ); ?>
			</th>
			<td>
				<select class="wmhp_users" name="wmhp_users[]" id="wmhp_users" multiple>
					<?php
					foreach ( $wmhp_users as $user_id ) :

						$user = get_user_by( 'id', $user_id );

						if ( ! is_a( $user, 'WP_User' ) ) {
							continue;
						}

						$display_name = $user->display_name . '(' . $user->user_email . ')';
						?>
						<option value="<?php echo intval( $user_id ); ?>" selected><?php echo esc_attr( $display_name ); ?></option>
					<?php endforeach; ?>
				</select>
				<?php wc_help_tip( 'Select individual users for rule.', true ); ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php esc_html_e( 'Include user roles', 'wmhp_hide_price' ); ?>
			</th>
			<td>
				<select class="wmhp_select2" name="wmhp_users_roles[]" id="wmhp_users_roles" multiple>
					<?php foreach ( $user_roles as $role_val => $role_name ) : ?>
						<option value="<?php echo esc_attr( $role_val ); ?>" <?php echo in_array( $role_val, $wmhp_users_roles ) ? 'selected' : ''; ?>><?php echo esc_attr( $role_name ); ?></option>
					<?php endforeach; ?>
					<option value="guest" <?php echo in_array( 'guest', $wmhp_users_roles ) ? 'selected' : ''; ?>><?php esc_html_e( 'Guest', 'wmhp_hide_price' ); ?></option>
				</select>
				<?php wc_help_tip( 'Select the user roles that apply to this rule.', true ); ?>
			</td>
		</tr>
	</table>
</div>
