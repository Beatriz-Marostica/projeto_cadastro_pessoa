Tutorial Laravel + PostgreSQL
Objetivo

Este tutorial orienta a configuração de um ambiente de desenvolvimento com Laravel integrado ao PostgreSQL, além da criação de uma API backend com operações CRUD.

Ao final, você terá:

Projeto Laravel funcional
Banco PostgreSQL configurado
CRUD completo com validações
API retornando JSON
Softwares
- PHP 8.0 ou superior
- Composer
- Node.js e NPM
- PostgreSQL

- IDE:
  - IntelliJ IDEA
  - Spring Tools Suite
  - Eclipse for JavaEE, etc...

- Ferramenta para testar a API:
  - Postman
  - Insomnia

- Git
- Docker
Criação do Banco de Dados
psql -U postgres
CREATE DATABASE cadastroPessoas;
\q
Instalação do Laravel Installer
composer global require laravel/installer

Verifique:

laravel --version

Caso necessário, adicione ao PATH:

C:\Users\SEU_USUARIO\AppData\Roaming\Composer\vendor\bin
Criação do Projeto
laravel new cadastroPessoas
cd cadastroPessoas
Configuração do .env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cadastroPessoas
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
Teste de Conexão
php artisan migrate
php artisan migrate:status
Execução da Aplicação
php artisan serve
Criação do CRUD
php artisan make:model Pessoa -mcr
Migration
Schema::create('pessoas', function (Blueprint $table) {
    $table->id();
    $table->string('nome');
    $table->string('cpf', 14)->unique();
    $table->string('telefone', 20)->nullable();
    $table->string('email')->unique();
    $table->integer('idade')->nullable();
    $table->string('estado', 2);
    $table->string('cidade');
    $table->string('bairro')->nullable();
    $table->string('rua');
    $table->string('numero', 20)->nullable();
    $table->timestamps();
});

Executar:

php artisan migrate
Model
class Pessoa extends Model
{
    protected $table = 'pessoas';

    protected $fillable = [
        'nome',
        'cpf',
        'telefone',
        'email',
        'idade',
        'estado',
        'cidade',
        'bairro',
        'rua',
        'numero',
    ];
}
Controller
namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    public function index()
    {
        return response()->json(
            Pessoa::orderBy('id', 'asc')->get()
        );
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
        return response()->json(
            Pessoa::findOrFail($id)
        );
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
        Pessoa::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Pessoa removida com sucesso.'
        ]);
    }
}
Rotas
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PessoaController;

Route::apiResource('pessoas', PessoaController::class);
Teste dos Endpoints

Exemplo:

http://127.0.0.1:8000/api/pessoas

Ferramentas recomendadas:

Postman
Insomnia
Resultado

A aplicação permite:

Cadastro de pessoas
Listagem de registros
Atualização de dados
Exclusão de registros
Consulta individual
Validação de campos
Retorno em JSON
Conclusão

Ambiente Laravel com PostgreSQL configurado e API CRUD funcional, pronto para evolução e integração com frontend.
