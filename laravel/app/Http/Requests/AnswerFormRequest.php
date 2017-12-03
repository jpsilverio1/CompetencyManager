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
		for ($indiceUsuario = 0; $indiceUsuario < count($this->request->get('evaluated_user_id')); $indiceUsuario++) {
			for ($indiceQuestao = 0; $indiceQuestao < count($this->request->get('personal_competence_id')); $indiceQuestao++) {
				$rules['personal_competence_level_id'.strval($indiceUsuario).strval($indiceQuestao)] = 'required';
			}
		}
		return $rules;
        
    }
	
	public function messages() 
	{
        $messages = [];
		for ($indiceUsuario = 0; $indiceUsuario < count($this->request->get('evaluated_user_id')); $indiceUsuario++) {
			for ($indiceQuestao = 0; $indiceQuestao < count($this->request->get('personal_competence_id')); $indiceQuestao++) {
				$messages['personal_competence_level_id'.strval($indiceUsuario).strval($indiceQuestao).'.required'] = 'É obrigatório responder a esta pergunta';
			}
		}
		return $messages;
	}
}
