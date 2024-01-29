<?php

namespace App\Http\Requests\Car;

use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;

class CarStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'slider_images' => 'required',
            /*'feature_image' => [
                'required',
                new ImageMimeTypeRule()
            ],*/
            'price' => 'required',
            //'speed' => 'required',
           // 'year' => 'required',
            //'mileage' => 'required',
            //'latitude' => 'required',
            //'longitude' => 'required',

        ];

        $languages = Language::all();


        foreach ($languages as $language) {
            $rules[$language->code . '_title'] = 'required|max:255';
            //$rules[$language->code . '_address'] = 'required';

            $rules[$language->code . '_category_id'] = 'required';
           // $rules[$language->code . '_car_condition_id'] = 'required';
          //  $rules[$language->code . '_brand_id'] = 'required';
          //  $rules[$language->code . '_car_model_id'] = 'required';
           // $rules[$language->code . '_fuel_type_id'] = 'required';
          //  $rules[$language->code . '_transmission_type_id'] = 'required';
            $rules[$language->code . '_description'] = 'required|min:15';
        }

        return $rules;
    }

    public function messages()
    {
        $messageArray = [];

        $languages = Language::all();

        foreach ($languages as $language) {
            $messageArray[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language';
            $messageArray[$language->code . '_address.required'] = 'The address field is required for ' . $language->name . ' language';

            $messageArray[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language';

            $messageArray[$language->code . '_category_id.required'] = 'The category field is required for ' . $language->name . ' language';

            $messageArray[$language->code . '_car_condition_id.required'] = 'The condition field is required for ' . $language->name . ' language';
            $messageArray[$language->code . '_brand_id.required'] = 'The brand field is required for ' . $language->name . ' language';
            $messageArray[$language->code . '_car_model_id.required'] = 'The model field is required for ' . $language->name . ' language';
            $messageArray[$language->code . '_car_model_id.required'] = 'The model field is required for ' . $language->name . ' language';
            $messageArray[$language->code . '_fuel_type_id.required'] = 'The fuel type field is required for ' . $language->name . ' language';
            $messageArray[$language->code . '_transmission_type_id.required'] = 'The transmission type field is required for ' . $language->name . ' language';

            $messageArray[$language->code . '_description.required'] = 'The description field is required for ' . $language->name . ' language';

            $messageArray[$language->code . '_description.min'] = 'The description field atleast have 15 characters for ' . $language->name . ' language';
        }

        return $messageArray;
    }
}
