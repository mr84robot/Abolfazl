<?php
/**
 * فیلدهای فرمِ آدرس (مثلِ یک فروشگاهِ ایرانیِ معمولی) و woocommerce_form_field برای پیش‌نمایش‌ها
 * (build-checkout.php و build-account.php). خروجی همان HTMLِ woocommerce_form_fieldِ ووکامرس است.
 */

/* ---------------- فیلدها ---------------- */
$STATES = array( '' => 'یک گزینه انتخاب کنید…', 'THR' => 'تهران', 'ESF' => 'اصفهان', 'FRS' => 'فارس', 'KHZ' => 'خوزستان', 'ALB' => 'البرز' );
function pv_fields( $pre ) {
	$f = array(
		$pre . '_first_name' => array( 'label' => 'نام', 'required' => true, 'class' => array( 'form-row-first' ), 'autocomplete' => 'given-name' ),
		$pre . '_last_name'  => array( 'label' => 'نام خانوادگی', 'required' => true, 'class' => array( 'form-row-last' ), 'autocomplete' => 'family-name' ),
		$pre . '_country'    => array( 'type' => 'country', 'label' => 'کشور / منطقه', 'required' => true, 'class' => array( 'form-row-wide', 'address-field', 'update_totals_on_change', 'ks-field-hidden' ) ), // ks-field-hidden: inc/shop-setup.php
		$pre . '_state'      => array( 'type' => 'state', 'label' => 'استان', 'required' => true, 'class' => array( 'form-row-first', 'address-field' ) ),
		$pre . '_city'       => array( 'label' => 'شهر', 'required' => true, 'class' => array( 'form-row-last', 'address-field' ) ),
		$pre . '_address_1'  => array( 'label' => 'آدرس کامل', 'required' => true, 'class' => array( 'form-row-wide', 'address-field' ), 'placeholder' => 'خیابان، کوچه، پلاک، واحد' ),
		$pre . '_postcode'   => array( 'label' => 'کد پستی', 'required' => true, 'class' => array( 'form-row-first', 'address-field' ), 'placeholder' => '۱۰ رقم، بدون خط تیره' ),
	);
	if ( 'billing' === $pre ) {
		$f['billing_phone'] = array( 'type' => 'tel', 'label' => 'شماره موبایل', 'required' => true, 'class' => array( 'form-row-last' ), 'placeholder' => '09xxxxxxxxx', 'autocomplete' => 'tel' );
		$f['billing_email'] = array( 'type' => 'email', 'label' => 'ایمیل', 'required' => false, 'class' => array( 'form-row-wide' ), 'autocomplete' => 'email' );
	}
	return $f;
}
function woocommerce_form_field( $key, $a, $value = null ) {
	$type = $a['type'] ?? 'text';
	$req  = ! empty( $a['required'] );
	$cls  = array_merge( array( 'form-row' ), $a['class'] ?? array() );
	if ( $req ) { $cls[] = 'validate-required'; }
	if ( 'errors' === ( $GLOBALS['MODE'] ?? '' ) && in_array( $key, array( 'billing_phone', 'billing_postcode' ), true ) ) { $cls[] = 'woocommerce-invalid'; $cls[] = 'woocommerce-invalid-required-field'; }
	$lbl = '<label for="' . $key . '" class="">' . $a['label'] . '&nbsp;' . ( $req ? '<span class="required" aria-hidden="true">*</span>' : '<span class="optional">(اختیاری)</span>' ) . '</label>';
	$ph  = isset( $a['placeholder'] ) ? ' placeholder="' . esc_attr( $a['placeholder'] ) . '"' : '';
	$val = esc_attr( (string) $value );
	switch ( $type ) {
		case 'country':  $in = '<strong>ایران</strong><input type="hidden" name="' . $key . '" id="' . $key . '" value="IR" autocomplete="country" class="country_to_state" readonly="readonly" />'; break;
		case 'state':    $in = '<select name="' . $key . '" id="' . $key . '" class="state_select" autocomplete="address-level1" data-placeholder="یک گزینه انتخاب کنید…">'; foreach ( $GLOBALS['STATES'] as $k => $v ) { $in .= '<option value="' . $k . '"' . ( $k === $value ? ' selected="selected"' : '' ) . '>' . $v . '</option>'; } $in .= '</select>'; break;
		case 'textarea': $in = '<textarea name="' . $key . '" class="input-text " id="' . $key . '"' . $ph . ' rows="2" cols="5">' . $val . '</textarea>'; break;
		case 'password': $in = '<input type="password" class="input-text " name="' . $key . '" id="' . $key . '"' . $ph . ' value="" autocomplete="new-password" />'; break;
		default:         $in = '<input type="' . $type . '" class="input-text " name="' . $key . '" id="' . $key . '"' . $ph . ' value="' . $val . '"' . ( isset( $a['autocomplete'] ) ? ' autocomplete="' . $a['autocomplete'] . '"' : '' ) . ' />';
	}
	echo '<p class="' . esc_attr( implode( ' ', $cls ) ) . '" id="' . $key . '_field">' . $lbl . '<span class="woocommerce-input-wrapper">' . $in . '</span></p>';
}
