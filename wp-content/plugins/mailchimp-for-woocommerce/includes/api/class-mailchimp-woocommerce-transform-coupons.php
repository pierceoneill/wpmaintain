<?php

/**
 * Created by Vextras.
 *
 * Name: Ryan Hungate
 * Email: ryan@vextras.com
 * Date: 10/06/17
 * Time: 8:29 AM
 */
class MailChimp_WooCommerce_Transform_Coupons {

	/**
	 * @param int $page
	 * @param int $limit
	 *
	 * @return object
	 */
	public function compile( $page = 1, $limit = 5 ) {
		$response = (object) array(
			'endpoint' => 'coupons',
			'page'     => $page ? $page : 1,
			'limit'    => (int) $limit,
			'count'    => 0,
			'stuffed'  => false,
			'items'    => array(),
            'has_next_page' => false
		);

		if ( ( ( $coupons = $this->getCouponPosts( $page, $limit ) ) && ! empty( $coupons['items'] ) ) ) {
			foreach ( $coupons['items'] as $post_id ) {
				$response->items[] = $post_id;
				$response->count++;
			}

            $response->has_next_page = $coupons['has_next_page'];
        }

		$response->stuffed = $response->count > 0 && (int) $response->count === (int) $limit;

		return $response;
	}

	/**
	 * @param $post_id
	 *
	 * @return MailChimp_WooCommerce_PromoCode
	 */
	public function transform( $post_id ) {
		$resource = new WC_Coupon( $post_id );
		$valid    = true;

		if ( ( $exp = $resource->get_date_expires() ) && time() > $exp->getTimestamp() ) {
			$valid = false;
		}

		$rule = new MailChimp_WooCommerce_PromoRule();

		$rule->setId( $resource->get_id() );
		$rule->setTitle( $resource->get_code() );
		$rule->setDescription( $resource->get_description() );
		$rule->setEnabled( $valid );
		$rule->setAmount( $resource->get_amount( 'edit' ) );

		if ( ! $rule->getDescription() ) {
			$rule->setDescription( $resource->get_code() );
		}

		switch ( $resource->get_discount_type() ) {
			case 'fixed_product':
				// Support to Woocommerce Free Gift Coupon Plugin
			case 'free_gift':
				$rule->setTypeFixed();
				$rule->setTargetTypePerItem();
				break;

			case 'fixed_cart':
				$rule->setTypeFixed();
				$rule->setTargetTypeTotal();
				break;

			case 'percent':
				$rule->setTypePercentage();
				$rule->setTargetTypeTotal();
				$rule->setAmount( ( $resource->get_amount( 'edit' ) / 100 ) );
				break;
		}

		if ( ( $exp = $resource->get_date_expires() ) ) {
			$rule->setEndsAt( $exp );
		}

		$code = new MailChimp_WooCommerce_PromoCode();

		$code->setId( $resource->get_id() );
		$code->setCode( $resource->get_code() );
		$code->setEnabled( $valid );
		$code->setRedemptionURL( get_home_url() );
		$code->setUsageCount( $resource->get_usage_count() );

		// attach the rule for use.
		$code->attachPromoRule( $rule );

		return apply_filters('mailchimp_sync_promocode', $code, $resource);
	}

	/**
	 * @param int $page
	 * @param int $posts
	 * @return array|bool
	 */
	public function getCouponPosts( $page = 1, $posts = 5 ) {
		$offset = 0;
		if ( $page > 1 ) {
			$offset = ( ( $page - 1 ) * $posts );
		}

        $limit = $posts + 1;

        $args = array(
			'post_type'      => 'shop_coupon',
			'posts_per_page' => $limit,
			'offset'         => $offset,
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'fields'         => 'ids',
		);

		$coupons = get_posts( $args );

        $has_next_page = count( $coupons ) > $posts;

        if ( $has_next_page ) {
            array_pop( $coupons );
        }

		if ( empty( $coupons ) ) {

			sleep( 2 );

			$coupons = get_posts( $args );

            $has_next_page = count( $coupons ) > $posts;

            if ( $has_next_page ) {
                array_pop( $coupons );
            }

			if ( empty( $coupons ) ) {
				return false;
			}
		}

        return [
            'items' => $coupons,
            'has_next_page' => $has_next_page,
        ];
    }
}
