<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Services\BrasilApiService;
use Illuminate\Support\Facades\Cache;


class ClientController extends Controller
{
    protected $clientRepo;
    protected $brasilApi;

    public function __construct(ClientRepository $clientRepo, BrasilApiService $brasilApi)
    {
        $this->clientRepo = $clientRepo;
        $this->brasilApi = $brasilApi;
    }


    public function index(Request $request)
    {
        // filtros virão somente da query string
        $filters = $request->query(); 
        $filters = array_intersect_key($filters, array_flip(['full_name', 'cpf', 'cep', 'per_page']));
    
        $cacheKey = 'clients_list_' . md5(http_build_query($filters));
    
        $clients = Cache::remember($cacheKey, 60, function () use ($filters) {
            return $this->clientRepo->all($filters);
        });
    
        return response()->json([
            'message' => 'Clients retrieved successfully.',
            'data'    => $clients->items(),
            'pagination' => [
                'current_page' => $clients->currentPage(),
                'per_page'     => $clients->perPage(),
                'total'        => $clients->total(),
                'last_page'    => $clients->lastPage(),
            ]
        ]);
    }
    
    
    
    public function store(ClientRequest $request)
    {
        $data = $request->validated();

        $address = $this->brasilApi->getAddressByCep($data['cep']);

        if (!$address) {
            return response()->json([
                'message' => 'Invalid CEP. Address could not be retrieved.'
            ], 422);
        }

        // dados da BrasilAPI
        $data['street']       = $address['street'] ?? '';
        $data['neighborhood'] = $address['neighborhood'] ?? '';
        $data['city']         = $address['city'] ?? '';
        $data['state']        = $address['state'] ?? '';

        $client = $this->clientRepo->create($data);

        Cache::forget('clients_list');

        return response()->json([
            'message' => 'Client created successfully.',
            'data'    => $client
        ], 201);
    }
        
    public function show($id)
    {
        $client = $this->clientRepo->find($id);
    
        if (!$client) {
            return response()->json([
                'message' => 'Client not found.'
            ], 404);
        }

        Cache::forget('clients_list');
    
        return response()->json([
            'message' => 'Client retrieved successfully.',
            'data'    => $client
        ]);
    }
    
    public function update(ClientRequest $request, $id)
    {
        $client = $this->clientRepo->find($id);
    
        if (!$client) {
            return response()->json([
                'message' => 'Client not found.'
            ], 404);
        }
    
        $data = $request->validated();
    
        // só valida o endereço se o cep for enviado
        if (array_key_exists('cep', $data)) {
            $address = $this->brasilApi->getAddressByCep($data['cep']);
    
            if (!$address) {
                return response()->json([
                    'message' => 'Invalid CEP. Address could not be retrieved.'
                ], 422);
            }
    
            $data['street']       = $address['street'] ?? '';
            $data['neighborhood'] = $address['neighborhood'] ?? '';
            $data['city']         = $address['city'] ?? '';
            $data['state']        = $address['state'] ?? '';
        }
    
        $client = $this->clientRepo->update($client, $data);

        Cache::forget('clients_list');
    
        return response()->json([
            'message' => 'Client updated successfully.',
            'data'    => $client
        ]);
    }
    
    
    public function destroy($id)
    {
        $client = $this->clientRepo->find($id);
    
        if (!$client) {
            return response()->json([
                'message' => 'Client not found.'
            ], 404);
        }
    
        $this->clientRepo->delete($client);

        Cache::forget('clients_list');
    
        return response()->json([
            'message' => 'Client deleted successfully.'
        ], 200);
    }
}
