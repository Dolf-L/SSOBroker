<?php
namespace Dolf\SSO\Repositories;


use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    /**
     * UserManagerInterface constructor.
     * @param Model $model
     */
    public function __construct(Model $model);


    /**
     * @param $customer_email
     * @return mixed
     */
    public function getUserByEmail($customer_email);
}