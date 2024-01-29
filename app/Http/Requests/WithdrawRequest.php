<?php

namespace App\Http\Requests;

use App\Models\WithdrawMethodInput;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
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
            'withdraw_method' => 'required',
            'withdraw_amount' => 'required',
        ];
        $inputs = WithdrawMethodInput::where('withdraw_payment_method_id', $this->withdraw_method)->orderBy('order_number', 'asc')->get();

        foreach ($inputs as $input) {
            if ($input->required == 1) {
                $rules["$input->name"] = 'required';
            }
            $this->validate($rules);
        }
    }
}
