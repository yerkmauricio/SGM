<?php

namespace App\Http\Controllers;

use App\Models\mensaje;
use App\Http\Requests\StoremensajeRequest;
use App\Http\Requests\UpdatemensajeRequest;
use App\Models\persona;
use App\Models\tipo_mensaje;
use App\Models\tipo_persona;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendMessageJob;
//use Illuminate\Http\UploadedFile;

use Illuminate\Http\Request;

class MensajeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:mensajes.index')->only('index');
        $this->middleware('can:mensajes.create')->only('create', 'store');
    }
    public function index()
    {
        $activeUsers = User::where('estado', 1)->count();
        $inactiveUsers = User::where('estado', 0)->count();

        $personCounts = DB::table('personas')
            ->join('tipo_personas', 'personas.tipo_persona_id', '=', 'tipo_personas.id')
            ->select('tipo_personas.nombre as tipo_persona', DB::raw('count(personas.id) as count'))
            ->groupBy('tipo_personas.nombre')
            ->get();

        // Separar los nombres de los tipos de personas y sus respectivas cantidades
        $labels = $personCounts->pluck('tipo_persona');
        $counts = $personCounts->pluck('count');
        $mensajes = mensaje::all();



        $users = User::all();

        // Crear un array para almacenar los datos de la gráfica
        $data = [];

        // Iterar sobre cada usuario
        foreach ($users as $user) {
            // Obtener la cantidad de mensajes enviados por el usuario
            $messageCount = Mensaje::where('user_id', $user->id)->count();

            // Agregar los datos del usuario a la matriz de datos
            $data[] = [
                'name' => $user->name . ' ' . $user->apellidopaterno,
                'messages_sent' => $messageCount
            ];
        }

        // Convertir los datos a formato JSON para la gráfica
        $jsonData = json_encode($data);
        return view('administrador.mensajes.index', compact('mensajes', 'activeUsers', 'inactiveUsers', 'labels', 'counts',  'jsonData'));
    }


    public function create()
    {
        // $tipo_mensajes = tipo_mensaje::pluck('nombre', 'id');
        $tipo_mensajes = tipo_mensaje::all();
        // $tipo_personas = tipo_persona::all();
        $tipo_personas = tipo_persona::where('estado', 1)->get();
        // dd($tipo_personas);
        return view('administrador.mensajes.create', compact('tipo_mensajes', 'tipo_personas'));
    }

    public function store(StoremensajeRequest $request)
    {
        //$tipo_persona = tipo_persona::all();
        // Aumentar el tiempo de ejecución máximo
        ini_set('max_execution_time', 125);
        $imagenBase64 = null;

        if ($request->nombre == null) {

            $mensajeencontrado = tipo_mensaje::find($request->tipo_mensaje_id);
            $titulo = $mensajeencontrado->nombre;
            $mensaje = $mensajeencontrado->descripcion;

            if ($mensajeencontrado->foto != null) {
                $url =  Storage::path($mensajeencontrado->foto);
                $imagenBase64 = base64_encode(file_get_contents($url));
            }
        } else {
            $titulo = $request->nombre;
            $mensaje = $request->descripcion;

            if ($request->hasFile('foto')) {
                $tipo_mensajes =  tipo_mensaje::create($request->all());

                $foto = $request->file('foto')->store('tipo_mensajes'); //el nombre de la carpeta public
                $tipo_mensajes->foto = $foto;

                $tipo_mensajes->save();
                //$request->file('foto')->delete();
                $id = $tipo_mensajes->id;
                $mensajeencontrado = tipo_mensaje::find($id);
                //dd($mensajeencontrado);
                $titulo = $mensajeencontrado->nombre;
                $mensaje = $mensajeencontrado->descripcion;
                $url =  Storage::path($mensajeencontrado->foto);
                $imagenBase64 = base64_encode(file_get_contents($url));
            }
        }
        $personas = Persona::whereIn('tipo_persona_id', $request->input('tipo_persona_id'))->get();
        // dd($personas->tipo_persona);
        // $tipo_per = $personas->tipo_persona;
        // dd($tipo_per);
        $user_id = auth()->id();
        // $PF = $this->formatearMensaje($request, $mensaje);
        $PF = $this->formatearMensaje($titulo, $mensaje);

        // Despachar cada mensaje a la cola
        foreach ($personas as $persona) {
            $tipo_persona_id = $persona->tipo_persona_id;
            // dd($tipo_persona_id);
            $mensaje = new Mensaje();

            // Asignar los atributos manualmente
            $mensaje->tipo_mensaje_id = $request->tipo_mensaje_id;
            $mensaje->tipo_persona_id = $tipo_persona_id;
            $mensaje->user_id = $user_id;
            // Guardar el objeto en la base de datos
            $mensaje->save();
            // $intervalo = rand(15, 20);
            // sleep($intervalo);
            // $imagenBase64;
            SendMessageJob::dispatch($persona, $mensaje, $titulo, $imagenBase64, $user_id, $PF);
        }

        return redirect()->back()->with('editar', 'ok');
    }

    private function formatearMensaje($request, $mensaje)
    {
        $a = "*"; // Negrita en WhatsApp
        $b = "\n"; // Salto de línea en WhatsApp
        $c = "-"; // Guion para listas no ordenadas

        $html = $mensaje;
        $html = str_replace('&nbsp;', ' ', $html);

        // Reemplazar <br> con saltos de línea
        $PF = preg_replace('/<br\s*\/?>/i', $b, $html);

        // Reemplazar </p> con salto de línea
        $PF = preg_replace('/<\/p>/', $b, $PF);

        // Reemplazar <b> y </b> con asteriscos
        $PF = preg_replace('/<b>(.*?)<\/b>/', $a . '$1' . $a, $PF);

        // Reemplazar <strong> y </strong> con asteriscos
        $PF = preg_replace('/<strong>(.*?)<\/strong>/', $a . '$1' . $a, $PF);

        // Reemplazar <ul> y </ul> con saltos de línea
        $PF = preg_replace('/<ul>/', $b, $PF);
        $PF = preg_replace('/<\/ul>/', $b, $PF);

        // Reemplazar <ol> y </ol> con saltos de línea y usar un contador para los elementos de la lista ordenada
        $PF = preg_replace_callback('/<ol>(.*?)<\/ol>/s', function ($matches) use ($b) {
            $listItems = preg_replace('/<li>/', '', $matches[1]);
            $listItems = preg_replace('/<\/li>/', '', $listItems);
            $items = explode("\n", trim($listItems));
            $result = '';
            foreach ($items as $index => $item) {
                if (!empty($item)) {
                    $result .= ($index + 1) . '. ' . trim($item) . $b;
                }
            }
            return $result;
        }, $PF);

        // Reemplazar <li> con guiones y saltos de línea
        $PF = preg_replace('/<li>/', $c . ' ', $PF);
        $PF = preg_replace('/<\/li>/', $b, $PF);

        // Reemplazar <a> con links
        $PF = preg_replace('/<a href="(.*?)".*?>(.*?)<\/a>/', '$2 $1', $PF);

        // Eliminar todas las demás etiquetas HTML
        return strip_tags($PF);
    }


    public function show(mensaje $mensaje)
    {
        return view('administrador.mensajes.show', compact('mensaje'));
    }


    public function edit(mensaje $mensaje)
    {
    }


    public function update(UpdatemensajeRequest $request, mensaje $mensaje)
    {
    }


    public function destroy(mensaje $mensaje)
    {
    }


    // comienzos del chat \both  Función para registrar y almacenar respuestas
    public function handle(Request $request)
    {
        $userMessage = $request->input('message');

        // Almacenar la respuesta del usuario en la base de datos
        // Esto depende de cómo estés manejando tu base de datos y tus modelos

        // Ejemplo de cómo podrías almacenar la respuesta:
        $respuesta = new persona();
        $respuesta->contenido = $userMessage;
        $respuesta->save();

        // Enviar respuesta automática
        $response = $this->enviarRespuestaAutomatica($userMessage);

        return $response;
    }

    // Función para enviar una respuesta automática según la respuesta del usuario
    private function enviarRespuestaAutomatica($userMessage)
    {
        $responseMessage = '';

        // Analizar la respuesta del usuario y determinar la acción a tomar
        switch ($userMessage) {
            case 'mal':
                $responseMessage = "Lamentamos que tu experiencia haya sido mala. ¿En qué podemos mejorar?";
                break;
            case 'regular':
                $responseMessage = "Gracias por tu respuesta. ¿Qué podríamos hacer para mejorar?";
                break;
            case 'bueno':
                $responseMessage = "Nos alegra que tu experiencia haya sido buena. ¿Hay algo más que podamos hacer por ti?";
                break;
            case 'perfecto':
            case 'excelente':
                $responseMessage = "¡Qué genial! Nos alegra que hayas tenido una experiencia perfecta. 😊";
                break;
            default:
                $responseMessage = "Gracias por tu respuesta. ¿En qué más podemos ayudarte?";
                break;
        }

        // Enviar la respuesta al usuario
        $response = Http::post('https://api.ultramsg.com/instance84201/messages/text', [
            'token' => 'your_token_here',
            'to' => 'recipient_number_here',
            'message' => $responseMessage,
        ]);

        return $response->json();
    }

    // Función para enviar el mensaje de agradecimiento y solicitud de evaluación
    public function enviarMensajeAgradecimiento()
    {
        // Enviar mensaje de agradecimiento y solicitud de evaluación
        $message = "¡Gracias por visitar el GAMEA! ¿Cómo te pareció tu visita?\n" .
            "Por favor, responde con una de las siguientes opciones:\n" .
            "1. Mal\n" .
            "2. Regular\n" .
            "3. Bueno\n" .
            "4. Perfecto\n" .
            "5. Excelente";

        $response = Http::post('https://api.ultramsg.com/instance84201/messages/text', [
            'token' => 'your_token_here',
            'to' => 'recipient_number_here',
            'message' => $message,
        ]);

        return $response->json();
    }

    // Función para gestionar las respuestas del formulario de evaluación
    public function gestionarRespuestaEvaluacion(Request $request)
    {
        $userResponse = $request->input('respuesta_evaluacion');

        // Si el usuario está de acuerdo en recibir información adicional
        if ($userResponse === 'si') {
            $message = "¡Gracias por tu respuesta! ¿Te gustaría recibir más información sobre el GAMEA?";
        } else {
            $message = "Entendido, gracias por tu respuesta.";
        }

        // Enviar mensaje al usuario
        $response = Http::post('https://api.ultramsg.com/instance84201/messages/text', [
            'token' => 'your_token_here',
            'to' => 'recipient_number_here',
            'message' => $message,
        ]);

        return $response->json();
    }
}
