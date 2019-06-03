<?php

class SLN_Helper_FacebookLogin
{
    const API_URL = 'https://graph.facebook.com/v2.8';

    public static function getUserIDByAccessToken($accessToken, $createCustomerFields = false) {

	$fbUser = static::getFBUserInfo($accessToken);

	$userID = SLN_Wrapper_Customer::getCustomerIdByFacebookID($fbUser['id']);

	if ( $userID ) {
	    return $userID;
	}

	$user = get_user_by('email', $fbUser['email']);

	if ( $user ) {
	    update_user_meta($user->ID, '_sln_fb_id', $fbUser['id']);
	    return $user->ID;
	}

	return static::createWpUser($fbUser, $createCustomerFields);
    }

    protected static function getFBUserInfo($accessToken) {

	if ( ! $accessToken ) {
	    throw new \Exception(__('Access token not found', 'salon-booking-system'));
	}

	$response = wp_remote_get(
	    add_query_arg(
		array(
		    'fields'	    => 'id,name,email',
		    'access_token'  => $accessToken,
		),
		static::API_URL . '/me'
	    )
	);

	if ( is_wp_error( $response ) ) {
	    throw new Exception($response->get_error_message());
	}

	$data = json_decode(wp_remote_retrieve_body( $response ), true);

	if ( isset( $data['error'] ) ) {
	    throw new Exception($data['error']['message']);
	}

	$tmp       = explode(' ', $data['name']);
	$lastName  = array_pop($tmp);
	$firstName = implode(' ', $tmp);

	return array(
	    'id'	=> $data['id'],
	    'first_name'=> $firstName,
	    'last_name'	=> $lastName,
	    'email'	=> $data['email'],
	    'phone'	=> '',
	    'address'	=> '',
	);
    }

    protected static function createWpUser($fbUser, $createCustomerFields = false) {

	$userID = wp_create_user("fb_{$fbUser['id']}", wp_generate_password(), $fbUser['email']);

	if ( is_wp_error($userID) ) {
	    throw new Exception($userID->get_error_message());
	}

	wp_update_user(array(
	    'ID'           => $userID,
	    'display_name' => $fbUser['first_name'] . ' ' . $fbUser['last_name'],
	    'nickname'     => $fbUser['first_name'] . ' ' . $fbUser['last_name'],
	    'first_name'   => $fbUser['first_name'],
	    'last_name'    => $fbUser['last_name'],
	    'role'         => SLN_Plugin::USER_ROLE_CUSTOMER,
	));

	add_user_meta($userID, '_sln_fb_id', $fbUser['id']);
	add_user_meta($userID, '_sln_phone', $fbUser['phone']);
	add_user_meta($userID, '_sln_address', $fbUser['address']);

	if ( $createCustomerFields ) {

	    $customer_fields = array_keys( SLN_Enum_CheckoutFields::toArray('customer'));

	    foreach($customer_fields as $k){
		add_user_meta($userID, '_sln_'.$k, '');
	    }
	}

	wp_new_user_notification($userID, null, 'both');

	return $userID;
    }

}
