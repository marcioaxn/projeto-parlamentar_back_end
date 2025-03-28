<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\TabAudit;
use Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class TabAuditController extends Controller
{
    public function gravarAutoriaPorColuna($id = null, $model = null, $campos = null, $tipoAcao = null)
    {

        try {

            if (is_array($id)) {

                if (isset($id) && !is_null($id) && $id != '' && is_array($id) && count($id) > 0) {

                    if (count($id) == 1) {

                        $nomeId = null;
                        $contId = 1;

                        foreach ($id as $key => $value) {

                            if ($contId == 1) {
                                $nomeId = $key;
                            }

                            $contId++;
                        }

                        $consulta = null;

                        if (array_key_exists($nomeId, $id)) {

                            $consulta = $model::find($id[$nomeId]);
                        }

                        // Use o método `getTable` para obter o nome da tabela
                        $tableName = (new $model())->getTable();

                        // Consulta SQL para obter o nome do schema
                        $query = "SELECT table_schema FROM information_schema.tables WHERE table_name = ?";

                        // Execute a consulta SQL
                        $results = DB::select($query, [$tableName]);

                        // Verifique se há resultados e obtenha o nome do schema
                        if (!empty($results)) {
                            $schemaName = $results[0]->table_schema;
                        } else {
                            $schemaName = 'Nenhum schema encontrado'; // Trate o caso em que nenhum schema foi encontrado
                        }

                        // Início gravar auditoria
                        if ($consulta !== null) {

                            foreach ($campos as $key => $value) {

                                $dadoBase = null;

                                $dadoBase = $consulta->$key;

                                $column_name = null;
                                $data_type = null;

                                if ($schemaName != 'Nenhum schema encontrado') {

                                    $estruturaColumn = $this->getColumnTable($schemaName, $tableName, $key);

                                    $column_name = $estruturaColumn->column_name;
                                    $data_type = $estruturaColumn->data_type;
                                }

                                if ($data_type != 'timestamp with time zone' && $value != $dadoBase) {

                                    $dataHoraAtual = now();

                                    // Adiciona 8 horas
                                    $dataHoraAtual->addHours(8);

                                    $gravarAuditoria = new TabAudit;

                                    $gravarAuditoria->acao = $tipoAcao;
                                    $gravarAuditoria->antes = $dadoBase;
                                    $gravarAuditoria->depois = $value;
                                    $gravarAuditoria->table = $tableName;
                                    $gravarAuditoria->column_name = $key;
                                    $gravarAuditoria->data_type = $data_type;
                                    $gravarAuditoria->table_id = $id[$nomeId];
                                    $gravarAuditoria->ip = $_SERVER['REMOTE_ADDR'];
                                    $gravarAuditoria->cod_user = Auth::user()->cod_user;
                                    $gravarAuditoria->dte_expired_at = $dataHoraAtual->format('Y-m-d H:i:s');

                                    $gravarAuditoria->save();
                                } elseif ($data_type != 'timestamp with time zone' && $value === $dadoBase) {

                                    $dataHoraAtual = now();

                                    // Adiciona 8 horas
                                    $dataHoraAtual->addHours(8);

                                    $gravarAuditoria = new TabAudit;

                                    $gravarAuditoria->acao = $tipoAcao;
                                    $gravarAuditoria->antes = $dadoBase;
                                    $gravarAuditoria->depois = $value;
                                    $gravarAuditoria->table = $tableName;
                                    $gravarAuditoria->column_name = $key;
                                    $gravarAuditoria->data_type = $data_type;
                                    $gravarAuditoria->table_id = $id[$nomeId];
                                    $gravarAuditoria->ip = $_SERVER['REMOTE_ADDR'];
                                    $gravarAuditoria->cod_user = Auth::user()->cod_user;
                                    $gravarAuditoria->dte_expired_at = $dataHoraAtual->format('Y-m-d H:i:s');

                                    $gravarAuditoria->save();
                                }
                            }
                        }
                        // Fim gravar auditoria

                    }
                }
            } else {
                $consulta = $model::find($id);

                // Use o método `getTable` para obter o nome da tabela
                $tableName = (new $model())->getTable();

                // Consulta SQL para obter o nome do schema
                $query = "SELECT table_schema FROM information_schema.tables WHERE table_name = ?";

                // Execute a consulta SQL
                $results = DB::select($query, [$tableName]);

                // Verifique se há resultados e obtenha o nome do schema
                if (!empty($results)) {
                    $schemaName = $results[0]->table_schema;
                } else {
                    $schemaName = 'Nenhum schema encontrado'; // Trate o caso em que nenhum schema foi encontrado
                }

                // Início gravar auditoria
                if ($consulta !== null) {

                    foreach ($campos as $key => $value) {

                        $dadoBase = null;

                        $dadoBase = $consulta->$key;

                        $column_name = null;
                        $data_type = null;

                        if ($schemaName != 'Nenhum schema encontrado') {

                            $estruturaColumn = $this->getColumnTable($schemaName, $tableName, $key);

                            $column_name = $estruturaColumn->column_name;
                            $data_type = $estruturaColumn->data_type;
                        }

                        if ($data_type != 'timestamp with time zone' && $value != $dadoBase) {

                            $dataHoraAtual = now();

                            // Adiciona 8 horas
                            $dataHoraAtual->addHours(8);

                            $gravarAuditoria = new TabAudit;

                            $gravarAuditoria->acao = $tipoAcao;
                            $gravarAuditoria->antes = $dadoBase;
                            $gravarAuditoria->depois = $value;
                            $gravarAuditoria->table = $tableName;
                            $gravarAuditoria->column_name = $key;
                            $gravarAuditoria->data_type = $data_type;
                            $gravarAuditoria->table_id = $id;
                            $gravarAuditoria->ip = $_SERVER['REMOTE_ADDR'];
                            $gravarAuditoria->cod_user = Auth::user()->cod_user;
                            $gravarAuditoria->dte_expired_at = $dataHoraAtual->format('Y-m-d H:i:s');

                            $gravarAuditoria->save();
                        } elseif ($data_type != 'timestamp with time zone' && $value === $dadoBase) {

                            $dataHoraAtual = now();

                            // Adiciona 8 horas
                            $dataHoraAtual->addHours(8);

                            $gravarAuditoria = new TabAudit;

                            $gravarAuditoria->acao = $tipoAcao;
                            $gravarAuditoria->antes = $dadoBase;
                            $gravarAuditoria->depois = $value;
                            $gravarAuditoria->table = $tableName;
                            $gravarAuditoria->column_name = $key;
                            $gravarAuditoria->data_type = $data_type;
                            $gravarAuditoria->table_id = $id;
                            $gravarAuditoria->ip = $_SERVER['REMOTE_ADDR'];
                            $gravarAuditoria->cod_user = Auth::user()->cod_user;
                            $gravarAuditoria->dte_expired_at = $dataHoraAtual->format('Y-m-d H:i:s');

                            $gravarAuditoria->save();
                        }
                    }
                }
                // Fim gravar auditoria
            }
        } catch (Illuminate\Database\QueryException $e) {
            TabLogErros::create(array('mensagem' => 'Erro ao gravar dados: ' . $e->getMessage()));
        }
    }

    protected function getColumnTable($schema = null, $table = null, $columnName = null)
    {

        return DB::selectOne("SELECT
            column_name,ordinal_position,is_nullable,data_type
            FROM
            information_schema.columns
            WHERE
            table_schema = '" . $schema . "'
            AND table_name = '" . $table . "'
            AND column_name = '" . $columnName . "';");
    }
}
