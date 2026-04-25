<?php
//  Controlador de notas: Gerenciar criação, edição, marcação como importante e conclusão de notas diárias
namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NoteController extends Controller
{
    public function __construct()
    {
        //  Middleware de autenticação: obrigatório estar logado
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::now()->format('Y-m-d');

        // Buscar todas as notas do dia atual do usuário
        $notes = Note::where('user_id', $userId)
                    ->where('date', $today)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('notes.index', compact('notes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        Note::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'date' => Carbon::now()->format('Y-m-d'),
            'status' => 'todo', // Nova nota começa como "A fazer"
        ]);

        return redirect()->back()->with('success', 'Nota criada com sucesso!');
    }

    //  SALVAR NOTA: Atualizar conteúdo da nota
    public function update(Request $request, Note $note)
    {
        //  Verificar propriedade
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        //  Validação
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        //  Atualizar título e conteúdo
        $note->update([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return redirect()->back()->with('success', 'Nota salva com sucesso!');
    }

    public function toggleImportant(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $note->important = !$note->important;
        $note->save();

        $message = $note->important ? 'Nota priorizada com sucesso!' : 'Prioridade removida com sucesso!';
        return redirect()->back()->with('success', $message);
    }

    public function complete(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $note->update([
            'completed' => true,
        ]);

        return redirect()->back()->with('success', 'Atividade do dia marcada como concluída!');
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $note->delete();

        return redirect()->back()->with('success', 'Atividade excluída com sucesso!');
    }
}
