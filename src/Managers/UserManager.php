<?php
namespace Dolf\SSO\Managers;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserManager
 * @package App\Managers
 */
class UserManager implements UserManagerInterface
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
     * @param $passport
     * @return Model
     */
    public function createUser($passport)
    {
        $this->model->email       = $passport->customer_email;
        $this->model->first_name  = $passport->first_name;
        $this->model->last_name   = $passport->last_name;
        $this->model->last_ip     = $passport->last_ip;

        $this->model->save();

        return $this->model;
    }

    /**
     * @param Model $model
     * @param $passport
     * @return Model
     */
    public function updateUser(Model $model, $passport)
    {
        $model->email          = $passport->customer_email;
        $model->first_name     = $passport->first_name;
        $model->last_name      = $passport->last_name;
        $model->last_ip        = $passport->last_ip;

        $model->save();

        return $model;
    }
}

