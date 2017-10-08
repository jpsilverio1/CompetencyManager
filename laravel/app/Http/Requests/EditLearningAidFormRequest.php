<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditLearningAidFormRequest extends FormRequest
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
        $rules = [];
        $rules['name'] = 'required|min:2|max:255|unique:learningaids,name,'.$this->get('id');
        $rules['description'] = 'required|min:2';
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório',
            'name.min' => 'O campo nome está muito curto. minimo:2',
            'name.max' => 'O campo nome está muito longo maximo:255',
            'name.unique' => 'Um treinamento já foi cadastrado com este nome. Por favor utilize outro nome.',
            'description.required'  => 'O campo descrição é obrigatório',
            'description.min'  => 'O campo descrição está muito curto',
        ];
    }
}
