<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    public function index()
    {
        $pessoas = Pessoa::orderBy('id', 'asc')->get();

        return response()->json($pessoas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'nome' => 'required|string|max:255',
                'cpf' => 'required|string|max:14|unique:pessoas,cpf',
                'telefone' => 'nullable|string|max:20',
                'email' => 'required|email|max:255|unique:pessoas,email',
                'idade' => 'nullable|integer|min:0',
                'estado' => 'required|string|size:2',
                'cidade' => 'required|string|max:255',
                'bairro' => 'nullable|string|max:255',
                'rua' => 'required|string|max:255',
                'numero' => 'nullable|string|max:20',
            ],
            [
                'nome.required' => 'O nome é obrigatório.',
                'cpf.required' => 'O CPF é obrigatório.',
                'cpf.unique' => 'Já existe uma pessoa cadastrada com este CPF.',
                'email.required' => 'O e-mail é obrigatório.',
                'email.email' => 'O e-mail informado é inválido.',
                'email.unique' => 'Já existe uma pessoa cadastrada com este e-mail.',
                'estado.required' => 'O estado é obrigatório.',
                'estado.size' => 'O estado deve ter 2 caracteres.',
                'cidade.required' => 'A cidade é obrigatória.',
                'rua.required' => 'A rua é obrigatória.',
                'idade.integer' => 'A idade deve ser um número inteiro.',
                'idade.min' => 'A idade não pode ser negativa.',
            ]
        );

        $pessoa = Pessoa::create($validated);

        return response()->json([
            'message' => 'Pessoa cadastrada com sucesso.',
            'data' => $pessoa
        ], 201);
    }

    public function show(string $id)
    {
        $pessoa = Pessoa::findOrFail($id);

        return response()->json($pessoa);
    }

    public function update(Request $request, string $id)
    {
        $pessoa = Pessoa::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:pessoas,cpf,' . $pessoa->id,
            'telefone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:pessoas,email,' . $pessoa->id,
            'idade' => 'nullable|integer|min:0',
            'estado' => 'required|string|size:2',
            'cidade' => 'required|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'rua' => 'required|string|max:255',
            'numero' => 'nullable|string|max:20',
        ]);

        $pessoa->update($validated);

        return response()->json([
            'message' => 'Pessoa atualizada com sucesso.',
            'data' => $pessoa
        ]);
    }

    public function destroy(string $id)
    {
        $pessoa = Pessoa::findOrFail($id);
        $pessoa->delete();

        return response()->json([
            'message' => 'Pessoa removida com sucesso.'
        ]);
    }
}