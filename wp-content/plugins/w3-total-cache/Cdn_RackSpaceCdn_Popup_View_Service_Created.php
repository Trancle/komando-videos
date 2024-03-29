<?php
namespace W3TC;

if ( !defined( 'W3TC' ) )
	die();
?>
<form action="admin.php?page=w3tc_cdn" method="post" style="padding: 20px"
    class="w3tc_cdn_rackspace_form">
    <?php
Util_Ui::hidden( '', 'w3tc_action', 'cdn_rackspace_service_created_done' );
Util_Ui::hidden( '', 'user_name', $details['user_name'] );
Util_Ui::hidden( '', 'api_key', $details['api_key'] );
Util_Ui::hidden( '', 'access_token', $details['access_token'] );
Util_Ui::hidden( '', 'access_region_descriptor', $details['access_region_descriptor_serialized'] );
Util_Ui::hidden( '', 'region', $details['region'] );
Util_Ui::hidden( '', 'service_id', $details['service_id'] );
echo Util_Ui::nonce_field( 'w3tc' );

?>
<form class="w3tc_popup_form">
    <div class="metabox-holder">
        <?php Util_Ui::postbox_header( __( 'Succeeded', 'w3-total-cache' ) ); ?>

        <div style="text-align: center" class="w3tc_rackspace_created_in_progress">
            <div class="spinner" style="float: right; display: block"></div>
            <div style="text-align: left">
                Service <?php echo $details['name'] ?> was successfully created.<br />
                Waiting for RackSpace to finish publishing process.<br />
                <br />

                Actual state is:
                <strong><span class="w3tc_rackspace_created_status">Initiated</span></strong>
            </div>
        </div>

        <div style="display: none" class="w3tc_rackspace_created_done">
            <div style="text-align: center">
                <div style="text-align: left">
                    Service <?php echo $details['name'] ?> was successfully configured.<br />
                    <?php if ( !$is_https ): ?>
                        <br />
                        Now you need to change DNS records of your domain
                        <strong><?php echo $details['cname'] ?></strong> and CNAME it to<br />
                        <strong class="w3tc_rackspace_access_url"></strong> to make caching work.
                    <?php endif; ?>
                </div>
            </div>

            <p class="submit">
                <input type="button"
                    class="w3tc_popup_submit w3tc-button-save button-primary"
                    value="<?php _e( 'Done', 'w3-total-cache' ); ?>" />
            </p>
        </div>
        <?php Util_Ui::postbox_footer(); ?>
    </div>
</form>
