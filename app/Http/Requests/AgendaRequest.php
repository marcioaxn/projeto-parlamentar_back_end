<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendaRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Autoriza todas as requisições (ajuste conforme necessário)
    }

    public function rules()
    {
        return [
            'dsc_titulo' => 'required|string|max:255',
            'dsc_descricao' => 'nullable|string',
            'dat_inicio' => 'required|date',
            'dat_fim' => 'required|date|after_or_equal:dat_inicio', // Data de fim deve ser igual ou posterior à data de início
            'nom_cor' => 'nullable|string|max:7', // Validação para cor (ex: #FFFFFF)
            'ind_recorrente' => 'boolean',
            'des_url' => 'nullable|url',
            'cod_parlamentar' => 'required|exists:tab_parlamentar,cod_parlamentar', // Valida se o parlamentar existe
        ];
    }

    public function messages()
    {
        return [
            'dat_fim.after_or_equal' => 'A data de fim deve ser igual ou posterior à data de início.',
            'cod_parlamentar.exists' => 'O parlamentar selecionado não existe.'
        ];
    }
}
