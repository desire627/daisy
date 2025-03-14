<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use TemPlazaFramework\Functions;
use Advanced_Product\Helper\AP_Helper;
if ( !class_exists( 'TemPlazaFramework\TemPlazaFramework' )){
    $templaza_options = array();
}else{
    $templaza_options = Functions::get_theme_options();
}
$tzbooking_adult_price = $tzbooking_child_price = $tzbooking_fnr_price = '';
$adult_price  = isset($templaza_options['ap_product_data_price'])?$templaza_options['ap_product_data_price']:'';
if($adult_price){
    $tzbooking_adult_price = get_field($adult_price, get_the_ID());
}
$child_price  = isset($templaza_options['ap_product_data_child_price'])?$templaza_options['ap_product_data_child_price']:'';
if($child_price){
    $tzbooking_child_price = get_field($child_price, get_the_ID());
}
$fnr_price  = isset($templaza_options['ap_product_data_fnr_price'])?$templaza_options['ap_product_data_fnr_price']:'';
if($fnr_price){
    $tzbooking_fnr_price = get_field($fnr_price, get_the_ID());
}
$tzbooking_product_type = 'daily';
$tzbooking_departure_time = array('11:00', '8:30', '9:00');
$tzbooking_max_adults = 0;
$tzbooking_decimal_prec   = get_option('options_ap_price_num_decimals', 0);
$tzbooking_decimal_sep    = get_option('options_ap_price_decimal_sep', ',');
$tzbooking_thousands_sep  = get_option('options_ap_price_thousands_sep', ',');

?>
<div class="tz-product-booking">
    <div class="tz-product-book-form">
        <form method="get" id="booking-form" action="<?php echo esc_url( tzbooking_get_product_cart_page() ); ?>">
            <input type="hidden" name="product_id" value="<?php echo get_the_ID()?>">
                <input type="hidden" name="people_available" value="">
                <input name="last_name" value="" placeholder="" type="hidden" >
                <div class="form-group">
                    <div class="book-name">
                        <input name="first_name" value="" placeholder="<?php esc_html_e('Your Name','travelami' ); ?>" type="text" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="book-email">
                        <input name="your_email" value="" placeholder="<?php esc_html_e('Your Email','travelami' ); ?>" type="text" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="book-phone">
                        <input name="your_phone" value="" placeholder="<?php esc_html_e('Phone Number','travelami' ) ?>" type="text" >
                    </div>
                </div>
                <div class="form-group">
                    <div class="book-departure-date">
                        <input class="date_picker form-control"  type="text" name="date" placeholder="<?php esc_html_e('Start Date','travelami') ?>">
                    </div>
                </div>
                <div class="form-group uk-hidden">
                    <div class="book-departure-time">
                        <select name="departure_time">
                            <option  value=""><?php esc_html_e('Choose time','travelami' ); ?></option>
                        </select>
                    </div>
                </div>
            <?php if( $tzbooking_adult_price != ''){ ?>
                <div class="form-group form-price">
                    <label><?php esc_html_e('Adult','travelami' ); ?></label>
                    <div class="st_adults_children uk-flex uk-flex-middle">
                        <div class="input-number-ticket uk-position-relative">
                            <input class="input-number uk-margin-remove" name="number_adults" type="text" value="1" data-min="1" data-max="10000" min="1" max="10000"/>
                            <span class="input-number-decrement"><i class="fas fa-caret-down"></i></span><span class="input-number-increment"><i class="fas fa-caret-up"></i></span>
                            <input name="price_adults" value="<?php echo esc_attr($tzbooking_adult_price); ?>" type="hidden">
                        </div>
                        <div class="tz_price">
                            <span class="adult_price"><?php echo esc_html('×&nbsp;').AP_Helper::format_price($tzbooking_adult_price); ?></span>
                            <span class="total_price_adults"><?php echo esc_html('=&nbsp;').AP_Helper::format_price($tzbooking_adult_price); ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if( $tzbooking_child_price != '' ){ ?>
                <div class="form-group form-price">
                    <label><?php esc_html_e('Children','travelami' ); ?></label>
                    <div class="st_adults_children uk-flex uk-flex-middle">
                        <div class="input-number-ticket uk-position-relative">
                            <input class="input-number uk-margin-remove" name="number_children" type="text" value="0" data-min="0" data-max="10000" min="0" max="10000"/>
                            <span class="input-number-decrement"><i class="fas fa-caret-down"></i></span><span class="input-number-increment"><i class="fas fa-caret-up"></i></span>
                            <input name="price_child" value="<?php echo esc_attr($tzbooking_child_price); ?>" type="hidden">
                        </div>
                        <div class="tz_price">
                            <span class="child_price"><?php echo esc_html('×&nbsp;').AP_Helper::format_price($tzbooking_child_price); ?></span>
                            <span class="total_price_children"><?php echo esc_html('=&nbsp;').AP_Helper::format_price(0); ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if( $tzbooking_fnr_price != '' ){ ?>
                <div class="form-group form-price">
                    <label><?php esc_html_e('FNR','travelami' ); ?></label>
                    <div class="st_adults_children uk-flex uk-flex-middle">
                        <div class="input-number-ticket uk-position-relative">
                            <input class="input-number uk-margin-remove" name="number_fnr" type="text" value="0" data-min="0" data-max="10000" min="0" max="10000"/>
                            <span class="input-number-decrement"><i class="fas fa-caret-down"></i></span><span class="input-number-increment"><i class="fas fa-caret-up"></i></span>
                            <input name="price_fnr" value="<?php echo esc_attr($tzbooking_fnr_price); ?>" type="hidden">
                        </div>
                        <div class="tz_price">
                            <span class="fnr_price"><?php echo esc_html('×&nbsp;').AP_Helper::format_price($tzbooking_fnr_price); ?></span>
                            <span class="total_price_fnr"><?php echo esc_html('=&nbsp;').AP_Helper::format_price(0); ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if($tzbooking_child_price !='' || $tzbooking_adult_price !='' || $tzbooking_fnr_price !=''){ ?>
            <div class="tz-product-total-price">
            <?php esc_html_e('Total:','travelami');?>
                <span class="total-price">
                    <span class="total_all_price"> <?php
                        if($tzbooking_adult_price != ''){
                            $tzbooking_total_price = $tzbooking_adult_price;
                            echo AP_Helper::format_price($tzbooking_total_price);
                        }elseif($tzbooking_child_price != ''){
                            $tzbooking_total_price = $tzbooking_child_price;
                            echo AP_Helper::format_price($tzbooking_total_price);
                        }elseif($tzbooking_fnr_price != ''){
                            $tzbooking_total_price = $tzbooking_fnr_price;
                            echo AP_Helper::format_price($tzbooking_total_price);
                        }
                        ?></span>
                </span>
            </div>
            <?php } ?>
            <button type="submit" class="btn_full book-now templaza-btn"><?php esc_html_e('Book This Tour','travelami');?></button>
        </form>
    </div>
    <div class="tz-booking-data" data-adults-price="<?php if($tzbooking_adult_price != ''){ echo esc_attr( $tzbooking_adult_price ); }else{ echo '0';} ?>" data-child-price="<?php if($tzbooking_child_price != ''){ echo esc_attr( $tzbooking_child_price ); }else{ echo '0';} ?>" data-fnr-price="<?php if($tzbooking_fnr_price != ''){ echo esc_attr( $tzbooking_fnr_price ); }else{ echo '0';} ?>"  data-decimal-prec="<?php echo esc_attr($tzbooking_decimal_prec); ?>" data-decimal-sep="<?php echo esc_attr($tzbooking_decimal_sep); ?>" data-thousands-sep="<?php echo esc_attr($tzbooking_thousands_sep); ?>"  data-departure-time='<?php echo json_encode($tzbooking_departure_time );?>'></div>
</div>
