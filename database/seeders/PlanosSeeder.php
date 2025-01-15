<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlanosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tab_planos')->insert([
            [
                'cod_plano' => Str::uuid(),
                'nom_plano' => 'Básico',
                'dsc_plano' => 'Acesso limitado a módulos e usuários.',
                'val_plano' => 500.00,
                'lim_usuarios' => 10,
                'frequencia_cobranca' => 'mensal',
                'sta_ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cod_plano' => Str::uuid(),
                'nom_plano' => 'Premium',
                'dsc_plano' => 'Acesso completo sem limites.',
                'val_plano' => 700.00,
                'lim_usuarios' => null,
                'frequencia_cobranca' => 'mensal',
                'sta_ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
