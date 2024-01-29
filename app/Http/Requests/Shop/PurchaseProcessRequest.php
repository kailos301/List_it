<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseProcessRequest extends FormRequest
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
      'billing_name' => 'required',
      'billing_email' => 'required|email:rfc,dns',
      'billing_phone' => 'required',
      'billing_city' => 'required',
      'billing_country' => 'required',
      'billing_address' => 'required',

      'shipping_name' => 'required_if:checkbox,1',
      'shipping_phone' => 'required_if:checkbox,1',
      'shipping_email' => 'required_if:checkbox,1|email:rfc,dns',
      'shipping_city' => 'required_if:checkbox,1',
      'shipping_country' => 'required_if:checkbox,1',
      'shipping_address' => 'required_if:checkbox,1'
    ];
  }

  /**
   * Get the validation messages that apply to the request.
   *
   * @return array
   */
  public function messages()
  {
    return [
      'billing_name.required' => 'The first name field is required.',
      'billing_email.required' => 'The email field is required.',
      'billing_phone.required' => 'The phone number field is required.',
      'billing_address.required' => 'The address field is required.',
      'billing_city.required' => 'The city field is required.',
      'billing_country.required' => 'The country field is required.',
      'shipping_name.required_if' => 'The first name field is required.',
      'shipping_email.required_if' => 'The email field is required.',
      'shipping_phone.required_if' => 'The phone number field is required.',
      'shipping_address.required_if' => 'The address field is required.',
      'shipping_city.required_if' => 'The city field is required.',
      'shipping_country.required_if' => 'The country field is required.'
    ];
  }
}
