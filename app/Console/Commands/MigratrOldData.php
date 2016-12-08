<?php

namespace App\Console\Commands;


use App\Sura;
use App\Verse;
use App\Country;
use App\Narration;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MigratrOldData extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rattil:migrate-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old data to the new database.';

    /**
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * @var \Illuminate\Database\Connection
     */
    protected $newConnection;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->connection = \DB::connection('old_mysql');
        $this->newConnection = \DB::connection();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->countriesMigration()
            ->suwarMigration()
            ->versesMigration()
            ->narrationsMigration();

        $this->info('Migration done successfully.');

        return 0;
    }

    /**
     * Migrate countries data
     *
     * @return $this
     */
    protected function countriesMigration()
    {
        $countries = $this->query()->from('countries')->get();
        $newQuery = $this->newQuery();

        foreach ($countries as $country)
        {
            if (!Country::whereId(2)->count())
            {
                $newQuery->from('countries')->insert([
                    'id'         => $country->CID,
                    'key'        => $country->flag,
                    'created_at' => new Carbon(),
                    'updated_at' => new Carbon(),
                ]);

                $newQuery->from('country_name')->insert([
                    'country_id'   => $country->CID,
                    'language_key' => 'ar',
                    'name'         => $country->arabicname,
                    'created_at'   => new Carbon(),
                    'updated_at'   => new Carbon(),
                ]);

                $newQuery->from('country_name')->insert([
                    'country_id'   => $country->CID,
                    'language_key' => 'en',
                    'name'         => $country->englishname,
                    'created_at'   => new Carbon(),
                    'updated_at'   => new Carbon(),
                ]);
            }
        }

        return $this;
    }

    /**
     * Migrate Suwar data
     *
     * @return $this
     */
    protected function suwarMigration()
    {
        $suwar = $this->query()->from('vy_surahmeta')->get();
        $newQuery = $this->newQuery();

        foreach ($suwar as $sura)
        {
            if (!Sura::whereId($sura->suraindex)->count())
            {
                $newQuery->from('suwar')->insert([
                    'id'                  => $sura->suraindex,
                    'revealed'            => $sura->revealed,
                    'chronological_order' => $sura->chronological_order,
                    'created_at'          => new Carbon(),
                    'updated_at'          => new Carbon(),
                ]);

                $newQuery->from('suwar_content')->insert([
                    'sura_id'      => $sura->suraindex,
                    'language_key' => 'ar',
                    'name'         => $sura->name_ar,
                    'definition'   => $sura->definition,
                    'created_at'   => new Carbon(),
                    'updated_at'   => new Carbon(),
                ]);

                $newQuery->from('suwar_content')->insert([
                    'sura_id'      => $sura->suraindex,
                    'language_key' => 'en',
                    'name'         => $sura->name_en,
                    'definition'   => $sura->definition,
                    'created_at'   => new Carbon(),
                    'updated_at'   => new Carbon(),
                ]);
            }
        }

        return $this;
    }

    /**
     * Migrate Verses data
     *
     * @return $this
     */
    protected function versesMigration()
    {
        $verses = $this->query()->from('mushaf_chr')->get();

        foreach ($verses as $verse)
        {
            if (!Verse::whereNumber($verse->aya)->whereSuraId($verse->sura)->count())
            {
                Sura::find($verse->sura)
                    ->verses()
                    ->create([
                        'number'     => $verse->aya,
                        'chapter'    => $verse->part,
                        'page'       => $verse->page,
                        'characters' => $verse->mushaf_chr,
                        'text'       => $verse->text,
                        'clean_text' => $verse->cleantext,
                        'created_at' => new Carbon(),
                        'updated_at' => new Carbon(),

                    ]);
            }
        }

        return $this;
    }

    /**
     * Migrate Narrations data
     *
     * @return $this
     */
    protected function narrationsMigration()
    {
        $narrations = $this->query()->from('rewajah')->where('delstat', 1)->get();
        $newQuery = $this->newQuery();

        foreach ($narrations as $narration)
        {
            if (!Narration::whereId($narration->idrewajah)->count())
            {
                $newQuery->from('narrations')->insert([
                    'id'         => $narration->idrewajah,
                    'weight'     => $narration->weight,
                    'created_at' => new Carbon(),
                    'updated_at' => new Carbon(),
                ]);

                $newQuery->from('narration_name')->insert([
                    'narration_id' => $narration->idrewajah,
                    'language_key' => 'ar',
                    'name'         => $narration->rew_name,
                    'created_at'   => new Carbon(),
                    'updated_at'   => new Carbon(),
                ]);

                $newQuery->from('narration_name')->insert([
                    'narration_id' => $narration->idrewajah,
                    'language_key' => 'en',
                    'name'         => $narration->rew_name_en,
                    'created_at'   => new Carbon(),
                    'updated_at'   => new Carbon(),
                ]);
            }
        }

        return $this;
    }

    /**
     * Get a new query builder from the
     * old database connection
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function query()
    {
        return $this->connection->query();
    }

    /**
     * Get a new query builder from the
     * new database connection
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newQuery()
    {
        return $this->newConnection->query();
    }
}
