<?php

namespace App\Http\Requests\Page;

use App\Models\Language;
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
    $ruleArray = [
      'status' => 'required'
    ];

    $languages = Language::all();

    foreach ($languages as $language) {
      $ruleArray[$language->code . '_title'] = [
        'required',
        'max:255',
        Rule::unique('page_contents', 'title')->ignore($this->id, 'page_id')
      ];

      $ruleArray[$language->code . '_content'] = 'min:15';
    }

    return $ruleArray;
  }

  /**
   * Get the validation messages that apply to the request.
   *
   * @return array
   */
  public function messages()
  {
    $messageArray = [];

    $languages = Language::all();

    foreach ($languages as $language) {
      $messageArray[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language.';

      $messageArray[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';

      $messageArray[$language->code . '_title.unique'] = 'The title field must be unique for ' . $language->name . ' language.';

      $messageArray[$language->code . '_content.min'] = 'The content field atleast have 15 characters for ' . $language->name . ' language.';
    }

    return $messageArray;
  }
}
