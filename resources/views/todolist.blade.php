<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ma TodoList</title>
  @vite('resources/css/app.css')

  <!-- Font Awesome pour les icônes -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  
  <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(-10px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-in {
      animation: fade-in 0.3s ease-out forwards;
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen py-10 px-4">

  @php
    $active = request('filter') ?? 'all';
  @endphp

  <div class="w-full max-w-2xl mx-auto space-y-6">

    <!-- ✅ Notification de succès -->
    @if(session('success'))
      <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded shadow">
        {{ session('success') }}
      </div>
    @endif

    <!-- ➕ Formulaire d'ajout -->
    <div class="bg-blue-50 p-6 rounded shadow hover:shadow-md transition">
      <h2 class="text-xl font-bold mb-4 text-blue-700">Ajouter une tâche</h2>
      <form method="POST" action="{{ route('tasks.store') }}" class="flex flex-col gap-4">
        @csrf
        <input type="text" name="title" placeholder="Nouvelle tâche..." class="p-3 rounded border focus:ring-2 focus:ring-blue-400">
        <button type="submit" class="bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Ajouter</button>
      </form>
    </div>

    <!-- 📋 Liste des tâches -->
    <div class="bg-white p-6 rounded shadow hover:shadow-md transition">
      <h2 class="text-xl font-bold mb-4 text-gray-800">Mes tâches</h2>

      <!-- 🔍 Filtres -->
      <div class="hidden md:flex gap-4 mb-4 text-sm text-gray-600 justify-center">
        <a href="?filter=all" class="{{ $active === 'all' ? 'text-blue-600 font-semibold' : 'hover:text-blue-500' }}"> <i class="fas fa-list"></i> Toutes</a>
        <a href="?filter=todo" class="{{ $active === 'todo' ? 'text-blue-600 font-semibold' : 'hover:text-blue-500' }}"> <i class="fas fa-hourglass-start"></i> À faire</a>
        <a href="?filter=done" class="{{ $active === 'done' ? 'text-blue-600 font-semibold' : 'hover:text-blue-500' }}"> <i class="fas fa-check-circle"></i> Terminées</a>
      </div>

      @if($tasks->count())
        <ul class="space-y-4">
          @foreach($tasks as $task)
            <li class="flex justify-between items-center border p-3 rounded fade-in hover:shadow-sm transition">
              <div class="flex items-center gap-3">
                <!-- ✅ Case à cocher -->
                <form method="POST" action="{{ route('tasks.toggle', $task->id) }}">
                  @csrf
                  @method('PATCH')
                  <input type="checkbox" onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
                </form>

                <!-- 🖋️ Titre -->
                <span class="{{ $task->completed ? 'line-through text-gray-400' : 'text-gray-700' }}">
                  {{ $task->title }}
                </span>
              </div>
              <div class="flex items-center gap-2">
                <!-- 🔧 Modifier -->
                <a href="{{ route('tasks.edit', $task->id) }}" class="text-blue-500 hover:text-blue-700">
                  ✏️
                </a>

                <!-- 🗑️ Supprimer -->
                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?');">
                  @csrf
                  @method('DELETE')
                  <button class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
              </div>

            </li>
          @endforeach
        </ul>
      @else
        <p class="text-gray-500 text-center">Aucune tâche pour le moment.</p>
      @endif
    </div>

  </div>

  <!-- 📌 Barre de filtres fixe -->
<div class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-md p-3 md:hidden flex justify-around text-sm text-gray-600 z-50">
  <a href="?filter=all" class="hover:text-blue-600 flex items-center gap-1">
    <i class="fas fa-list"></i> Toutes
  </a>
  <a href="?filter=todo" class="hover:text-blue-600 flex items-center gap-1">
    <i class="fas fa-hourglass-start"></i> À faire
  </a>
  <a href="?filter=done" class="hover:text-blue-600 flex items-center gap-1">
    <i class="fas fa-check-circle"></i> Terminées
  </a>
</div>


</body>
</html>
