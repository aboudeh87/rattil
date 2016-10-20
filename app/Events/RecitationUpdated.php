<?php

namespace App\Events;


use App\Recitation;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\SerializesModels;

class RecitationUpdated
{

    use SerializesModels;

    /**
     * @var Recitation
     */
    protected $model;

    /**
     * Create a new event instance.
     *
     * @param \App\Recitation $model
     */
    public function __construct(Recitation $model)
    {
        $this->model = $model;
    }

    /**
     * @return \App\Recitation
     */
    public function getModel()
    {
        return $this->model;
    }
}
