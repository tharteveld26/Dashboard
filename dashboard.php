<?php
/**
 * Enhanced Member Dashboard
 *
 * Custom account dashboard with badge rewards.
 *
 * Template overrides WooCommerce myaccount/dashboard.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$current_user = wp_get_current_user();
$allowed_html = array( 'a' => array( 'href' => array() ) );

$order_ids = wc_get_orders( [
    'customer_id' => $current_user->ID,
    'return'      => 'ids',
    'limit'       => -1,
] );

$numbers = implode( ', ', $order_ids );
$shipping = array_filter( [
    get_user_meta( $current_user->ID, 'shipping_first_name', true ) . ' ' . get_user_meta( $current_user->ID, 'shipping_last_name', true ),
    get_user_meta( $current_user->ID, 'shipping_address_1', true ),
    get_user_meta( $current_user->ID, 'shipping_address_2', true ),
    get_user_meta( $current_user->ID, 'shipping_city', true ),
    get_user_meta( $current_user->ID, 'shipping_state', true ),
    get_user_meta( $current_user->ID, 'shipping_postcode', true ),
    get_user_meta( $current_user->ID, 'shipping_country', true ),
] );
$shipping_address = implode( ', ', $shipping );

$mailto_help = 'mailto:hello@thetravelbuggycompany.co.uk?subject=' . rawurlencode( 'Support Request' ) . '&body=' . rawurlencode( "Order numbers: $numbers\nShipping address: $shipping_address" );
$mailto_callback = 'mailto:hello@thetravelbuggycompany.co.uk?subject=' . rawurlencode( 'Call Back Request' ) . '&body=' . rawurlencode( "Please call me regarding orders: $numbers\nShipping address: $shipping_address" );
$mailto_warranty = 'mailto:hello@thetravelbuggycompany.co.uk?subject=' . rawurlencode( 'Warranty Claim' ) . '&body=' . rawurlencode( "I would like to claim under warranty for orders: $numbers\nShipping address: $shipping_address" );
?>

<div class="tbc-member-dashboard">
    <header class="tbc-member-hero">
        <h2><?php printf( esc_html__( 'Welcome back, %s!', 'woocommerce' ), esc_html( $current_user->display_name ) ); ?></h2>
    </header>

    <div class="tbc-action-buttons">
        <a class="tbc-action-btn help" href="<?php echo esc_attr( $mailto_help ); ?>"><?php esc_html_e( 'Help', 'woocommerce' ); ?></a>
        <a class="tbc-action-btn callback" href="<?php echo esc_attr( $mailto_callback ); ?>"><?php esc_html_e( 'Request a Call Back', 'woocommerce' ); ?></a>
        <a class="tbc-action-btn warranty" href="<?php echo esc_attr( $mailto_warranty ); ?>"><?php esc_html_e( 'Claim Under Warranty', 'woocommerce' ); ?></a>
        <a class="tbc-action-btn helpcentre" href="https://thetravelbuggycompany.co.uk/help-centre" target="_blank" rel="noopener"><?php esc_html_e( 'Help Centre', 'woocommerce' ); ?></a>
        <a class="tbc-action-btn rewards" href="https://thetravelbuggycompany.co.uk/rewards" target="_blank" rel="noopener"><?php esc_html_e( 'Rewards', 'woocommerce' ); ?></a>
    </div>

    <div class="tbc-badge-section">
        <div class="tbc-xp-bar">
            <?php echo do_shortcode( '[wc_user_badges_xp_bar]' ); ?>
        </div>
        <div class="tbc-badges">
            <?php echo do_shortcode( '[wc_user_badges]' ); ?>
        </div>
    </div>

    <button type="button" class="tbc-toggle-links">
        <?php esc_html_e( 'Account Shortcuts', 'woocommerce' ); ?>
    </button>
    <div class="tbc-links-content">
        <a class="tbc-link" href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>">
            <?php esc_html_e( 'View Orders', 'woocommerce' ); ?>
        </a>
        <a class="tbc-link" href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>">
            <?php esc_html_e( 'Manage Addresses', 'woocommerce' ); ?>
        </a>
        <a class="tbc-link" href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>">
            <?php esc_html_e( 'Edit Details', 'woocommerce' ); ?>
        </a>
    </div>
</div>

<?php
/**
 * WooCommerce dashboard hooks.
 */
do_action( 'woocommerce_account_dashboard' );
do_action( 'woocommerce_before_my_account' );
do_action( 'woocommerce_after_my_account' );
?>

<style>
.tbc-member-dashboard {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}
.tbc-member-hero {
    text-align: center;
    margin-bottom: 20px;
}
.tbc-action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
    justify-content: center;
}
.tbc-action-btn {
    flex: 1 1 180px;
    text-align: center;
    padding: 15px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
}
.tbc-action-btn.help { background: #19864a; }
.tbc-action-btn.callback { background: #0069d9; }
.tbc-action-btn.warranty { background: #ffc107; color: #000; }
.tbc-action-btn.helpcentre { background: #6c757d; }
.tbc-action-btn.rewards { background: #d63384; }
.tbc-badge-section {
    text-align: center;
    margin-bottom: 20px;
}
.tbc-xp-bar,
.tbc-badges {
    margin-top: 10px;
}
.tbc-toggle-links {
    background: #19864a;
    border: none;
    color: #fff;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}
.tbc-links-content {
    display: none;
    margin-top: 15px;
}
.tbc-links-content.open {
    display: block;
}
.tbc-link {
    display: block;
    margin-bottom: 8px;
    color: #19864a;
    text-decoration: none;
}
.tbc-link:hover {
    text-decoration: underline;
}
</style>
<script>
document.addEventListener('DOMContentLoaded',function(){
    var btn=document.querySelector('.tbc-toggle-links');
    var box=document.querySelector('.tbc-links-content');
    if(btn&&box){
        btn.addEventListener('click',function(){
            box.classList.toggle('open');
        });
    }
});
</script>

<!-- Omit closing PHP tag to avoid "headers already sent" issues. -->
