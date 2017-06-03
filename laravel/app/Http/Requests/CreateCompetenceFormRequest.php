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
				
		/* foreach($this->request->get('names') as $key => $val)
		{
			$rules['names.'.$key] = 'required|min:2';
		}
		foreach($this->request->get('description') as $key => $val)
		{
			$rules['description.'.$key] = 'required|min:2';
		} */
		
		return $rules;
		
    }
	
	/* public function messages() 
	{
		$messages = [];
		foreach($this->request->get('items') as $key => $val)
		{
			$messages['items.'.$key.'.max'] = 'The field labeled "Book Title '.$key.'" must be less than :max characters.';
		}
		return $messages;
	} */
}
