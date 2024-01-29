<?php

namespace App\Http\Requests\Popup;

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
      'language_id' => 'required',
      'type' => 'required',
      'image' => [
        'required',
        new ImageMimeTypeRule()
      ],
      'name' => 'required|max:255',
      'background_color' => 'required_if:type,2|required_if:type,3|required_if:type,7',
      'background_color_opacity' => 'required_if:type,2|required_if:type,3|numeric|between:0,1',
      'title' => 'required_if:type,2|required_if:type,3|required_if:type,4|required_if:type,5|required_if:type,6|required_if:type,7|max:255',
      'text' => 'required_if:type,2|required_if:type,3|required_if:type,4|required_if:type,5|required_if:type,6|required_if:type,7',
      'button_text' => 'required_if:type,2|required_if:type,3|required_if:type,4|required_if:type,5|required_if:type,6|required_if:type,7|max:255',
      'button_color' => 'required_if:type,2|required_if:type,3|required_if:type,4|required_if:type,5|required_if:type,6|required_if:type,7',
      'button_url' => 'required_if:type,2|required_if:type,4|required_if:type,6|required_if:type,7',
      'end_date' => 'required_if:type,6|required_if:type,7|date',
      'end_time' => 'required_if:type,6|required_if:type,7|date_format:h:i A',
      'delay' => 'required|numeric',
      'serial_number' => 'required|numeric'
    ];
  }

  public function messages()
  {
    return [
      'language_id.required' => 'The language field is required.'
    ];
  }
}
