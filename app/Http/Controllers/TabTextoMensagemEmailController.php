<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabTextoMensagemEmail;
use Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class TabTextoMensagemEmailController extends Controller
{
    public function getMensagemQueEstaraNoEmailBoasVindas()
    {
        return TabTextoMensagemEmail::where('tpo_objetivo', 'mensagem-que-estara-no-e-mail-boas-vindas')
            ->first();
    }
}
