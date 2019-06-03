<?php
/**
* Welcome Page View
*
* @since 1.0.0
* @package salon-booking-system
*/
if ( ! defined( 'WPINC' ) ) {
die;
}
?>
<div class="wrap about-wrap">
    <h1><?php _e( 'Salon Booking System', 'salon-booking-system' ); ?></h1>
    <div class="about-text">
        <h4><?php _e( "Welcome!<br /> follow these simple steps to start your new booking expericence.", 'salon-booking-system' ); ?></h4>
    </div>
    <div class="wp-badge welcome__logo"></div>
    <div class="feature-section two-col">
        <div class="col">
            <h3><?php _e( 'Step 1 - General settings', 'salon-booking-system' ); ?></h3>
            <ul>
                <li><?php _e( 'Go to <span>Salon > Settings > General</span> tab and fill out these minimum required options:&ensp;', 'salon-booking-system' ); ?><a href="admin.php?page=salon-settings&tab=general"><?php _e( 'BRING ME THERE', 'salon-booking-system' ); ?></a></li>
                <li><?php _e( 'Fill out your salon information', 'salon-booking-system' ); ?></li>
                <li><?php _e( 'Scroll down the page and setup the “Booking notes”', 'salon-booking-system' ); ?></li>
                <li><?php _e( 'Then setup the “Assistant selection” options', 'salon-booking-system' ); ?></li>
            </ul>
            <?php _e( 'The rest of the options are optional', 'salon-booking-system' ); ?>
            <br>
            <br>
            <h3><?php _e( 'Step 2 - Booking rules', 'salon-booking-system' ); ?></h3>
            <ul>
                <li><?php _e( 'Go to <span>Salon > Settings > Booking rules</span> tab&ensp;', 'salon-booking-system' ); ?><a href="admin.php?page=salon-settings&tab=booking"><?php _e( 'BRING ME THERE', 'salon-booking-system' ); ?></a></li>
                <li><?php _e( 'Setup “Customers per session” option, how many customers can be attended at the same times', 'salon-booking-system' ); ?></li>
                <li><?php _e( 'Setup “Session average duration” option, that changes the minimum hour fraction used by customers to select the timing of their reservation and for you to define the duration of your services', 'salon-booking-system' ); ?></li>
                <li><?php _e( '“Change order” option, can be enabled if you want the booking process starting from the selection of the services, then the assistants, and then the date/time', 'salon-booking-system' ); ?></li>
                <li><?php _e( '“Setup “Online booking available days”, this option is very important as it should represents your real weekly time table', 'salon-booking-system' ); ?></li>
            </ul>
            <?php _e( 'The rest of the options are optional', 'salon-booking-system' ); ?>
        </div>
        <div class="col">
            <h3><?php _e( 'Step 3 - Services', 'salon-booking-system' ); ?></h3>
            <ul>
                <li><?php _e( 'Go to <span>Salon > Services</span> section and create as many services as you need&ensp;', 'salon-booking-system' ); ?><a href="edit.php?post_type=sln_service"><?php _e( 'BRING ME THERE', 'salon-booking-system' ); ?></a></li>
            </ul>
            <h3><?php _e( 'Step 4 - Assistants', 'salon-booking-system' ); ?></h3>
            <ul>
                <li><?php _e( 'Got to <span>Salon > Assistants</span> section and create as many assistants as you need just in case you are not working alone&ensp;', 'salon-booking-system' ); ?><a href="edit.php?post_type=sln_attendant"><?php _e( 'BRING ME THERE', 'salon-booking-system' ); ?></a></li>
            </ul>
                <h3><?php _e( 'Step 5 - Testing', 'salon-booking-system' ); ?></h3>
                <ul>
                    <li><?php _e( 'Open the <span>Booking</span> page on front-end and make some test with the booking form and verify that the booking process is correct', 'salon-booking-system' ); ?></li>
                </ul>

                <h3><?php _e( 'More documentation?', 'salon-booking-system' ); ?></h3>
                <ul>
                    <li><?php _e( 'You can consult the <span>Salon > Settings > Support</span> tab where you can find all the info you need or get in contact with us&ensp;', 'salon-booking-system' ); ?><a href="admin.php?page=salon-settings&tab=documentation"><?php _e( 'BRING ME THERE', 'salon-booking-system' ); ?></a></li>
                </ul>
            </div>
        </div>

    </div>
