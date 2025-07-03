<?php
/**
 * Buggy Club Member Dashboard
 *
 * Tailwind-based layout with quick actions and badges.
 * Template overrides WooCommerce myaccount/dashboard.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$current_user = wp_get_current_user();

$order_ids = wc_get_orders([
    'customer_id' => $current_user->ID,
    'return'      => 'ids',
    'limit'       => -1,
]);

$numbers  = implode(', ', $order_ids);
$shipping = array_filter([
    get_user_meta( $current_user->ID, 'shipping_first_name', true ) . ' ' . get_user_meta( $current_user->ID, 'shipping_last_name', true ),
    get_user_meta( $current_user->ID, 'shipping_address_1', true ),
    get_user_meta( $current_user->ID, 'shipping_address_2', true ),
    get_user_meta( $current_user->ID, 'shipping_city', true ),
    get_user_meta( $current_user->ID, 'shipping_state', true ),
    get_user_meta( $current_user->ID, 'shipping_postcode', true ),
    get_user_meta( $current_user->ID, 'shipping_country', true ),
]);
$shipping_address = implode(', ', $shipping);

$recent_posts = wp_get_recent_posts([
    'numberposts' => 3,
    'post_status' => 'publish',
]);

$mailto_help     = 'mailto:hello@thetravelbuggycompany.co.uk?subject=' . rawurlencode('Support Request') . '&body=' . rawurlencode("Order numbers: $numbers\nShipping address: $shipping_address");
$mailto_callback = 'mailto:hello@thetravelbuggycompany.co.uk?subject=' . rawurlencode('Call Back Request') . '&body=' . rawurlencode("Please call me regarding orders: $numbers\nShipping address: $shipping_address");
$mailto_warranty = 'mailto:hello@thetravelbuggycompany.co.uk?subject=' . rawurlencode('Warranty Claim') . '&body=' . rawurlencode("I would like to claim under warranty for orders: $numbers\nShipping address: $shipping_address");
?>

<script src="https://cdn.tailwindcss.com"></script>

<div class="tbc-dashboard bg-gray-50 font-sans">
  <header class="bg-white shadow p-4 flex justify-between items-center">
    <div class="flex items-center">
      <img src="https://via.placeholder.com/40x40?text=tbco" alt="Logo" class="w-10 h-10 mr-2" />
      <h1 class="text-xl font-semibold">My Buggy Club</h1>
    </div>
    <div class="flex items-center">
      <span class="mr-4 text-gray-700">
        <?php printf( esc_html__('Hi, %s!', 'woocommerce'), '<strong>' . esc_html( $current_user->display_name ) . '</strong>' ); ?>
      </span>
      <div class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
        <?php esc_html_e('Member', 'woocommerce'); ?>
      </div>
    </div>
  </header>

  <main class="max-w-5xl mx-auto p-6">
    <section class="mb-8">
      <?php echo do_shortcode('[wc_user_badges_xp_bar]'); ?>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
      <a href="<?php echo esc_url( wc_get_endpoint_url('orders') ); ?>" class="bg-white shadow rounded-lg p-4 text-center hover:bg-gray-100">
        <div class="text-2xl mb-1">🛒</div>
        <div class="text-gray-800 font-medium"><?php esc_html_e('View Orders', 'woocommerce'); ?></div>
      </a>
      <a href="https://thetravelbuggycompany.co.uk/rewards" class="bg-white shadow rounded-lg p-4 text-center hover:bg-gray-100">
        <div class="text-2xl mb-1">🎁</div>
        <div class="text-gray-800 font-medium"><?php esc_html_e('Redeem Rewards', 'woocommerce'); ?></div>
      </a>
      <a href="<?php echo esc_attr( $mailto_help ); ?>" class="bg-white shadow rounded-lg p-4 text-center hover:bg-gray-100">
        <div class="text-2xl mb-1">✉️</div>
        <div class="text-gray-800 font-medium"><?php esc_html_e('Submit Ticket', 'woocommerce'); ?></div>
      </a>
      <a href="<?php echo esc_attr( $mailto_callback ); ?>" class="bg-white shadow rounded-lg p-4 text-center hover:bg-gray-100">
        <div class="text-2xl mb-1">📞</div>
        <div class="text-gray-800 font-medium"><?php esc_html_e('Request Callback', 'woocommerce'); ?></div>
      </a>
      <a href="<?php echo esc_attr( $mailto_warranty ); ?>" class="bg-white shadow rounded-lg p-4 text-center hover:bg-gray-100">
        <div class="text-2xl mb-1">🔧</div>
        <div class="text-gray-800 font-medium"><?php esc_html_e('Claim Warranty', 'woocommerce'); ?></div>
      </a>
    </section>

    <section class="mb-8">
      <h2 class="text-lg font-semibold mb-4"><?php esc_html_e('Your Badges', 'woocommerce'); ?></h2>
      <div class="flex space-x-4 overflow-x-auto pb-2">
        <?php echo do_shortcode('[wc_user_badges]'); ?>
      </div>
    </section>

    <?php if ( $recent_posts ) : ?>
    <section class="mb-8">
      <h2 class="text-lg font-semibold mb-4"><?php esc_html_e('Latest from our Blog', 'woocommerce'); ?></h2>
      <ul class="space-y-4">
        <?php foreach ( $recent_posts as $post ) : ?>
          <li class="bg-white shadow rounded-lg p-4">
            <a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>" class="text-blue-600 font-medium hover:underline"><?php echo esc_html( $post['post_title'] ); ?></a>
            <p class="text-gray-600 text-sm mt-1"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $post['post_content'] ), 20, '...' ) ); ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>
    <?php endif; ?>
  </main>
</div>

<?php
do_action('woocommerce_account_dashboard');
do_action('woocommerce_before_my_account');
do_action('woocommerce_after_my_account');
// Omit closing PHP tag to avoid "headers already sent" issues.
