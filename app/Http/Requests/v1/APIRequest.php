<?php

namespace App\Http\Requests\v1;

use App\Traits\ApiResponder;
use Illuminate\Foundation\Http\FormRequest;

abstract class APIRequest extends FormRequest
{
    use ApiResponder;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    abstract public function authorize();

//    protected function failedValidation(Validator $validator)
//    {
//        $errors = (new ValidationException($validator))->errors();
//
//        throw new HttpResponseException(
//            $this->error(ResponseMessages::VALIDATION_FAILURE,JsonResponse::HTTP_UNPROCESSABLE_ENTITY, [
//                'errors' => $errors
//            ])
//        );
//    }
//
//    protected function failedAuthorization()
//    {
////        dd("hellos");
////        throw new HttpResponseException(
////            $this->error(ResponseMessages::UNAUTHORIZED,JsonResponse::HTTP_UNAUTHORIZED)
////        );
//    }
}
