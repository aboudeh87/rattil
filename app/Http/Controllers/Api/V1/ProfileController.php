<?php

namespace App\Http\Controllers\Api\V1;


use Image;
use Storage;
use App\User;
use Illuminate\Http\Request;
use App\Traits\JsonResponses;
use App\Traits\ProfilesChecker;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\ProfileRequest;
use App\Transformers\V1\UserTransformer;
use App\Transformers\V1\ProfileTransformer;

class ProfileController extends ApiController
{

    use ProfilesChecker, JsonResponses;

    const IMAGES_PATH = '/public/profiles/';

    /**
     * Update profile of current user
     *
     * @param \App\Http\Requests\ProfileRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Upload avatar for logged in user
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(Request $request)
    {
        $this->validate($request, ['image' => 'required|image']);

        $this->model = auth($this->guard)->user();

        $this->imageProcess($request);

        return $this->respondSuccess(trans('messages.avatar_uploaded_success'));
    }

    /**
     * Delete avatar for logged in user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAvatar()
    {
        $this->model = auth($this->guard)->user();
        $this->model->avatar = null;
        $this->model->save();

        Storage::delete(self::IMAGES_PATH . $this->getAvatarName());

        return $this->respondSuccess(trans('messages.avatar_deleted_success'));
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
            return $this->accessDeniedResponse();
        }

        return $this->returnProfileResponse();
    }

    /**
     * Search in users profile
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'query'     => 'required|min:2',
            'certified' => 'required|min:2',
        ]);

        $string = $request->get('query');

        $models = User::where(function ($query) use ($string)
        {
            $keyword = '%' . str_replace(' ', '%', $string) . '%';

            $query->where('username', 'like', $keyword)
                ->orWhere('name', 'like', $keyword)
                ->orWhere('email', $string);
        });

        if ($request->get('certified', null) !== null)
        {
            $models->where('certified', (bool) $request->get('certified'));
        }

        return $this->respondWithPagination(
            $models->paginate(),
            new UserTransformer
        );
    }

    /**
     * Process upload avatar of user
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function imageProcess(Request $request)
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

            $filename = $this->getAvatarName();

            if ($this->model->avatar)
            {
                Storage::delete(self::IMAGES_PATH . $filename);
            }

            Storage::put(self::IMAGES_PATH . $filename, $image->save(), 'public');

            $this->model->avatar = $request->root() . self::IMAGES_PATH . $filename;
        }
    }

    /**
     * Get image file name for logged in user
     *
     * @return string
     */
    protected function getAvatarName()
    {
        return md5($this->model->id) . '.jpg';
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