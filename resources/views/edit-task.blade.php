<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier une tâche</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen px-4">

  <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
    <h2 class="text-xl font-bold mb-4 text-gray-800">✏️ Modifier la tâche</h2>

    <form method="POST" action="{{ route('tasks.update', $task->id) }}" class="flex flex-col gap-4">
      @csrf
      @method('PUT')
      <input type="text" name="title" value="{{ $task->title }}" class="p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
      <button type="submit" class="bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Mettre à jour</button>
    </form>

    <a href="{{ route('tasks.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mt-4 inline-block">
      ← Retour à la liste
    </a>
  </div>

</body>
</html>
