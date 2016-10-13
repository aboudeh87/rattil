<?php

namespace App\Events;


use App\Recitation;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\SerializesModels;

class NewRecitationPosted
{

    use SerializesModels;

    /**
     * @var Recitation
     */
    protected $model;

    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * Create a new event instance.
     *
     * @param \App\Recitation               $model
     * @param \Illuminate\Http\UploadedFile $file
     */
    public function __construct(Recitation $model, UploadedFile $file)
    {
        $this->model = $model;
        $this->file = $file;
    }

    /**
     * @return \App\Recitation
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return \Illuminate\Http\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
}
