<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskFormRequest extends FormRequest
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
				
		foreach($this->request->get('title') as $key => $val)
		{
			$rules['title.'.$key] = 'required|min:2|unique:tasks,title';
		}
		foreach($this->request->get('description') as $key => $val)
		{
			$rules['description.'.$key] = 'required|min:2';
		}
		return $rules;
    }
	
	public function messages() 
	{
		$messages = [];
		foreach($this->request->get('title') as $key => $val)
		{
			$messages['title.'.$key.'.required'] = 'O campo título é obrigatório';
			$messages['title.'.$key.'.min'] = 'O campo título está muito curto';
			$messages['title.'.$key.'.unique'] = 'Uma tarefa já foi cadastrada com este título. Por favor utilize outro título.';
			$messages['description.'.$key.'.required'] = 'O campo descrição é obrigatório';
			$messages['description.'.$key.'.min'] = 'O campo descrição está muito curto';
		} 
		return $messages; 
	}
}
