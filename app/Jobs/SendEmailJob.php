<?php

namespace App\Jobs;

use App\Constantes\Constante;
use App\Models\Consultas;
use App\Models\Reportes;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cuenta;
    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cuenta)
    {
        //
        $this->id = $cuenta['id'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $updateSolicitud = Consultas::where('id_consulta', $this->id)->first();
        $reportes = Reportes::all()->pluck('nombre_reporte', 'id_reporte')->toArray();
        if (is_object($updateSolicitud)) {
            try {
                $data["email"] = $updateSolicitud->email;
                $data["id"] = $updateSolicitud->id_consulta;
                $data["title"] = "Notificacion de Solicitud de Reporte";
                $data["tipo"] = $updateSolicitud->reporte;
                $data["fecha"] = $updateSolicitud->acceso;
                $data["reportes"] = $reportes;
                $filename = storage_path() . '/Reportes/' . $updateSolicitud->nombre_archivo;
                $files = [$filename];

                Mail::send('transparencia.email.solicitud', $data, function ($message) use ($data, $files) {
                    $message->to($data["email"], $data["email"])
                        ->subject($data["title"]);
                    foreach ($files as $file) {
                        $message->attach($file);
                    }
                });

                $updateSolicitud->correo_enviado = Constante::CORREO_ENVIADO_EXITOSO;
                $updateSolicitud->save();
            } catch (Exception $ex) {
                Log::error('Error en Envio de Correo ',
                    [
                        'mensaje' => $ex->getMessage(),
                        'id' => $this->id,
                        'consulta' => $updateSolicitud->toArray(),
                        'traza' => $ex->getTraceAsString(),
                    ]
                );
                $updateSolicitud->correo_enviado = Constante::CORREO_NO_ENVIADO;
                $updateSolicitud->save();

            }
        } else {
            Log::error('Error no se encontro la solicitud: ' . $this->id);
        }
    }
}
