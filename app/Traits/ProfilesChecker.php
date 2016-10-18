<?php

namespace App\Traits;


use App\User;

/**
 * Class ProfilesChecker
 *
 * @package App\Traits
 */
trait ProfilesChecker
{

    /**
     * @var string
     */
    protected $guard = 'api';

    /**
     * @var User
     */
    protected $model;

    /**
     * Check if the logged in user can access profile
     *
     * @param string $value
     *
     * @return bool
     */
    protected function isAllowed($value = null)
    {
        if ($value === null)
        {
            $this->model = auth($this->guard)->user();

            return true;
        }

        $this->model = $this->getUserModel($value);
        $privacy = $this->model->properties()->whereKey('private')->first();

        if ($this->model->id === auth($this->guard)->id() || !$privacy || !$privacy->value)
        {
            return true;
        }

        return (bool) $this->model
            ->followers()
            ->where('user_id', auth($this->guard)->id())
            ->where('accepted', true)
            ->count();
    }

    /**
     * Get user model from username or ID
     *
     * @param string $value
     *
     * @return User
     */
    protected function getUserModel($value)
    {
        if (is_numeric($value))
        {
            return User::whereId($value)->firstOrFail();
        }
        else
        {
            return User::whereUsername($value)->firstOrFail();
        }
    }
}