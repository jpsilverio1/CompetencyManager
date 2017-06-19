<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompetenceFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->isManager();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		$rules = [];

		foreach($this->request->get('name') as $key => $val)
		{
			$rules['name.'.$key] = 'required|min:2|unique:competencies,name';
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
		foreach($this->request->get('name') as $key => $val)
		{
			$messages['name.'.$key.'.required'] = 'O campo nome é obrigatório';
			$messages['name.'.$key.'.min'] = 'O campo nome está muito curto';
			$messages['name.'.$key.'.unique'] = 'Uma competência já foi cadastrada com este nome. Por favor utilize outro nome.';
			$messages['description.'.$key.'.required'] = 'O campo descrição é obrigatório';
			$messages['description.'.$key.'.min'] = 'O campo descrição está muito curto';
		}
		return $messages;
	}
}
