<?php

namespace App\Http\Requests\Testimonial;

use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

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
    $themeInfo = DB::table('basic_settings')->select('theme_version')->first();

    return [
      'language_id' => 'required',
      'image' => $themeInfo->theme_version == 2 ? ['required', new ImageMimeTypeRule()] : '',
      'name' => 'required|max:255',
      'occupation' => 'required|max:255',
      'comment' => 'required'
    ];
  }

  public function messages()
  {
    return [
      'language_id.required' => 'The language field is required.'
    ];
  }
}
