<?php
// Gerenciamento de cursos: Permitir que os usuários criem, editem, visualizem e excluam cursos, além de acompanhar o progresso em cada curso
namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function __construct()
    {
        // 🔐 Middleware de autenticação obrigatório para acessar cursos
        // Conexão: Auth Middleware → esse controller → protege todas as rotas
        $this->middleware('auth');
    }

    public function index()
    {
        // 📋 LISTAR: Mostra todos os cursos do usuário com progresso (%)
        // Exibido em: resources/views/courses/index.blade.php
        // Cada curso tem um progresso de 0-100% com barra visual no Tailwind
        $courses = Course::where('user_id', Auth::id())->get();

        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        // 🔈 VALIDAÇÕES antes de criar curso:
        //   - name: obrigatório, string, máximo 255 caracteres
        //   - progress: opcional, inteiro de 0 a 100 (porcentagem)
        //   - content: opcional, string
        $request->validate([
            'name' => 'required|string|max:255',
            'progress' => 'nullable|integer|min:0|max:100',
            'content' => 'nullable|string',
        ]);

        // 📌 GRAVA: Novo curso com dados do usuário logado
        // Relacionamento: User (logado) → via Auth::id() → Course::user_id
        // Barra de progresso na view calcula: width = progress%
        Course::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'progress' => $request->progress ?? 0,  // Default: 0% de progresso
            'content' => $request->content,
        ]);

        return redirect()->route('courses.index');
    }

    public function edit(Course $course)
    {
        // 🔐 SEGURANÇA: Verifica se o curso pertence AO USUÁRIO logado
        if ($course->user_id !== Auth::id()) {
            abort(403);  // HTTP 403: Forbidden
        }

        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        // 🔐 SEGURANÇA: Valida propriedade do curso
        if ($course->user_id !== Auth::id()) {
            abort(403);
        }

        // 🔈 VALIDAÇÕES de atualização
        $request->validate([
            'name' => 'required|string|max:255',
            'progress' => 'required|integer|min:0|max:100',  // OBRIGATÓRIO para atualizar
            'content' => 'nullable|string',
        ]);

        // 📌 ATUALIZA: Usuário incrementa progresso conforme avança no curso
        // Exemplo: A cada aula concluída, incrementa + 5%, + 10%, etc
        $course->update([
            'name' => $request->name,
            'progress' => $request->progress,
            'content' => $request->content,
        ]);

        return redirect()->route('courses.index');
    }

    public function show(Course $course)
    {
        // 🔐 SEGURANÇA: Verifica se o curso pertence AO USUÁRIO logado
        if ($course->user_id !== Auth::id()) {
            abort(403);  // HTTP 403: Forbidden
        }

        return view('courses.show', compact('course'));
    }
}
