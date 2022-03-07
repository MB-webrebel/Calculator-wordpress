=== Mortgage Calculator / Loan Calculator ===
Tags: mortgage calculator, loan calculator, calculator, mortgage, real estate, loan
Contributors: mlcalc
Requires at least: 2.0.1
Tested up to: 5.8.1
Stable tag: 1.5.17

Powerful mortgage and loan calculator widget with shortcode.

== Description ==

[Mortgage Calculator](https://www.mlcalc.com/ "Mortgage Calculator Homepage") is a great value-add for any mortgage loan officer or real estate agent looking to provide good-looking, functional, valuable mortgage content to readers. The execution of this mortgage calculator is superb from the smooth sidebar integration, modal window results and AJAX-based graphs that are incredibly visually appealing. It's a great little loan application that delivers a lot of mortgage value in a user-friendly and well-executed way.

__Shortcode__

Use the mortgage/loan shortcodes on different pages:

`[mlcalc]`

specify default mortgage values:

`[mlcalc calculator="mortgage" size="narrow" price="125,000" rate="6.2" language="es" currency="eur"]`

Languages included: English, German, Spanish, French, Italian, Russian, Portuguese, and Albanian.

[WordPress Mortgage Loan Calculator Plugin](https://www.mlcalc.com/free-widgets/mortgage-loan-calculator/wordpress.htm)

== Installation ==

1. Upload the `mortgage-loan-calculator` directory (including all files within) to the `/wp-content/plugins/` directory.
2. Activate the mortgage calculator plugin through the 'Plugins' menu in WordPress.

= Usage =

* If you are using widgets, just add the Mortgage Calculator to the sidebar through the `Appearance -> Widgets -> Mortgage Calculator` menu in WordPress.
* Add the following code: `<?php display_mlcalc(); ?>` to your template where you would like the mortgage loan calculator form to appear.
* Add the shortcode `[mlcalc]` to your page or post and configure default mortgage parameters.

= Shortcode mortgage and loan parameters =
* calculator = mortgage | loan | mortgage_only | loan_only
* size = wide | narrow
* currency = usd | eur | gbp | CUSTOM
* rate = '{interest rate}'
* price = '{purchase price}'
* mterm = '{mortgage term in years}'
* down = '{down payment in %%}'
* tax = '{property tax}'
* insurance = '{property insurance}'
* pmi = '{pmi in %%}'
* amount = '{loan amount}'
* lterm = '{loan term in years}'
* schedule = month | year | month_nc | year_nc | none (_nc stands for "no choice" for visitor)
* language = en | de | es | fr | it | pt | ru

= Shortcode examples =
`	
[mlcalc]
[mlcalc calculator="mortgage_only"]
[mlcalc calculator="mortgage" size="narrow" price="125,000" rate="6.2" language="es" currency="eur"]`

== Screenshots ==
1. The Mortgage Calculator front-end, as it appears in your WordPress Sidebar.
2. The Mortgage Calculation Summary and Chart, as it appears when a visitor clicks the Calculate button.
3. The Mortgage Calculator configuration dialog, as it appears within WordPress Administration Interface.

== Upgrade Notice ==

= 1.5.17 =
Security XSS issue fixed for shortcode.

= 1.5.16 =
Security XSS issue fixed for shortcode.

= 1.5.15 =
Switched to calling file locations properly, using plugins_url().

= 1.5.14 =
Security fix. Added sanitizing for all input fields.

= 1.5.13 =
Security XSS issue fixed for shortcode.

= 1.5.12 =
Small fix.

= 1.5.11 =
Small fix.

= 1.5.10 =
Increased font size.

= 1.5.9 =
HTML fixes.

= 1.5.8 =
Wordpress 5.6.1 tested. Some bugs fixed.

= 1.5.7 =
Small fixes.

= 1.5.6 =
CSS bugs fixed. Optimizations for mobile devices.

= 1.5.5 =
Small fixes and tests.

= 1.5.4 =
Small HTML fixes.

= 1.5.3 =
Small fix for Internet Explorer 10.

= 1.5.2 =
Small fixes.

= 1.5.1 =
Small interface fixes.

= 1.5 =
Now all images, CSS and JavaScript are bundled with the plugin and loaded from your own server.

== Frequently Asked Questions ==

= May I use your widget on my commercial website =
Yes. Absolutely.
