<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeamFormRequest extends FormRequest
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
        $rules['name'] = 'required|unique:teams|min:2|max:255';
        $rules['description'] = 'required|min:2';
        /*foreach($this->request->get('competence_ids') as $key => $val)
        {
            $rules['competence_ids.'.$key] = 'required';
        } */
        /*return [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ];
        foreach($this->request->get('name') as $key => $val)
        {
            $rules['name.'.$key] = 'required|min:2|unique:competencies,name';
        }
        foreach($this->request->get('description') as $key => $val)
        {
            $rules['description.'.$key] = 'required|min:2';
        } */
        return $rules;
    }
	
	public function messages() 
	{
		/* $messages = [];
		foreach($this->request->get('name') as $key => $val)
		{
			$messages['name.'.$key.'.required'] = 'O campo nome é obrigatório';
			$messages['name.'.$key.'.min'] = 'O campo nome está muito curto';
			$messages['name.'.$key.'.unique'] = 'Uma equipe já foi cadastrada com este nome. Por favor utilize outro nome.';
			$messages['description.'.$key.'.required'] = 'O campo descrição é obrigatório';
			$messages['description.'.$key.'.min'] = 'O campo descrição está muito curto';
		} 
		return $messages; */
        return [
            'name.required' => 'O campo nome é obrigatório',
            'name.min' => 'O campo nome está muito curto. minimo:2',
            'name.max' => 'O campo nome está muito longo maximo:255',
            'name.unique' => 'Uma equipe já foi cadastrada com este nome. Por favor utilize outro nome.',
            'description.required'  => 'O campo descrição é obrigatório',
            'description.min'  => 'O campo descrição está muito curto',
        ];



	}
}
