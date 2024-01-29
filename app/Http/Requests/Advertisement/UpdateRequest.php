<?php

namespace App\Http\Requests\Advertisement;

use App\Models\Advertisement;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;

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
    $ad = Advertisement::find($this->id);

    $array = [
      'ad_type' => 'required',
      'resolution_type' => 'required|numeric'
    ];

    if (($this->ad_type == 'banner') && is_null($ad->image) && !$this->has('image')) {
      $array['image'] = 'required';
    }
    if ($this->hasFile('image')) {
      $array['image'] = new ImageMimeTypeRule();
    }

    $array['url'] = [
      'required_if:ad_type,banner',
      $this->filled('url') ? 'url' : ''
    ];

    $array['slot'] = 'required_if:ad_type,adsense';

    return $array;
  }

  public function messages()
  {
    return [
      'image.required' => 'The image field is required when ad type is banner.'
    ];
  }
}
