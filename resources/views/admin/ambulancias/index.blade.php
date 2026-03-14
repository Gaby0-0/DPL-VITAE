@extends('layouts.admin')

@section('title', 'Ambulancias')
@section('header', 'AMBULANCIAS')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <h3 class="text-2xl font-semibold">Listado de ambulancias</h3>

    <!-- aqui va el boton de crear ambulancia jsjs-->
       class="bg-[#d90000] text-white px-5 py-3 rounded-xl hover:bg-red-800 transition">
        Registrar ambulancia
</div>

<div class="bg-white rounded-2xl shadow-md overflow-hidden">

<table class="w-full text-left">

<thead class="bg-[#d90000] text-white">
<tr>
<th class="px-6 py-4">ID</th>
<th class="px-6 py-4">Placa</th>
<th class="px-6 py-4">Estado</th>
<th class="px-6 py-4">Tipo</th>
<th class="px-6 py-4">Operador</th>
<th class="px-6 py-4">Acciones</th>
</tr>
</thead>

<tbody class="divide-y divide-gray-200">

@forelse($ambulancias as $a)

<tr class="hover:bg-gray-50">

<td class="px-6 py-4">{{ $a->id_ambulancia }}</td>

<td class="px-6 py-4">{{ $a->placa }}</td>

<td class="px-6 py-4">{{ $a->estado }}</td>

<td class="px-6 py-4">{{ $a->tipo->nombre_tipo ?? 'N/A' }}</td>

<td class="px-6 py-4">{{ $a->operador->id_usuario ?? 'N/A' }}</td>

<td class="px-6 py-4 flex gap-3">

<a href="{{ route('ambulancias.edit',$a->id_ambulancia) }}"
class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg">
Editar
</a>

<form action="{{ route('ambulancias.destroy',$a->id_ambulancia) }}" method="POST">

@csrf
@method('DELETE')

<button class="bg-red-100 text-red-700 px-3 py-1 rounded-lg">
Eliminar
</button>

</form>

</td>

</tr>

@empty

<tr>
<td colspan="6" class="px-6 py-4 text-center text-gray-500">
No hay ambulancias registradas.
</td>
</tr>

@endforelse

</tbody>
</table>

</div>

@endsection