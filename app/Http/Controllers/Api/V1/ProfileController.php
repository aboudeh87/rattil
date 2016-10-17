<?php

namespace App\Http\Controllers\Api\V1;


use Image;
use App\User;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\ProfileRequest;
use App\Transformers\V1\ProfileTransformer;
use Storage;

class ProfileController extends ApiController
{

    const IMAGES_PATH = 'public/uploads/profiles/';

    /**
     * @var string
     */
    protected $guard = 'api';

    /**
     * @var User
     */
    protected $model;

    /**
     * Update profile of current user
     *
     * @param \App\Http\Requests\ProfileRequest $request
     */
    public function update(ProfileRequest $request)
    {
        $this->model = auth($this->guard)->user();
        $this->model->fill(['name' => $request->get('name')]);

        $this->imageProcess($request);

        $this->model->save();

        return $this->handleProfileUpdatedSuccess();
    }

    /**
     * Return items of model
     *
     * @param null|string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id = null)
    {
        if (!$this->isAllowed($id))
        {
            return $this->handleAccessDeniedResponse();
        }

        return $this->returnProfileResponse();
    }

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

    /**
     * Process upload avatar of user
     *
     * @param \App\Http\Requests\ProfileRequest $request
     */
    protected function imageProcess(ProfileRequest $request)
    {
        $file = $request->file('image');

        if ($file instanceof UploadedFile)
        {
            $image = Image::make($file)
                ->encode('jpg', 75)
                ->resize(250, 250, function ($constraint)
                {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

            $filename = md5($this->model->id) . '.jpg';
            if ($this->model->avatar)
            {
                Storage::delete(self::IMAGES_PATH . $filename);
            }

            Storage::put(self::IMAGES_PATH . $filename, $image->save(), 'public');

            $this->model->avatar = $request->root() . self::IMAGES_PATH . $filename;
        }
    }

    /**
     * Return Access denied response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleAccessDeniedResponse()
    {
        return $this->respondError(trans('messages.privacy_access_denied'), 403);
    }

    /**
     * return profile of user response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function returnProfileResponse()
    {
        return $this->respond((new ProfileTransformer)->transform($this->model));
    }

    /**
     * Return success response after updating profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleProfileUpdatedSuccess()
    {
        return $this->respondSuccess(trans('messages.profile_updated_success'));
    }
}