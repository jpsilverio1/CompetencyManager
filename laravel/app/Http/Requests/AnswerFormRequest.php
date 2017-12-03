<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerFormRequest extends FormRequest
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
		return $rules;
		foreach($this->request->get('personal_competence_level_id') as $key => $val)
		{
			$rules['personal_competence_level_id.'.$key] = 'required';
			foreach ($val as $key_i => $val_i) {
				$rules['personal_competence_level_id.$key.$key_i'] = 'required';
			}
		}
        
    }
	
	public function messages() 
	{
        $messages = [];
		return $messages;
		foreach($this->request->get('personal_competence_level_id') as $key => $val)
		{
			foreach ($val as $key_i => $val_i) {
				$messages['personal_competence_level_id.'.$key.'.'.$key_i.'.'.'required'] = 'É obrigatório responder a esta pergunta';
			}
		}
		
	}
}
