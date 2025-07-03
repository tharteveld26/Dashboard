<?php
defined( 'ABSPATH' ) || exit;

$customer_orders = wc_get_orders( [
  'customer_id' => get_current_user_id(),
  'limit'       => -1,
  'orderby'     => 'date',
  'order'       => 'DESC',
] );

if ( ! $customer_orders ) {
  echo '<p>You have no past orders.</p>';
  return;
}
?>

<style>
.tbc-order-block {
  background: #fff;
  border-radius: 8px;
  margin-bottom: 30px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);
  overflow: hidden;
}

/* Header */
.tbc-order-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  background: #f7f7f7;
  padding: 12px 20px;
}
.tbc-order-header .meta {
  font-size: 14px;
  color: #555;
}
.tbc-order-header .meta small {
  display: block;
  color: #666;
  margin-top: 4px;
}
.tbc-order-header .product-title {
  margin: 8px 0 0;
  font-size: 18px;
  color: #d63384;
  text-transform: lowercase;
  line-height: 1.2;
}
.tbc-order-header .total-price {
  font-size: 20px;
  font-weight: 700;
  color: #19864a;
}

/* Body */
.tbc-order-body {
  display: flex;
  align-items: stretch;
  padding: 20px;
  gap: 20px;
}

/* Image stays fixed width on the left, full card‐height */
.tbc-order-image {
  flex: none;
  width: 120px;      /* whatever width you prefer */
  overflow: hidden;
}

/* Let the image fill its container’s height */
.tbc-order-image img {
  display: block;
  height: 100%;
  object-fit: cover;
}


/* Actions column is fixed width and pushed to the right */
.tbc-order-actions {
  flex: none;
  width: 30%;
  margin-left: auto;    /* push this column as far right as possible */
  text-align: right;
}

/* Stack buttons full-width in that 180px column */
.tbc-order-actions a {
  display: block;
  width: 100%;
  padding: 10px 20px;
  margin-bottom: 8px;
  border-radius: 4px;
  text-decoration: none;
  font-size: 14px;
  color: #fff;
}

.tbc-order-actions .view-btn     { background: #19864a; }
.tbc-order-actions .feedback-btn { background: #d63384; }
.tbc-order-actions .manual-btn   { background: #6c757d; }
</style>

<?php foreach ( $customer_orders as $order ) :
    $order_id  = $order->get_id();
    $date      = $order->get_date_created()->format( 'j M Y' );
    $status    = wc_get_order_status_name( $order->get_status() );
    $deliv     = $order->get_date_completed()
                ? $order->get_date_completed()->format( 'j M Y' )
                : 'In Progress';

    // First product
    $items   = $order->get_items();
    $first   = reset( $items );
    $prod    = $first->get_product();
    $img     = $prod ? $prod->get_image( 'medium' ) : wc_placeholder_img( 'medium' );
    $title   = $first->get_name() . ' – x' . $first->get_quantity();
    $total   = $order->get_formatted_order_total();
    $view    = wc_get_endpoint_url( 'view-order', $order_id );
?>
<div class="tbc-order-block">

  <div class="tbc-order-header">
    <div>
      <div class="meta">
        Order #<?php echo $order_id; ?> — <?php echo esc_html( $status ); ?>
        <small>Placed on <?php echo $date; ?> · Estimated delivery: <?php echo $deliv; ?></small>
      </div>
      <h2 class="product-title"><?php echo wp_kses_post( $title ); ?></h2>
    </div>
    <div class="total-price"><?php echo wp_kses_post( $total ); ?></div>
  </div>

  <div class="tbc-order-body">
    <div class="tbc-order-image"><?php echo $img; ?></div>
    <div class="tbc-order-actions">
      <a href="<?php echo esc_url( $view ); ?>" class="view-btn">View Order</a>
      <?php if ( $order->has_status( 'completed' ) ) : ?>
        <a href="https://uk.trustpilot.com/evaluate/thetravelbuggycompany.co.uk"
           class="feedback-btn" target="_blank">Leave Feedback</a>
      <?php endif; ?>
      <?php
      $manual = get_post_meta( $prod->get_id(), 'tbc_manual_url', true );
      if ( empty( $manual ) && $prod && $prod->is_type( 'variation' ) ) {
        $manual = get_post_meta( $prod->get_parent_id(), 'tbc_manual_url', true );
      }
      if ( $manual ) : ?>
        <a href="<?php echo esc_url( $manual ); ?>"
           class="manual-btn" target="_blank">Download Manual</a>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php endforeach; ?>