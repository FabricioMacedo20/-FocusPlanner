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
        //  LISTAR: Mostra todos os livros do usuário com progresso de páginas
        // Exibido em: resources/views/readings/index.blade.php
        // Na view: percentual = (current_page / total_pages) * 100
        $activeReadingsQuery = Reading::where('user_id', Auth::id())->where('completed', false);
        $completedReadingsQuery = Reading::where('user_id', Auth::id())->where('completed', true);

        $activeReadings = (clone $activeReadingsQuery)->get();
        $completedReadings = (clone $completedReadingsQuery)->get();
        $readings = $activeReadingsQuery->paginate(5)->withQueryString();

        $totalReadings = $activeReadings->count() + $completedReadings->count();
        $inProgressCount = $activeReadings->count();
        $completedCount = $completedReadings->count();
        $totalPagesRead = $activeReadings->sum('current_page') + $completedReadings->sum('current_page');
        $completionRate = $totalReadings > 0 ? round(($completedCount / $totalReadings) * 100) : 0;

        $featuredReading = $activeReadings->sortByDesc(function ($reading) {
            return $reading->total_pages > 0 ? ($reading->current_page / $reading->total_pages) : 0;
        })->first();

        if ($totalReadings === 0) {
            $contextMessage = 'Você não possui leituras em andamento. Adicione um novo livro para começar.';
        } elseif ($completedCount === $totalReadings) {
            $contextMessage = 'Parabéns! Todas as leituras cadastradas foram concluídas.';
        } else {
            $contextMessage = 'Continue registrando seu progresso para acompanhar sua evolução ao longo do tempo.';
        }

        return view('readings.index', compact('readings', 'completedReadings', 'totalReadings', 'inProgressCount', 'completedCount', 'totalPagesRead', 'completionRate', 'featuredReading', 'contextMessage'));
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
            'book_title' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'total_pages' => 'required|integer|min:1',
            'current_page' => 'nullable|integer|min:0|lte:total_pages',
        ], [
            'book_title.required' => 'O título do livro é obrigatório.',
            'book_title.max' => 'O título do livro não pode ter mais de 255 caracteres.',
            'book_title.not_regex' => 'O título do livro não pode ficar em branco.',
            'total_pages.required' => 'O total de páginas é obrigatório.',
            'total_pages.integer' => 'O total de páginas deve ser um número inteiro.',
            'total_pages.min' => 'O total de páginas deve ser pelo menos 1.',
            'current_page.integer' => 'As páginas lidas devem ser um número inteiro.',
            'current_page.min' => 'As páginas lidas não podem ser negativas.',
            'current_page.lte' => 'As páginas lidas não podem ser maiores que o total de páginas.',
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
            'book_title' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'total_pages' => 'required|integer|min:1',
            'current_page' => 'required|integer|min:0|lte:total_pages',
        ], [
            'book_title.required' => 'O título do livro é obrigatório.',
            'book_title.max' => 'O título do livro não pode ter mais de 255 caracteres.',
            'book_title.not_regex' => 'O título do livro não pode ficar em branco.',
            'total_pages.required' => 'O total de páginas é obrigatório.',
            'total_pages.integer' => 'O total de páginas deve ser um número inteiro.',
            'total_pages.min' => 'O total de páginas deve ser pelo menos 1.',
            'current_page.required' => 'As páginas lidas são obrigatórias.',
            'current_page.integer' => 'As páginas lidas devem ser um número inteiro.',
            'current_page.min' => 'As páginas lidas não podem ser negativas.',
            'current_page.lte' => 'As páginas lidas não podem ser maiores que o total de páginas.',
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
