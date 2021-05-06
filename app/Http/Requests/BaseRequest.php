<?php

namespace App\Http\Requests;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Application;

/**
 * Class BaseRequest
 * @package App\Http\Requests
 * @author Shashank Jha
 */
class BaseRequest
{
    /**
     * @var
     */
    protected $requests;

    /**
     * @param $requests
     */
    protected function setData($requests)
    {
        $this->requests = $requests;
    }

    /**
     * @return mixed
     */
    protected function getData()
    {
        return $this->requests;
    }

    /**
     * Get a validation factory instance
     * @return Application|mixed
     */
    protected function getValidationFactory()
    {
        return app('validator');
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Main validation method
     * @param $requests
     * @return mixed
     * @throws ValidationException
     */
    public function validateRequest($requests)
    {
        // set request data
        $this->setData($requests);

        // get all the rules
        $rules = $this->rules();

        // get all the messages
        $messages = $this->messages();

        // validate the request param
        $validator = $this->getValidationFactory()->make($this->getData(), $rules, $messages);

        // throw validation error if validator fails
        if ($validator->fails())
            throw new ValidationException($validator);

        // get the request data
        return $this->getData();
    }
}
