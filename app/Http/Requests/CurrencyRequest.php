<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
      'base_currency_symbol' => 'required',
      'base_currency_symbol_position' => 'required',
      'base_currency_text' => 'required',
      'base_currency_text_position' => 'required',
      'base_currency_rate' => 'required|numeric'
    ];
  }
}
