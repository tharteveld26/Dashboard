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
?>

<div class="tbc-member-dashboard">
    <header class="tbc-member-hero">
        <h2><?php printf( esc_html__( 'Welcome back, %s!', 'woocommerce' ), esc_html( $current_user->display_name ) ); ?></h2>
        <div class="tbc-xp-bar">
            <?php echo do_shortcode( '[wc_user_badges_xp_bar]' ); ?>
        </div>
        <div class="tbc-badges">
            <?php echo do_shortcode( '[wc_user_badges]' ); ?>
        </div>
    </header>

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
