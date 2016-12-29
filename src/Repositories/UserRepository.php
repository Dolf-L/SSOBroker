<?php
namespace Dolf\SSO\Repositories;


use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var Model
     */
    public $model;

    /**
     * UserManager constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param $customer_email
     * @return mixed
     */
    public function getUserByEmail($customer_email)
    {
        return $this->model->where('email', $customer_email)->first();
    }
}