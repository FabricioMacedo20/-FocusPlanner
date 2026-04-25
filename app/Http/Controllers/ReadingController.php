<?php
//  Controlador de leitura: Gerenciar livros que o usuário está lendo, progresso e marcação de conclusão
namespace App\Http\Controllers;

use App\Models\Reading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadingController extends Controller
{
    public function __construct()
    {
        //  Middleware de autenticação obrigatório para acessar leituras
        // Conexão: Auth Middleware → esse controller → protege todas as rotas
        $this->middleware('auth');
    }

    public function index()
    {
        //  LISTAR: Mostra todos os livros do usuário com progresso de páginas (apenas ativos)
        // Exibido em: resources/views/readings/index.blade.php
        // Na view: percentual = (current_page / total_pages) * 100
        $readings = Reading::where('user_id', Auth::id())->where('completed', false)->get();
        $completedReadings = Reading::where('user_id', Auth::id())->where('completed', true)->get();

        return view('readings.index', compact('readings', 'completedReadings'));
    }

    public function create()
    {
        return view('readings.create');
    }

    public function store(Request $request)
    {
        //  VALIDAÇÕES antes de criar registro de leitura:
        //   - book_title: obrigatório, string, máximo 255 caracteres
        //   - total_pages: obrigatório, inteiro, mínimo 1 (página)
        //   - current_page: opcional, inteiro, mínimo 0 (começa do zero)
        $request->validate([
            'book_title' => 'required|string|max:255',
            'total_pages' => 'required|integer|min:1',
            'current_page' => 'nullable|integer|min:0',
        ]);

        //  GRAVA: Novo livro com dados do usuário logado
        // Relacionamento: User (logado) → via Auth::id() → Reading::user_id
        // Progresso será calculado na view: (current_page / total_pages) * 100
        Reading::create([
            'user_id' => Auth::id(),
            'book_title' => $request->book_title,
            'total_pages' => $request->total_pages,
            'current_page' => $request->current_page ?? 0,  // Default: 0 páginas lidas
        ]);

        return redirect()->route('readings.index');
    }

    public function edit(Reading $reading)
    {
        //  SEGURANÇA: Verifica se o livro pertence AO USUÁRIO logado
        if ($reading->user_id !== Auth::id()) {
            abort(403);  // HTTP 403: Forbidden (não autorizado)
        }

        return view('readings.edit', compact('reading'));
    }

    public function update(Request $request, Reading $reading)
    {
        //  SEGURANÇA: Valida propriedade do livro antes de atualizar
        if ($reading->user_id !== Auth::id()) {
            abort(403);
        }

        //  VALIDAÇÕES de atualização
        $request->validate([
            'book_title' => 'required|string|max:255',
            'total_pages' => 'required|integer|min:1',
            'current_page' => 'required|integer|min:0',  // OBRIGATÓRIO para atualizar
        ]);

        //  ATUALIZA: Usuário incrementa páginas conforme lê o livro
        // Exemplo: Li 10... 25... 50... até alcançar total_pages
        // Progresso atualizado automaticamente na view
        $reading->update([
            'book_title' => $request->book_title,
            'total_pages' => $request->total_pages,
            'current_page' => $request->current_page,
        ]);

        return redirect()->route('readings.index');
    }

    public function destroy(Reading $reading)
    {
        if ($reading->user_id !== Auth::id()) {
            abort(403);
        }

        $reading->delete();

        return redirect()->route('readings.index');
    }

    public function complete(Reading $reading)
    {
        //  SEGURANÇA: Verifica se o livro pertence AO USUÁRIO logado
        if ($reading->user_id !== Auth::id()) {
            abort(403);  // HTTP 403: Forbidden (não autorizado)
        }

        //  MARCA COMO CONCLUÍDO: Define current_page como total_pages e completed como true
        $reading->update([
            'current_page' => $reading->total_pages,
            'completed' => true,
        ]);

        return redirect()->route('readings.index');
    }
}
