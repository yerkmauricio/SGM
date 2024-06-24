<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Mensaje;
use Illuminate\Support\Facades\Storage;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $persona;
    protected $mensaje;
    protected $titulo;
    protected $user_id;
   // protected $requestData;
    protected $mensajeencontrado;
    protected $imagenBase64;
    protected $PF;



    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($persona, $mensaje, $titulo, $imagenBase64, $user_id, $PF)
    {
        $this->persona = $persona;
        $this->mensaje = $mensaje;
        $this->user_id = $user_id;
        $this->titulo = $titulo;
        $this->PF = $PF;
        $this->imagenBase64 = $imagenBase64;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        if ($this->imagenBase64 == null) {
            $intervalo = rand(15, 20);
            sleep($intervalo);
            Http::post('https://api.ultramsg.com/instance87158/messages/chat', [ //cambiar
                'token' => '8ixtbk4mz805c7j6', //cambiar
                'to' => $this->persona->whatsapp,
                'body' => strtoupper($this->titulo) . "\n" . ' Estimado ' . strtoupper($this->persona->tipo_persona->nombre) . ' ' . $this->PF,
            ]);
        } else {
            $intervalo = rand(15, 20);
            sleep($intervalo);
            Http::post('https://api.ultramsg.com/instance87158/messages/image', [ //cambiar
                'token' => '8ixtbk4mz805c7j6', //cambiar
                'to' => $this->persona->whatsapp,
                'image' => $this->imagenBase64, // Contenido de la imagen en base64
                'caption' => strtoupper($this->titulo) . "\n" . ' Estimado ' . strtoupper($this->persona->tipo_persona->nombre) . ' ' . $this->PF,
            ]);
        }

        //dd($this->mensaje->id);
        // $mensaje = new Mensaje();
        // $mensaje->tipo_mensaje_id = $this->mensaje->id;
        // $mensaje->tipo_persona_id =  $this->persona->tipo_personas_id;// Puedes ajustar esto según tus necesidades
        // $mensaje->user_id = $this->user_id;
        // $mensaje->save();
    }
}
