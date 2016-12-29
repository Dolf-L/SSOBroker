<?php

namespace Dolf\SSO;

use Illuminate\Contracts\Validation\Factory as Validator;

class SSOValidator
{
    /**
     * @var Validator
     */
    public $validator;

    /**
     * SSOValidator constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }


    /**
     * @param $passport
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateData($passport)
    {
        $validator = $this->validator->make((array)$passport, [
            'customer_email'   => 'required|email',
            'first_name'       => 'required|max:255',
            'last_name'        => 'required|max:255',
            'last_ip'          => 'required|max:255',
        ]);

        return $validator;
    }
}