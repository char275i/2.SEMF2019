<?php

class SLN_Shortcode_Salon_DetailsStep extends SLN_Shortcode_Salon_AbstractUserStep
{
    protected function dispatchForm()
    {
        global $current_user;
	    if (isset($_POST['fb_access_token'])) {
		    $values = $this->dispatchAuthFB($_POST['fb_access_token']);
		    if ($this->hasErrors()) {
			    return false;
		    }
	    } elseif (isset($_POST['login_name'])) {
            $ret = $this->dispatchAuth(sanitize_text_field(wp_unslash($_POST['login_name'])),sanitize_text_field($_POST['login_password']));
            if (!$ret) {
                return false;
            }

            $values  = array(
                'firstname' => '',
                'lastname'  => '',
                'email'     => '',
                'phone'     => '',
                'address'   => '',
            );
            if (!SLN_Enum_CheckoutFields::isHidden('firstname')) {
                $values['firstname'] = $current_user->user_firstname;
            }
            if (!SLN_Enum_CheckoutFields::isHidden('lastname')) {
                $values['lastname'] = $current_user->user_lastname;
            }
            if (!SLN_Enum_CheckoutFields::isHidden('email')) {
                $values['email'] = $current_user->user_email;
            }
            if (!SLN_Enum_CheckoutFields::isHidden('phone')) {
                $values['phone'] = get_user_meta($current_user->ID, '_sln_phone', true);
            }
            if (!SLN_Enum_CheckoutFields::isHidden('address')) {
                $values['address'] = get_user_meta($current_user->ID, '_sln_address', true);
            }
            $additional_fields = array_keys( SLN_Enum_CheckoutFields::toArray('additional'));
            if($additional_fields){
                foreach ($additional_fields as $field ) {
                    if (!SLN_Enum_CheckoutFields::isHidden($field))
                    $values[$field] = get_user_meta($current_user->ID, '_sln_'.$field, true);
                }
            }
            $this->bindValues($values);
            $this->validate($values);
            if ($this->getErrors()) {
                $this->bindValues($values);
                return false;
            }else{
                $_SESSION['sln_sms_dontcheck'] = true;
            }
        } else {
            $values = $_POST['sln'];
            $this->bindValues($values);
            if (!is_user_logged_in()) {
                $this->validate($values);
                if ($this->getErrors()) {
                    return false;
                }

                if ($this->getPlugin()->getSettings()->get('enabled_force_guest_checkout') || $this->getPlugin()->getSettings()->get('enabled_guest_checkout') && isset($values['no_user_account']) && $values['no_user_account']) {
                    $_SESSION['sln_detail_step'] = $values;
                } else {
                    if (email_exists($values['email'])) {
                        $this->addError(__('E-mail exists', 'salon-booking-system'));
                        if ($this->getErrors()) {
                            return false;
                        }
                    }

                    if ($values['password'] != $values['password_confirm']) {
                        $this->addError(__('Passwords are different', 'salon-booking-system'));
                        if ($this->getErrors()) {
                            return false;
                        }
                    }

                    if(!$this->getShortcode()->needSms()) {
                        $this->successRegistration($values);
                    }else{
                        $_SESSION['sln_detail_step'] = $values;
                    }
                }
            }else{
                wp_update_user(
                    array('ID' => $current_user->ID, 'first_name' => $values['firstname'], 'last_name' => $values['lastname'])
                );
                $user_meta_fields = array_merge(array('phone', 'address'), array_keys( SLN_Enum_CheckoutFields::toArray('customer')));
                foreach($user_meta_fields as $k){
                    if(isset($values[$k])){
                       update_user_meta($current_user->ID, '_sln_'.$k, $values[$k]);
                    }
                }
            }
        }
        $this->bindValues($values);

        return true;
    }

    private function dispatchAuthFB($accessToken) {

	try {

	    $userID = SLN_Helper_FacebookLogin::getUserIDByAccessToken($accessToken, true);

	    $user = get_user_by('id', $userID);

	    wp_set_auth_cookie($userID);
	    wp_set_current_user($userID);

	    return array(
		'fb_id'     => get_user_meta($userID, '_sln_fb_id', true),
		'firstname' => $user->first_name,
		'lastname'  => $user->last_name,
		'email'     => $user->user_email,
		'phone'     => '',
		'address'   => '',
	    );

	} catch (\Exception $ex) {
	    $this->addError($ex->getMessage());
	}

	return array();
    }

    private function validate($values){
        if (SLN_Enum_CheckoutFields::isRequired('firstname') && empty($values['firstname'])) {
            $this->addError(__('First name can\'t be empty', 'salon-booking-system'));
        }
        if (SLN_Enum_CheckoutFields::isRequired('lastname') && empty($values['lastname'])) {
            $this->addError(__('Last name can\'t be empty', 'salon-booking-system'));
        }
        if (SLN_Enum_CheckoutFields::isRequired('phone') && empty($values['phone'])) {
            $this->addError(__('Mobile phone can\'t be empty', 'salon-booking-system'));
        }
        if (SLN_Enum_CheckoutFields::isRequired('address') && empty($values['address'])) {
            $this->addError(__('Address can\'t be empty', 'salon-booking-system'));
        }
        if (SLN_Enum_CheckoutFields::isRequired('email')) {
            if (empty($values['email'])) {
                $this->addError(__('e-mail can\'t be empty', 'salon-booking-system'));
            }
            if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
                $this->addError(__('e-mail is not valid', 'salon-booking-system'));
            }
        }
        $fields = SLN_Enum_CheckoutFields::toArray('additional');
        foreach ($fields as $field => $label) {
            if (SLN_Enum_CheckoutFields::isRequired($field) && empty($values[$field])){
                $this->addError(__($label.' can\'t be empty', 'salon-booking-system'));
            }
        }
    }
}
