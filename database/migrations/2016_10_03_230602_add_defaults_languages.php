<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultsLanguages extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Language::create([
            'key'       => 'en',
            'name'      => 'English',
            'direction' => 'ltr',
            'published' => true,
        ]);

        \App\Language::create([
            'key'       => 'ar',
            'name'      => 'العربية',
            'direction' => 'rtl',
            'published' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Language::whereIn('key', ['ar', 'en'])->delete();
    }
}
