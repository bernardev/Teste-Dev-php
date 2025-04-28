<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'cpf', 'email', 'phone', 'cep', 'street', 'neighborhood', 'city', 'state'
    ];

    public static $rules = [
        'full_name' => 'required|string|max:255',
        'cpf' => 'required|string|size:14|unique:clients',
        'email' => 'required|email|unique:clients',
        'phone' => 'nullable|string|max:15',
        'cep' => 'required|string|size:9',
        'street' => 'required|string|max:255',
        'neighborhood' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'state' => 'required|string|max:2',
    ];
}
