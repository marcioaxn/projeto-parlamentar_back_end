<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AtualizarOuCriarDadosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model;
    protected $id;
    protected $campos;

    /**
     * Create a new job instance.
     *
     * @param  string  $model
     * @param  array  $id
     * @param  array  $campos
     * @return void
     */
    public function __construct($model, $id, $campos)
    {
        $this->model = $model;
        $this->id = $id;
        $this->campos = $campos;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Aqui você coloca a lógica original do seu método:
        $this->model::updateOrCreate($this->id, $this->campos);
    }
}

