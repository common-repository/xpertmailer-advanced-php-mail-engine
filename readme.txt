=== XPertMailer ===
Contributors: Tanase Laurentiu Iulian
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=contact%40xpertmailer%2ecom&item_name=XPertMailer&buyer_credit_promo_code=&buyer_credit_product_category=&buyer_credit_shipping_method=&buyer_credit_user_address_change=&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: mail, smtp, wp_mail, mailer, phpmailer
Requires at least: 2.3
Stable tag: 0.1

Send mail in 5 different ways: PHP mail(), MX Zone, SMTP Relay, Command Line or POP before SMTP, also you can use your Gmail account to send mails.

== Description ==

After almost 2 years of developing a mailing solution (PHP mail class) that can be incorporated in virtually any existing script, www.xpertmailer.com is proud to present you the WordPress plugin of XPM4 (XpertMailer v4).
Knowing the spread of WordPress and the adoption of it as the best blogging solution nowdays, we thought everyone needs a very versatile and highly configurable mail solution for WordPress.
Therefore WP-XPM brings you _ALOT_ of features designed to help you in your mailing quest. A quick overview of WP-XPM shows up some neat options that you can play with like:
5 methods of mail delivery ( you even knew there are so many?:) )

* PHP mail() function
* MX Zone, without mail server support
* SMTP Relay, with optional authentication
* Command Line, use an "Unix SendMail like" mail program
* POP before SMTP, Mail Proxy method

* Auto failback option to MX Zone delivery if the selected delivery method doesnt work
* Extended logging options
* Gmail SMTP pre-defined option
* Mail delivery test ( test before you actually send e-mails )

== Installation ==

1. Upload the "xpertmailer-advanced-php-mail-engine" folder to the "wp-content\plugins" directory
2. Activate the plugin titled "XPertMailer - Advanced PHP Mail Engine" through the "Plugins" menu in WordPress
3. Access the "Options" Tab in your Admin Panel and select the "XPertMailer" SubMenu Item.

== Frequently Asked Questions ==

= My plugin still sends mail via the mail() function =

1. Open the problem Plugin in a text editor.
2. Do a search for the function `mail(` and replace it with `wp_mail(`.
3. Save and upload the revised plugin and it should work now.

== Screenshots ==

1. Screenshot of the Options > XPertMailer panel.

== Support Questions ==

XPertMailer Forum:
<http://www.xpertmailer.com/forum/>
