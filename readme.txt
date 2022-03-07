=== Responsive Mortgage Calculator ===
Tags: mortgage, mortgage calculator, responsive mortgage calculator, loan, calculator, realty, realtor, real estate, widget
Contributors: rmcalculator
Requires at least: 3.0.1
Tested up to: 5.7
Stable tag: 2.4.2

A simple responsive mortgage calculator widget and shortcode.

== Description ==

The Responsive Mortgage Calculator is a jQuery widget and shortcode that's designed to fit easily into any theme, on any device, at any size. Just what every "Real Estate Agent on the go" needs. This plugin is, optionally, relying on [www.mlcalc.com](https://www.mlcalc.com/ "Mortgage Calculator") service to show mortgage amortization and chart.

= Features =

The mortgage calculator allows your website visitors to estimate their mortgage payments by entering:

* the total cost of the home,
* a down payment amount,
* an interest rate (fixed rate),
* the amortization period (mortgage term),
* and they can select a payment period, either monthly, bi-weekly, or weekly,
* nice popup summary with chart and amortization schedule.

The mortgage payment result is displayed below the form - very simply and very easy to follow. For the savvy user, a click on the information icon reveals more mortgage details…

= Redesign It =

There are settings to adjust the styling, a light and a dark theme, or you can remove the styling entirely and use your theme’s styling. The HTML is built with plenty of classes, so it’s easy to override the included stylesheet with your own CSS. 

= Plenty of Options =

* Set the interest rate compounding period for your region.
* Format currencies how you want with your own symbol, ISO code and number format.
* Hide the down payment field.
* Set a default interest rate.
* Accept amortization period in months or years.
* Set a fixed payment period.
* Set available mortgage payment period options.
* Rename the input labels.
* Add your own CSS classes.

= Shortcode Attributes =

Use the shortcode on different pages with different field names by using shortcode attributes. They’re pretty obvious, but here’s an example:

`[mortgagecalculator totalamount="Mortgage Amount"]`

or use the first letter of the original labels:

`[rmc ta="Mortgage Amount"]`

Set input values using attributes for total amount, down payment, interest rate, and mortgage term the long way:

`[mortgagecalculator total_amount_value="$250,000" down_payment_value="$50,000" interest_rate_value="3.49%" amortization_period_value="15 years"]`

or using the short versions:

`[rmc tav="$250,000" dpv="$50,000" irv="3.49%" apv="15 years"]`

= Disclaimer =

The Responsive Mortgage Calculator is for demonstration purposes only and may not reflect actual numbers for your mortgage.

== Installation ==

Install this plugin just like any other…

1. Upload the plugin folder `responsive-mortgage-calculator.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

= Widget Installation =

1. Access the `Widgets` page under the `Appearance` menu.
1. Drag the `Responsive Mortgage Calculator` into the Widget display area of your choice.
1. If you want, change the title and save it.

= Shortcode Usage =

You can insert the mortgage calculator into a page or post using the short code [mortgagecalculator] or [rmc].

= Set the Compounding Period =

The mortgage calculator has a default compounding period that is semi-annual. Visit the settings page under `Settings` > `Resp Mortgage Calculator` and change the setting ‘Compounding period for the mortgage interest’ to the correct period for your region.

== Frequently Asked Questions ==

= On submission, the page just reloads and the mortgage calculator shows no results =

This is likely a problem with the JavaScript file not being loaded, and seems to occur when using the shortcode with a visual editor plugin, like Visual Composer. This will also prevent the CSS file from loading.

First, try editing the page with the shortcode. Switch from the visual editor to plain text editing, add the shortcode in plain text, and save it. Check the mortgage calculator to see if it working. If that didn’t work, you can…

Solve this problem by manually loading the scripts and styles. In the plugin folder, open the `Extras` folder, then open the file called `manually_load_scripts.php`. Copy the contents to your theme’s functions.php file. Even better, copy it to the functions.php file of a child theme. Change the page slug in the `if` conditional from `your-page-slug-here` to the actual page slug you’re using for the shortcode. Test it.

= The calculated payment is off by a few dollars =

The mortgage calculator calculates interest monthly by default, but you can change this on the settings page. Go to `Settings` > `Resp Mortgage Calculator` and change the setting ‘Compounding Period’ to the correct period for your region.

= When I put two mortgage calculators on the same page, it doesn't work =

This is a known issue. In the current version of the calculator, there can only be one instance of Responsive Mortgage Calculator on a page. This may change at some point in the future.

== Screenshots ==

1. The mortgage calculator fits in the widgets area of your theme or on any page and blends right in. The form inputs are styled simply and unobtrusively. The ‘Calculate’ button takes it’s styling from your theme.
2. The mortgage payment amount is displayed below the ‘Calculate’ button. The circled chart icon is clickable.
3. A longer summary of the mortgage details can be shown by changing appropriate setting.

== Changelog ==

= 2.4.2 =
* Small fix to support older PHP versions

= 2.3.12 =
* Added currency support in the graph modal
* Added jQuery via wp_enqueue_script for graph modal

= 2.3.11 =
* Fixed bug with a label of user-selected Fixed Payment Period

= 2.3.10 =
* Small JS bug fixed

= 2.3.9 =
* Modified chart icon a little

= 2.3.8 =
* Changed default calculation popup to: Show the monthly payment, textual summary and chart icon

= 2.3.7 =
* Tested with WordPress 5.6.1

= 2.3.6 =

* Small fixes

= 2.3.5 =

* Tested with the new version of WordPress

= 2.3.4 =

* Added default values for Total Amount, Down Payment, Interest Rate and Amortization Periods
* Added beautiful summary popup with chart and amortization schedule
* Changed summary display options: show popup immediately; show monthly payment along with popup toggle icon; show monthly payment, textual summary and popup toggle icon

= 2.3.3 =

* Fixed a bug that caused error messages to display if no post object was loaded

= 2.3.2 =

* Fixed a bug where localization file was not found if calculator data was submitted to the server
* Updated widget constructor call to parent constructor since call to WP_Widget constructor is deprecated

= 2.3.1 =

* Added shortcode attributes to set input values. Various permutations of the attributes are available, with some listed below
* Total amount value can now be set using the shortcode attribute total_amount_value or tav
* Down payment value can now be set using the shortcode attribute down_payment_value or dpv
* Interest rate value can now be set using the shortcode attribute  interest_rate_value or irv
* Amortization period value can now be set using the shortcode attribute amortization_period_value or apv

= 2.3.0 =

* Added options to set which payment periods can be selected
* Added option for semi-annual payment period

= 2.2.9 =

* Fixed a bug where the native select box arrow was displaying in Firefox even when fancy select styling was enabled
* Modified the arrow on the fancy select box to be a little smaller with no background
* Added an option for a minimum total amount

= 2.2.8 =

* Added options for fixed yearly and quarterly payments
* Fixed a bug that was causing the payment period to always be monthly of the payment period is hidden

= 2.2.7 =

* Added fixed height to inputs and select fancy styling.
* Added a setting to allow for 0% interest
* Fixed an error on server side calculations that caused total with down payment and interest to be miscalculated

= 2.2.6 =

* Removed dependency on the jQuery.isNumeric method.
* Fixed bugs in the server side calculation processor.

= 2.2.5 =

* Merry Christmas! Responsive Mortgage Calculator Pro is now available! Use coupon code XMAS2015 for 20% off till New Years.
* Removed dead code
* Amortization period is now unlimited

= 2.2.4 =

* Detailed summary now has slide animation when toggle is clicked
* Works over SSL
* Now performs a basic calculation on the server and returns a payment result if no JavaScript is available
* Hopefully solved the nested shortcode issue that prevents JavaScript from loading with Visual Composer. Fingers crossed.
* Default currency format added to JavaScript in case you upgrade from a version without having set the currency format in the settings page

= 2.2.3 =

* Changed default compounding period to monthly
* You can now set whether amortization period is in years or months

= 2.2.2 =

* Fixed a JavaScript bug where the {code} tag was being replace by "null" if there was no currency code

= 2.2.1 =

* Added a number formatting setting to the options page for international currency systems. You can now choose the thousands separator, the decimal delimiter, and the number of decimal places. Also includes the Indian System.
* Interest Rate field now corrects for poorly formatted input
* Options page descriptions are improved and labels are shorter
* Shortcode detection includes form id detection, in case the post content is preprocessed by Visual Composer or other visual editing tools
* JavaScript currency code validation imporved

= 2.2.0 =

* This version marks completion of changes to currency formatting. If you skipped update 2.1.9/1, please read those updates.
* Completed changes to the .pot, and FR and ES .po and .mo files to include changes to the way that currencies are now formatted and displayed. These changes impact the summary section only
* Currency format has been removed from the translation files and placed into the database via the options page. (see 2.1.9 updates)

= 2.1.91 =

* Updated JavaScript version number to override caching of old JS file

= 2.1.9 =

* This update requires resaving the options or reinstalling to initialize the new currency format and new result settings (see changes below)
* Currency is now entirely customizable (replaced the select box with a text input)
* You can now arrange your currency results using the currency symbol, amount and ISO code. See the options page to create your currency format
* You can now choose to hide the total with interest and downpayment in the results summary. See the options page
* Moved settings required by Javascript from hidden inputs to localization object
* Minor changes to localizations. New .pot, .po, .mo files pending. Please await changes to FR and ES language files if you are using them.

= 2.1.8 =

* Added Peso currency symbol

= 2.1.7 =

* Security update: added security check to all necessary PHP files to prevent unwanted access to support files
* Corrected a class that prevented the ‘i’ icon from being correctly positioned

= 2.1.6 =

* Total Amount and Down Payment fields now accept commas and correct for poorly formatted input
* Provided a helper php file to manually load scripts for people using visual editor plugins.

= 2.1.5 =

* Changed script loading so that JS and CSS are always registered. JS and CSS can now be enqueued from your own scripts by calling wp_enqueue_script(‘lidd_mc’) and wp_enqueue_style(‘lidd_mc’)… in case you need to manually load them.

= 2.1.4 =

* Added an option to set a default interest rate
* Accented characters can now be used for field labels

= 2.1.3 =

* Script/style loading moved to ‘wp’ action and combined

= 2.1.2 =

* Completed internationalization
* Added front end French and Spanish translations - thanks to designium

= 2.1.1 =

* Added missing files

= 2.1.0 =

* Added option for setting a fixed payment period
* Result now shows the payment period
* Added ability to set input and submit button labels using shortcode attributes
* Beginning internationalization - still needs PO and MO files

= 2.0.3 =

* Added generic currency symbol
* Added input for ISO currency code on the options page

= 2.0.2 =

* Fixed an error where the JS and CSS weren’t loading with the [rmc] shortcode

= 2.0.1 =

* Quick and dirty bug fixes.

= 2.0.0 =

* Massive code rewrite.
* Added options page.
* Widget users may need to reactivate the widget.

= 1.1.3 =

* Minor CSS to remove margins from the Payment Period select box and prevent the surrounding span from resizing.

= 1.1.2 =

* Fixed a bug that caused the form to display at the top of the page instead of where the shortcode was placed.

= 1.1.1 =

* Made sure that JS and CSS are being included when the shortcode is used…
* Fixed a minor UI bug where the arrow background on the select box was too short.

= 1.1 =

* Added shortcodes
