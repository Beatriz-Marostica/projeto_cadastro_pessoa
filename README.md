# Tutorial de Configuração e Desenvolvimento com o Framework Laravel utilizando PostgreSQL
<br>
<br>
1. Objetivo

O presente tutorial tem como objetivo orientar a configuração do ambiente de desenvolvimento para utilização do framework Laravel, bem como a criação de uma aplicação backend integrada ao banco de dados PostgreSQL.
Ao final do processo, espera-se que o ambiente esteja completamente funcional, com o projeto Laravel criado, a conexão com o banco de dados estabelecida e uma aplicação backend capaz de realizar operações de cadastro, consulta, atualização e exclusão de dados.
<br>
<br>
<br>
2. Pré-requisitos

Antes de iniciar a configuração, é necessário que os seguintes softwares estejam previamente instalados no computador:

* PHP na versão 8.0 ou superior
* Composer
* Node.js e NPM
* Sistema gerenciador de banco de dados PostgreSQL
<br>
<br>
3. Criação do banco de dados

Após a instalação do PostgreSQL, deve-se criar o banco de dados que será utilizado pela aplicação.

No terminal, execute o comando:

```psql -U postgres```

Após inserir a senha, execute:

```CREATE DATABASE cadastroPessoas;```

Para sair do terminal do PostgreSQL:

```\q```

Caso o usuário utilize o pgAdmin, o banco também pode ser criado por meio da interface gráfica.
<br>
<br>
<br>
4. Instalação do Laravel Installer

Para facilitar a criação de projetos Laravel, é recomendada a instalação do Laravel Installer.

No terminal, execute:

```composer global require laravel/installer```

Em alguns casos, será necessário adicionar o diretório global do Composer à variável de ambiente PATH. No sistema Windows, esse diretório geralmente se encontra em:

```C:\Users\SEU_USUARIO\AppData\Roaming\Composer\vendor\bin```

Para verificar se a instalação foi realizada corretamente, execute:

```laravel --version```
<br>
<br>
<br>
5. Criação do projeto Laravel

Acesse, via terminal, o diretório onde deseja criar o projeto e execute:

```laravel new cadastroPessoas```

Em seguida, acesse a pasta criada:

```cd cadastroPessoas```
<br>
<br>
<br>
6. Configuração do arquivo .env

No diretório do projeto, abra o arquivo .env e configure a conexão com o banco PostgreSQL da seguinte forma:


```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cadastroPessoas
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
```

Substitua o valor de sua_senha pela senha definida no PostgreSQL.
<br>
<br>
<br>
7. Teste da conexão com o banco de dados

Para validar a conexão com o banco e criar as tabelas padrão do Laravel, execute:

```php artisan migrate```

Caso o processo seja concluído com sucesso, as tabelas padrão serão criadas no banco.

Para verificar o status das migrations:

```php artisan migrate:status```
<br>
<br>
<br>
8. Execução da aplicação

Para iniciar o servidor local do Laravel, execute:

```php artisan serve```
<br>
<br>
<br>
9. Estrutura inicial do projeto

Após as etapas anteriores, o ambiente estará preparado com:

* Projeto Laravel criado
* Banco de dados PostgreSQL configurado
* Tabelas padrão geradas
* Servidor local em execução
<br>
<br>
10. Criação da estrutura do CRUD

Para criar os arquivos necessários para a entidade Pessoa, execute:

```php artisan make:model Pessoa -mcr```

Esse comando gera automaticamente a model, a migration e o controller resource.
<br>
<br>
<br>
11. Configuração da migration

Localize o arquivo de migration gerado no diretório database/migrations e ajuste o método up() conforme o exemplo:

``` env
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
```
<br>
<br>
<br>
12. Execução da migration

Após configurar a estrutura da tabela, execute novamente:

```php artisan migrate```

A tabela pessoas será criada no banco de dados.
<br>
<br>
<br>
13. Configuração da model

No arquivo app/Models/Pessoa.php, configure os atributos permitidos para inserção em massa:

```env
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
```
<br>
<br>
<br>
14. Configuração do controller

No arquivo PessoaController, implemente os métodos necessários para as operações de CRUD, incluindo validações e retorno de dados em formato JSON.

``` env
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
```
<br>
<br>
<br>
15. Configuração das rotas

No arquivo routes/api.php, defina as rotas da API:

``` env
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PessoaController;

Route::apiResource('pessoas', PessoaController::class);
```

Esse comando cria automaticamente rotas para listagem, criação, atualização, exclusão e visualização de registros.
<br>
<br>
<br>
16. Teste dos endpoints

Com o servidor em execução, os endpoints podem ser acessados por meio de ferramentas como Postman.

Exemplo de endpoint:

```http://127.0.0.1:8000/api/pessoas```
<br>
<br>
<br>
17. Resultado obtido

Após a conclusão das etapas, a aplicação estará apta a realizar:

* Cadastro de pessoas
* Listagem de registros
* Atualização de dados
* Exclusão de registros
* Consulta individual
* Validação de campos obrigatórios
* Retorno de dados em formato JSON
<br>
<br>
18. Conclusão

Nesta etapa, foi realizada a configuração completa do ambiente de desenvolvimento utilizando Laravel e PostgreSQL, bem como a implementação de uma aplicação backend com funcionalidades de CRUD.
