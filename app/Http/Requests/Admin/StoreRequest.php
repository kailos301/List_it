<?php

namespace App\Http\Requests\Admin;

use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'image' => [
        'required',
        new ImageMimeTypeRule()
      ],
      'role_id' => 'required',
      'username' => 'required|max:255|unique:admins',
      'email' => 'required|email:rfc,dns|unique:admins',
      'first_name' => 'required',
      'last_name' => 'required',
      'password' => 'required|confirmed',
      'password_confirmation' => 'required'
    ];
  }

  public function messages()
  {
    return [
      'role_id.required' => 'The role field is required.',
      'password.confirmed' => 'Password confirmation does not match.',
      'password_confirmation.required' => 'The confirm password field is required.'
    ];
  }
}
