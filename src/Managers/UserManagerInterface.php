<?php
namespace Dolf\SSO\Managers;


use Illuminate\Database\Eloquent\Model;

interface UserManagerInterface
{
    /**
     * UserManagerInterface constructor.
     * @param Model $model
     */
    public function __construct(Model $model);


    /**
     * @param $userData
     * @return mixed
     */
    public function createUser($userData);


    /**
     * @param Model $model
     * @param $userData
     * @return mixed
     */
    public function updateUser(Model $model, $userData);
}