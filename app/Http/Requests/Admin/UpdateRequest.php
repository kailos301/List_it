<?php

namespace App\Http\Requests\Admin;

use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
      'image' => $this->hasFile('image') ? new ImageMimeTypeRule() : '',
      'role_id' => 'required',
      'username' => [
        'required',
        'max:255',
        Rule::unique('admins')->ignore($this->id)
      ],
      'email' => [
        'required',
        'email:rfc,dns',
        Rule::unique('admins')->ignore($this->id)
      ],
      'first_name' => 'required',
      'last_name' => 'required'
    ];
  }
}
