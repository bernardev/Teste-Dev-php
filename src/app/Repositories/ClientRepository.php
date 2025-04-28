<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    public function create(array $data)
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data)
    {
        $client->update($data);
        return $client; // Alterado para retornar o cliente atualizado
    }

    public function delete(Client $client)
    {
        return $client->delete();
    }

    public function all($filters = [])
    {
        $query = Client::query();

        if (!empty($filters['full_name'])) {
            $query->where('full_name', 'like', $filters['full_name'] . '%');
        }

        if (!empty($filters['cpf'])) {
            $query->where('cpf', $filters['cpf']);
        }

        if (!empty($filters['cep'])) {
            $query->where('cep', $filters['cep']);
        }

        $perPage = $filters['per_page'] ?? 10; // Valor padrão de 10 registros por página
        return $query->paginate($perPage);
    }

    public function find($id)
    {
        return Client::find($id);
    }
}
