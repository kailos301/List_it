<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailFromAdminRequest extends FormRequest
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
      'smtp_status' => 'required',
      'smtp_host' => 'required',
      'smtp_port' => 'required|numeric',
      'encryption' => 'required',
      'smtp_username' => 'required',
      'smtp_password' => 'required',
      'from_mail' => 'required',
      'from_name' => 'required'
    ];
  }

  public function messages()
  {
    return [
      'from_mail.required' => 'The mail address field is required.'
    ];
  }
}
