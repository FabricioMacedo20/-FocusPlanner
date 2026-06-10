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

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'content' => 'nullable|string',
        ], [
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'title.not_regex' => 'O título não pode ficar em branco.',
        ]);

        // Criar nota com user_id, data de hoje e status inicial
        Note::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'date' => Carbon::now()->format('Y-m-d'),  // Data de hoje
            'status' => 'todo',  // Nova nota começa como "A fazer"
            'completed' => false,  // Não está completa
        ]);

        // Redirecionar para o planner após criar nova atividade
        return redirect()->route('planner')->with('success', 'Atividade criada com sucesso!');
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
            'title' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'content' => 'nullable|string',
        ], [
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'title.not_regex' => 'O título não pode ficar em branco.',
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
        // Verificar se a nota pertence ao usuário logado
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        // Marcar nota como completada
        $note->update([
            'completed' => true,
        ]);

        // Redirecionar para planner para manter o fluxo centralizado
        return redirect()->route('planner')->with('success', 'Atividade marcada como concluída!');
    }

    public function destroy(Note $note)
    {
        // Verificar se a nota pertence ao usuário logado
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        // Deletar a nota
        $note->delete();

        // Redirecionar para planner para manter o fluxo centralizado
        return redirect()->route('planner')->with('success', 'Atividade excluída com sucesso!');
    }
}
