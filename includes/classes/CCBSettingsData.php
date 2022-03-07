<?php

namespace cBuilder\Classes;

class CCBSettingsData
{
    public static function get_tab_pages()
    {
        return ['calculator', 'conditions', 'settings', 'customize'];
    }

    public static function settings_data()
    {
        return [
            'general' => [
                'header_title'  => 'Total Summary',
                'descriptions'  => 'show',
                'boxStyle'      => 'vertical',
                'hide_empty'    => 'show',
            ],
            'currency' => [
                'currency'              => '$',
                'num_after_integer'     => 2,
                'decimal_separator'     => '.',
                'thousands_separator'   => ',',
                'currencyPosition'      => 'left_with_space',
            ],
            'formFields' => [
                'fields'            => [],
                'emailSubject'      => '',
                'contactFormId'     => '',
                'accessEmail'       => false,
                'adminEmailAddress' => '',
                'submitBtnText'     => 'Submit',
                'allowContactForm'  => false,
                'body'              => 'Dear sir/madam\n' .
                    'We would be very grateful to you if you could provide us the quotation of the following=>\n' .
                    '\nTotal Summary\n' .
                    '[ccb-subtotal]\n' .
                    'Total: [ccb-total-0]\n' .
                    'Looking forward to hearing back from you.\n' .
                    'Thanks in advance',
                'payment'           => false,
                'paymentMethod'     => '',
            ],

            'paypal' => [
                'enable'        => false,
                'description'   => '[ccb-total-0]',
                'paypal_email'  => '',
                'currency_code' => '',
                'paypal_mode'   => 'sandbox',
            ],

            'woo_products' => [
                'enable'            => false,
                'category_id'       => '',
                'hook_to_show'      => 'woocommerce_after_single_product_summary',
                'hide_woo_cart'     => false,
                'meta_links'        => [],
            ],

            'woo_checkout' => [
                'enable'        => false,
                'product_id'    => '',
                'redirect_to'   => 'cart',
                'description'   => '[ccb-total-0]',
            ],

            'stripe' => [
                'enable'        => false,
                'secretKey'     => '',
                'publishKey'    => '',
                'description'   => '[ccb-total-0]',

            ],

            'recaptcha_type' => [
                'v2'    => 'Google reCAPTCHA v2',
                'v3'    => 'Google reCAPTCHA v3'
            ],

            'recaptcha_v3' => [
                'siteKey'   => '',
                'secretKey' => '',
            ],

            'recaptcha' => [
                'enable'    => false,
                'type'      => 'v2',
                'options'   => [
                    'v2'    => 'Google reCAPTCHA v2',
                    'v3'    => 'Google reCAPTCHA v3'
                ],

                'v2'        => [
                    'siteKey'   => '',
                    'secretKey' => '',
                ],

                'v3'        => [
                    'siteKey'   => '',
                    'secretKey' => '',
                ]
            ],

            'notice' => [
                'requiredField' => 'This field is required',
            ],

            'icon'  => 'fas fa-cogs',
            'type'  => 'Cost Calculator Settings',
        ];
    }

    public static function get_settings_pages() {
        return [
            [
                'title' => __('General', 'cost-calculator-builder'),
                'slug'  => 'general',
                'icon'  => 'fas fa-cog',
            ],

            [
                'title' => __('Currency', 'cost-calculator-builder'),
                'slug'  => 'currency',
                'icon'  => 'fas fa-coins',
            ],

            [
                'title' => __('Send Form', 'cost-calculator-builder'),
                'slug'  => 'form',
                'icon'  => 'fas fa-envelope',
                'file'  => 'send-form',
            ],

            [
                'title' => __('Woo Products', 'cost-calculator-builder'),
                'slug'  => 'woo_products',
                'icon'  => 'fas fa-archive',
                'file'  => 'woo-products',
            ],

            [
                'title' => __('Woo Checkout', 'cost-calculator-builder'),
                'slug'  => 'woo_checkout',
                'icon'  => 'fas fa-shopping-cart',
                'file'  => 'woo-checkout',
            ],

            [
                'title' => __('Stripe', 'cost-calculator-builder'),
                'slug'  => 'stripe',
                'icon'  => 'fab fa-stripe-s',
                'file'  => 'stripe',
            ],

            [
                'title' => __('PayPal', 'cost-calculator-builder'),
                'slug'  => 'paypal',
                'icon'  => 'fab fa-paypal',
                'file'  => 'paypal',
            ],

            [
                'title' => __('Default Form', 'cost-calculator-builder'),
                'slug'  => 'default_form',
                'icon'  => 'fas fa-envelope-open-text',
                'file'  => 'default-form',
            ],

            [
                'title' => __('reCAPTCHA', 'cost-calculator-builder'),
                'slug'  => 'recaptcha',
                'icon'  => 'fas fa-robot',
                'file'  => 'recaptcha',
            ],

            [
                'title' => __('Notice', 'cost-calculator-builder'),
                'slug'  => 'notice',
                'icon'  => 'fas fa-exclamation-circle',
                'file'  => 'notice',
            ]
        ];
    }

    public static function stm_calc_created_set_option( $post_id, $post, $update ) {
        if ( ! $update ) {
            return;
        }

        $created = get_option( 'stm_calc_created', false );
        if ( ! $created ) {
            $data = [ 'show_time' => time(), 'step' => 0, 'prev_action' => '' ];
            set_transient( 'stm_cost-calculator-builder_single_notice_setting', $data );
            update_option( 'stm_calc_created', true );
        }
    }

    public static function stm_admin_notice_rate_calc( $data ) {
        if ( is_array( $data ) ) {
            $data['title']   = 'Well done!';
            $data['content'] = 'You have built your first calculator up. Now please help us by rating <strong>Cost Calculator 5 Stars!</strong>';
        }

        return $data;
    }
}