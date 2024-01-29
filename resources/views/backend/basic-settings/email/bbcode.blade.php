<div class="col-lg-5">
  <table class="table table-striped border">
    <thead>
      <tr>
        <th scope="col">{{ __('BB Code') }}</th>
        <th scope="col">{{ __('Meaning') }}</th>
      </tr>
    </thead>
    <tbody>
      @if ($templateInfo->mail_type == 'verify_email')
        <tr>
          <td>{username}</td>
          <td scope="row">{{ __('Username of User') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'verify_email')
        <tr>
          <td>{verification_link}</td>
          <td scope="row">{{ __('Email Verification Link') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'reset_password' || $templateInfo->mail_type == 'product_order')
        <tr>
          <td>{customer_name}</td>
          <td scope="row">{{ __('Name of The Customer') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'balance_add' || $templateInfo->mail_type == 'balance_subtract')
        <tr>
          <td>{amount}</td>
          <td scope="row">{{ __('Balance add/substract  amount') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'reset_password')
        <tr>
          <td>{password_reset_link}</td>
          <td scope="row">{{ __('Password Reset Link') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'product_order')
        <tr>
          <td>{order_number}</td>
          <td scope="row">{{ __('Order Number') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'product_order')
        <tr>
          <td>{order_link}</td>
          <td scope="row">{{ __('Link to View Order Details') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type != 'verify_email' ||
              $templateInfo->mail_type != 'reset_password' ||
              $templateInfo->mail_type != 'product_order')
        <tr>
          <td>{username}</td>
          <td scope="row">{{ __('Username of Vendor') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'admin_changed_current_package' ||
              $templateInfo->mail_type == 'admin_changed_next_package' ||
              $templateInfo->mail_type == 'admin_removed_current_package')
        <tr>
          <td>{replaced_package}</td>
          <td scope="row">{{ __('Replace Package Name') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'admin_changed_current_package' ||
              $templateInfo->mail_type == 'admin_added_current_package' ||
              $templateInfo->mail_type == 'admin_changed_next_package' ||
              $templateInfo->mail_type == 'admin_added_next_package' ||
              $templateInfo->mail_type == 'admin_removed_current_package' ||
              $templateInfo->mail_type == 'admin_removed_next_package' ||
              $templateInfo->mail_type == 'membership_extend' ||
              $templateInfo->mail_type == 'registration_with_premium_package' ||
              $templateInfo->mail_type == 'registration_with_trial_package' ||
              $templateInfo->mail_type == 'registration_with_free_package' ||
              $templateInfo->mail_type == 'payment_accepted_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'payment_accepted_for_registration_offline_gateway' ||
              $templateInfo->mail_type == 'payment_rejected_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'payment_rejected_for_registration_offline_gateway')
        <tr>
          <td>{package_title}</td>
          <td scope="row">{{ __('Package Name') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'admin_changed_current_package' ||
              $templateInfo->mail_type == 'admin_added_current_package' ||
              $templateInfo->mail_type == 'admin_added_next_package' ||
              $templateInfo->mail_type == 'membership_extend' ||
              $templateInfo->mail_type == 'registration_with_premium_package' ||
              $templateInfo->mail_type == 'registration_with_trial_package' ||
              $templateInfo->mail_type == 'registration_with_free_package' ||
              $templateInfo->mail_type == 'payment_accepted_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'payment_accepted_for_registration_offline_gateway' ||
              $templateInfo->mail_type == 'payment_rejected_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'payment_rejected_for_registration_offline_gateway')
        <tr>
          <td>{package_price}</td>
          <td scope="row">{{ __('Price of Package') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'registration_with_premium_package')
        <tr>
          <td>{discount}</td>
          <td scope="row">{{ __('Discount Amount') }}</td>
        </tr>
      @endif
      @if ($templateInfo->mail_type == 'registration_with_premium_package')
        <tr>
          <td>{total}</td>
          <td scope="row">{{ __('Total Paid Amount') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'admin_changed_current_package' ||
              $templateInfo->mail_type == 'admin_added_current_package' ||
              $templateInfo->mail_type == 'admin_changed_next_package' ||
              $templateInfo->mail_type == 'admin_added_next_package' ||
              $templateInfo->mail_type == 'membership_extend' ||
              $templateInfo->mail_type == 'registration_with_premium_package' ||
              $templateInfo->mail_type == 'registration_with_trial_package' ||
              $templateInfo->mail_type == 'registration_with_free_package' ||
              $templateInfo->mail_type == 'payment_accepted_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'payment_accepted_for_registration_offline_gateway')
        <tr>
          <td>{activation_date}</td>
          <td scope="row">{{ __('Package activation date') }}</td>
        </tr>
      @endif
      @if (
          $templateInfo->mail_type == 'admin_changed_current_package' ||
              $templateInfo->mail_type == 'admin_added_current_package' ||
              $templateInfo->mail_type == 'admin_changed_next_package' ||
              $templateInfo->mail_type == 'admin_added_next_package' ||
              $templateInfo->mail_type == 'membership_extend' ||
              $templateInfo->mail_type == 'registration_with_premium_package' ||
              $templateInfo->mail_type == 'registration_with_trial_package' ||
              $templateInfo->mail_type == 'registration_with_free_package' ||
              $templateInfo->mail_type == 'payment_accepted_for_membership_extension_offline_gateway' ||
              $templateInfo->mail_type == 'payment_accepted_for_registration_offline_gateway')
        <tr>
          <td>{expire_date}</td>
          <td scope="row">{{ __('Package expire date') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'membership_expiry_reminder')
        <tr>
          <td>{last_day_of_membership}</td>
          <td scope="row">{{ __('Package expire last date') }}</td>
        </tr>
      @endif
      @if ($templateInfo->mail_type == 'membership_expiry_reminder' || $templateInfo->mail_type == 'membership_expired')
        <tr>
          <td>{login_link}</td>
          <td scope="row">{{ __('Login Url') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'inquiry_about_car')
        <tr>
          <td>{car_name}</td>
          <td scope="row">{{ __('Name of car') }}</td>
        </tr>
        <tr>
          <td>{enquirer_name}</td>
          <td scope="row">{{ __('Name of enquirer') }}</td>
        </tr>
        <tr>
          <td>{enquirer_email}</td>
          <td scope="row">{{ __('Email address of enquirer') }}</td>
        </tr>
        <tr>
          <td>{enquirer_phone}</td>
          <td scope="row">{{ __('Phone number of enquirer') }}</td>
        </tr>
        <tr>
          <td>{enquirer_message}</td>
          <td scope="row">{{ __('Message of enquirer') }}</td>
        </tr>
      @endif

      <tr>
        <td>{website_title}</td>
        <td scope="row">{{ __('Website Title') }}</td>
      </tr>
    </tbody>
  </table>
</div>
