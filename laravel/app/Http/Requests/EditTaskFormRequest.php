<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditTaskFormRequest extends FormRequest
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
        $rules['title'] = 'required|min:2|max:255|unique:tasks,title,'.$this->get('id');
        $rules['description'] = 'required|min:2';
		$rules['competence_ids'] = 'required';
        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'O campo título é obrigatório',
            'title.min' => 'O campo título está muito curto. minimo:2',
            'title.max' => 'O campo título está muito longo maximo:255',
            'title.unique' => 'Uma tarefa já foi cadastrada com este nome. Por favor utilize outro nome.',
            'description.required'  => 'O campo descrição é obrigatório',
            'description.min'  => 'O campo descrição está muito curto',
			'competence_ids.required' => 'Você precisa cadastrar pelo menos uma competência necessária para a execução da tarefa',
        ];
    }
}
