<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientApiIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_create_a_client_successfully()
    {
        $response = $this->postJson('/api/clients', [
            'full_name' => 'Iago Severino das Neves',
            'cpf'       => '12345678909',
            'email'     => 'iago.neves@example.com',
            'phone'     => '41991471309',
            'cep'       => '81280-350'
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'message' => 'Client created successfully.'
                 ]);

        $this->assertDatabaseHas('clients', [
            'cpf'   => '12345678909',
            'email' => 'iago.neves@example.com'
        ]);
    }

    public function test_should_not_allow_duplicate_cpf()
    {
        // cria um cliente normalmente
        $this->postJson('/api/clients', [
            'full_name' => 'Iago Severino das Neves',
            'cpf'       => '12345678909',
            'email'     => 'iago.neves@example.com',
            'phone'     => '41991471309',
            'cep'       => '81280-350'
        ]);

        // tenta cadastrar outro cliente com o mesmo CPF
        $response = $this->postJson('/api/clients', [
            'full_name' => 'Breno Carlos Yago da Costa',
            'cpf'       => '12345678909',
            'email'     => 'breno.carlos@example.com',
            'phone'     => '41991283301',
            'cep'       => '81320-000'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['cpf']);
    }

    public function test_should_not_allow_invalid_cpf()
    {
        // tenta cadastrar um cliente com CPF inválido
        $response = $this->postJson('/api/clients', [
            'full_name' => 'Bruno Marques Ferreira',
            'cpf'       => '12345678900',
            'email'     => 'bruno.ferreira@example.com',
            'phone'     => '41991887766',
            'cep'       => '81280-350'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['cpf']);
    }

    public function test_should_not_allow_duplicate_email()
    {
        // cria o cliente normalmente
        $this->postJson('/api/clients', [
            'full_name' => 'Vinícius Henrique Lima',
            'cpf'       => '39053344705',
            'email'     => 'vinicius.lima@example.com',
            'phone'     => '41999987766',
            'cep'       => '81280-350'
        ]);

        // tenta cadastrar outro cliente com o mesmo e-mail (mas CPF diferente)
        $response = $this->postJson('/api/clients', [
            'full_name' => 'Carlos Eduardo Pinto',
            'cpf'       => '12345678909',
            'email'     => 'vinicius.lima@example.com',
            'phone'     => '41991776655',
            'cep'       => '81280-350'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    public function test_should_not_allow_invalid_cep()
    {
        // tenta cadastrar um cliente com CEP inválido
        $response = $this->postJson('/api/clients', [
            'full_name' => 'Eduardo Ramos Teixeira',
            'cpf'       => '39053344705',
            'email'     => 'eduardo.teixeira@example.com',
            'phone'     => '41991556644',
            'cep'       => '00000-000'
        ]);

        $response->assertStatus(422)
                ->assertJsonFragment([
                    'message' => 'Invalid CEP. Address could not be retrieved.'
                ]);
    }

    public function test_should_update_a_client_successfully()
    {
        
        $createResponse = $this->postJson('/api/clients', [
            'full_name' => 'Marcos Vinícius Pereira',
            'cpf'       => '39053344705',
            'email'     => 'marcos.pereira@example.com',
            'phone'     => '41999887766',
            'cep'       => '81280-350'
        ]);

        $clientId = $createResponse->json('data.id');

        // alterando só o nome do cliente
        $updateResponse = $this->patchJson("/api/clients/{$clientId}", [
            'full_name' => 'Marcos Vinícius Atualizado'
        ]);

        $updateResponse->assertStatus(200)
        ->assertJson([
            'message' => 'Client updated successfully.',
            'data'    => [
                'full_name' => 'Marcos Vinícius Atualizado'
            ]
        ]);

        $this->assertDatabaseHas('clients', [
            'id'        => $clientId,
            'full_name' => 'Marcos Vinícius Atualizado'
        ]);
    }

}
