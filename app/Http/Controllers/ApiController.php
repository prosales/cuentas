<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Validator;
use Exception;
use Carbon\Carbon;
use App\Clients;
use App\EventClient;
use App\Agenda;
use App\EventAgenda;
use App\EventsPhotos;
use App\AgendaDetail;
use App\Notifications;

class ApiController extends Controller
{
    public $records;
    public $message;
    public $result;
    public $status_code;

    public function __construct()
    {
        $this->records = null;
        $this->message = "Oops! Algo salio mal.";
        $this->result = false;
        $this->status_code = 200;
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|max:255',
                'email' => 'required|unique:clients,email',
                'password' => 'required|min:5',
                'birthday' => 'required|date',
                'fcm' => 'required',
                'dispositivo' => 'required'
            ]);

            if($validator->fails()) {
                $this->message = $validator->errors()->first();
            }
            else {
                $avatar = "";
                if($request->file('foto')) {
                    $imagen = $request->file('foto');
                    $nombre_imagen = time().'.'.$imagen->getClientOriginalExtension();
                    Storage::disk('avatars')->put($nombre_imagen,File::get($imagen), 'public');
                    $avatar = 'avatars/'.$nombre_imagen;
                }
                else {
                    $avatar = 'avatars/avatar.png';
                }
                
                $request->merge([
                    'password' => bcrypt($request->input('password')), 
                    'avatar' => $avatar,
                    'latitude' => 0,
                    'longitude' => 0
                ]);

                $this->records = Clients::create($request->all());
                $this->message = "Registro creado correctamente.";
                $this->result = true;
            }
        }
        catch(Exception $e) {
            $this->records = null;
            $this->message = env('APP_DEBUG') ? $e->getMessage() : 'Ocurrio un problema.';
            $this->result = false;
        }
        finally {
            $response = [
                'result' => $this->result,
                'message' => $this->message,
                'records' => $this->records
            ];

            return response()->json($response, $this->status_code);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required'
            ]);

            if($validator->fails()) {
                $this->message = $validator->errors()->first();
            }
            else {
                $avatar = "";
                if($request->file('foto')) {
                    $imagen = $request->file('foto');
                    $nombre_imagen = time().'.'.$imagen->getClientOriginalExtension();
                    Storage::disk('avatars')->put($nombre_imagen,File::get($imagen), 'public');
                    $avatar = 'avatars/'.$nombre_imagen;
                }
                else {
                    $avatar = $record->avatar;
                }
                
                $record = Clients::find($request->input('client_id'));
                $record->avatar = $avatar;
                $record->save();

                $this->records = $record;
                $this->message = "Registro actualizado correctamente.";
                $this->result = true;
            }
        }
        catch(Exception $e) {
            $this->records = null;
            $this->message = env('APP_DEBUG') ? $e->getMessage() : 'Ocurrio un problema.';
            $this->result = false;
        }
        finally {
            $response = [
                'result' => $this->result,
                'message' => $this->message,
                'records' => $this->records
            ];

            return response()->json($response, $this->status_code);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required'
            ]);

            if($validator->fails()) {
                $this->message = $validator->errors()->first();
            }
            else {
                $client = Clients::where('email', $request->input('email'))->where('code', $request->input('password'))->first();
                if($client) {
                    // if(Hash::check($request->input('password'), $client->password)) {
                    // if($request->input('password'), $client->password)) {
                        $client->fcm = $request->input('fcm', $client->fcm);
                        $client->save();
                        
                        $this->records = $client;
                        $this->message = "Bienvenido.";
                        $this->result = true;
                    // }
                    // else {
                    //     $this->message = "La constraseña ingresada es incorrecta.";
                    //     $this->result = false;
                    // }
                }
                else {
                    $this->message = "Datos incorrectos.";
                    $this->result = false;
                }
            }
        }
        catch(Exception $e) {
            $this->records = null;
            $this->message = env('APP_DEBUG') ? $e->getMessage() : 'Ocurrio un problema.';
            $this->result = false;
        }
        finally {
            $response = [
                'result' => $this->result,
                'message' => $this->message,
                'records' => $this->records
            ];

            return response()->json($response, $this->status_code);
        }
    }

    public function update_token(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required',
            ]);

            if($validator->fails()) {
                $this->message = $validator->errors()->first();
            }
            else {
                $client = Clients::find($request->input('client_id'));
                $client->fcm = $request->input('fcm');
                $client->save();

                $this->records = $client;
                $this->message = "Token actualizado.";
                $this->result = true;
            }
        }
        catch(Exception $e) {
            $this->records = null;
            $this->message = env('APP_DEBUG') ? $e->getMessage() : 'Ocurrio un problema.';
            $this->result = false;
        }
        finally {
            $response = [
                'result' => $this->result,
                'message' => $this->message,
                'records' => $this->records
            ];

            return response()->json($response, $this->status_code);
        }
    }

    public function location(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required',
                'latitude' => 'required',
                'longitude' => 'required'
            ]);

            if($validator->fails()) {
                $this->message = $validator->errors()->first();
            }
            else {
                $client = Clients::find($request->input('client_id'));
                $client->latitude = $request->input('latitude');
                $client->longitude = $request->input('longitude');
                $client->save();

                $this->records = $client;
                $this->message = "Ubicación actualizada.";
                $this->result = true;
            }
        }
        catch(Exception $e) {
            $this->records = null;
            $this->message = env('APP_DEBUG') ? $e->getMessage() : 'Ocurrio un problema.';
            $this->result = false;
        }
        finally {
            $response = [
                'result' => $this->result,
                'message' => $this->message,
                'records' => $this->records
            ];

            return response()->json($response, $this->status_code);
        }
    }

    public function events_per_client($client_id)
    {
        try {
            $date = Carbon::now('America/Guatemala')->toDateString();
            $events = EventClient::select('event_client.*')
                                    ->leftJoin('events', 'events.id', '=', 'event_client.event_id')
                                    ->whereRaw('events.date = ? AND client_id = ?',[$date, $client_id])
                                    ->with('event', 'client')
                                    ->get();
            $this->records = $events;
            $this->message = "Eventos consultados";
            $this->result = true;
        }
        catch(Exception $e) {
            $this->records = null;
            $this->message = env('APP_DEBUG') ? $e->getMessage() : 'Ocurrio un problema.';
            $this->result = false;
        }
        finally {
            $response = [
                'result' => $this->result,
                'message' => $this->message,
                'records' => $this->records
            ];

            return response()->json($response, $this->status_code);
        }
    }

    public function event_detail($event_id, $client_id)
    {
        try {
            $agenda = Agenda::where('event_id', $event_id)->with('detail')->first();
            if($agenda) {
                foreach($agenda->detail as $item) {
                    $agenda_client = EventAgenda::where('agenda_detail_id', $item->id)->where('client_id', $client_id)->first();
                    if($agenda_client) {
                        $item->check = 1;
                    }
                    else {
                        $item->check = 0;
                    }
                }

                $this->records = $agenda;
                $this->message = "Agenda consultada.";
                $this->result = true;
            }
            else {
                $this->message = "No hay agenda para este evento.";
                $this->result = true;
            }
        }
        catch(Exception $e) {
            $this->records = null;
            $this->message = env('APP_DEBUG') ? $e->getMessage() : 'Ocurrio un problema.';
            $this->result = false;
        }
        finally {
            $response = [
                'result' => $this->result,
                'message' => $this->message,
                'records' => $this->records
            ];

            return response()->json($response, $this->status_code);
        }
    }

    public function add_agenda(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'agenda_detail_id' => 'required',
                'client_id' => 'required'
            ]);

            if($validator->fails()) {
                $this->message = $validator->errors()->first();
            }
            else {
                $agenda = AgendaDetail::find($request->input('agenda_detail_id'));
                $compare = EventAgenda::leftJoin('agenda_detail','agenda_detail.id', '=', 'event_agenda.agenda_detail_id')
                                        ->whereRaw('agenda_detail.start_time = ? AND agenda_detail.end_time = ?', [$agenda->start_time, $agenda->end_time])
                                        ->first();
                if(!$compare) {
                    $event = EventAgenda::create($request->all());
                    // Notifications::create([
                    //     'client_id' => $request->input('client_id'),
                    //     'message' => $agenda->activity,
                    //     'date_time' => $agenda->start_time
                    // ]);
                    $this->records = $event;
                    $this->message = "Actividad agregada.";
                    $this->result = true;
                }
                else {
                    $this->message = "Ya cuentas con una actividad agregada a la misma hora.";
                    $this->result = false;
                }
            }
        }
        catch(Exception $e) {
            $this->records = null;
            $this->message = env('APP_DEBUG') ? $e->getMessage() : 'Ocurrio un problema.';
            $this->result = false;
        }
        finally {
            $response = [
                'result' => $this->result,
                'message' => $this->message,
                'records' => $this->records
            ];

            return response()->json($response, $this->status_code);
        }
    }

    public function upload_photos(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required',
                'client_id' => 'required',
                'foto' => 'required'
            ]);

            if($validator->fails()) {
                $this->message = $validator->errors()->first();
            }
            else {
                $imagen = $request->file('foto');
                $nombre_imagen = $request->input('event_id').'/'.time().'.'.$imagen->getClientOriginalExtension();
                Storage::disk('photos')->put($nombre_imagen,File::get($imagen), 'public');
                $request->merge(['photo' => 'photos/'.$nombre_imagen]);

                $this->records = EventsPhotos::create($request->all());
                $this->message = "Fotografía cargada correctamente.";
                $this->result = true;
            }
        }
        catch(Exception $e) {
            $this->records = null;
            $this->message = env('APP_DEBUG') ? $e->getMessage() : 'Ocurrio un problema.';
            $this->result = false;
        }
        finally {
            $response = [
                'result' => $this->result,
                'message' => $this->message,
                'records' => $this->records
            ];

            return response()->json($response, $this->status_code);
        }
    }

    public function photo_gallery($event_id, $client_id)
    {
        try {
            $this->records = EventsPhotos::where('event_id', $event_id)->where('client_id', $client_id)->get();
            $this->message = "Fotografías consultadas.";
            $this->result = true;
        }
        catch(Exception $e) {
            $this->records = null;
            $this->message = env('APP_DEBUG') ? $e->getMessage() : 'Ocurrio un problema.';
            $this->result = false;
        }
        finally {
            $response = [
                'result' => $this->result,
                'message' => $this->message,
                'records' => $this->records
            ];

            return response()->json($response, $this->status_code);
        }
    }
}
