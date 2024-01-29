<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MatchOldPasswordRule implements Rule
{
  private $personType;

  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct($role)
  {
    // here, $role variable defines whether it is admin or user
    $this->personType = $role;
  }

  /**
   * Determine if the validation rule passes.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    if ($this->personType == 'admin') {
      $authAdminPass = Auth::guard('admin')->user()->password;

      return Hash::check($value, $authAdminPass);
    } else if ($this->personType == 'user') {
      $authUserPass = Auth::guard('web')->user()->password;

      return Hash::check($value, $authUserPass);
    } else if ($this->personType == 'vendor') {
      $authUserPass = Auth::guard('vendor')->user()->password;

      return Hash::check($value, $authUserPass);
    }
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return 'Your provided current password does not match!';
  }
}
