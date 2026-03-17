@extends('layouts.admin')

@section('title', 'Insumos')
@section('header', 'INSUMOS')

@section('content')

{{-- Registro --}}

<div class="mb-6">
    <h3 class="text-base font-semibold text-gray-600 mb-3">Registro</h3>
    <form action="{{ route('insumos.store') }}" method="POST" class="flex items-end gap-4 flex-wrap">
        @csrf

    <div class="flex flex-col gap-1">
        <label class="text-sm font-semibold text-gray-700">Nombre</label>
        <input type="text" name="nombre_insumo" value="{{ old('nombre_insumo') }}"
               class="border border-gray-300 rounded-md px-3 h-10 text-sm focus:outline-none focus:border-[#d90000] w-56"/>
    </div>

    <div class="flex flex-col gap-1">
        <label class="text-sm font-semibold text-gray-700">Costo por unidad</label>
        <input type="number" step="0.01" name="costo_unidad" value="{{ old('costo_unidad') }}"
               class="border border-gray-300 rounded-md px-3 h-10 text-sm focus:outline-none focus:border-[#d90000] w-44"/>
    </div>

    <button type="submit"
            class="h-10 px-6 bg-[#8ab4c8] hover:bg-[#6a9ab8] text-white text-sm font-semibold rounded-md transition">
        Registrar
    </button>
</form>

@error('nombre_insumo')
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror
@error('costo_unidad')
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror

</div>

{{-- Alertas --}}
@if(session('success')) <div class="mb-4 p-4 rounded-xl bg-green-100 text-green-700 text-sm">
{{ session('success') }} </div>
@endif

@if(session('error')) <div class="mb-4 p-4 rounded-xl bg-red-100 text-red-700 text-sm">
{{ session('error') }} </div>
@endif

{{-- Inventario --}}

<div>
    <div class="flex justify-between items-center mb-3">
        <h3 class="text-base font-semibold text-gray-600">Inventario</h3>
        <input type="text" id="buscador" placeholder="Buscar..."
               onkeyup="filtrarTabla()"
               class="bg-gray-200 rounded-full px-4 py-2 text-sm outline-none w-64 placeholder-gray-500"/>
    </div>

<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    <div class="max-h-[500px] overflow-y-auto">
        <table class="w-full text-left" id="tablaInsumos">

            <thead class="bg-[#d90000] text-white sticky top-0 z-10">
                <tr>
                    <th class="px-4 py-3 w-10"></th>
                    <th class="px-6 py-3 text-sm font-bold">Nombre</th>
                    <th class="px-6 py-3 text-sm font-bold">Costo por unidad</th>
                    <th class="px-6 py-3 text-sm font-bold text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

            @forelse($insumos as $insumo)

            <tr class="hover:bg-gray-50">

                <td class="px-4 py-3">
                    <input type="checkbox" class="w-4 h-4 accent-[#d90000]"/>
                </td>

                <td class="px-6 py-3 text-sm">
                    {{ $insumo->nombre_insumo }}
                </td>

                <td class="px-6 py-3 text-sm">
                    ${{ number_format($insumo->costo_unidad, 2) }}
                </td>

                <td class="px-6 py-3 text-sm text-center">

                    <div class="flex justify-center gap-3">

                        {{-- Ver --}}
                        <button onclick="abrirModalVer({{ $insumo->id_insumo }})"
                                class="text-gray-500 hover:text-[#d90000] transition"
                                title="Ver">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>

                            </svg>

                        </button>

                        {{-- Editar --}}
                        <button onclick="abrirModalEditar({{ $insumo->id_insumo }})"
                                class="text-gray-500 hover:text-[#d90000] transition"
                                title="Editar">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>

                            </svg>

                        </button>

                        {{-- Eliminar --}}
                        <form action="{{ route('insumos.destroy', $insumo->id_insumo) }}"
                              method="POST"
                              onsubmit="event.preventDefault(); abrirModal(this)">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="text-gray-500 hover:text-[#d90000] transition"
                                    title="Eliminar">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                    <path d="M10 11v6"/>
                                    <path d="M14 11v6"/>
                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>

                                </svg>

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

            @empty

            <tr>
                <td colspan="4" class="px-6 py-6 text-center text-gray-500 text-sm">
                    No hay insumos registrados.
                </td>
            </tr>

            @endforelse

        </tbody>

        </table>
    </div>
</div>

</div>

{{-- Modal eliminar --}}
<div id="modalEliminar"
     class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

    <div class="bg-white rounded-xl shadow-lg p-6 w-80 text-center">

        <h2 class="text-lg font-semibold text-gray-700 mb-3">
            Confirmar eliminación
        </h2>

        <p class="text-sm text-gray-500 mb-6">
            ¿Estás seguro de eliminar este insumo?
        </p>

        <div class="flex justify-center gap-4">

            <button onclick="cerrarModalEliminar()"
                class="px-4 py-2 rounded-md bg-gray-300 hover:bg-gray-400 text-sm">
                Cancelar
            </button>

            <button onclick="confirmarEliminar()"
                class="px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white text-sm">
                Eliminar
            </button>

        </div>

    </div>

</div>

{{-- Modal ver --}}
<div id="modalVer"
     class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

    <div class="bg-white rounded-xl shadow-lg p-6 w-96">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-700">
                Detalles del Insumo
            </h2>
            <button onclick="cerrarModalVer()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="space-y-3">
            <div>
                <label class="text-sm font-semibold text-gray-600">Nombre:</label>
                <p id="verNombre" class="text-sm text-gray-800 mt-1"></p>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-600">Costo por unidad:</label>
                <p id="verCosto" class="text-sm text-gray-800 mt-1"></p>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button onclick="cerrarModalVer()"
                class="px-4 py-2 rounded-md bg-gray-300 hover:bg-gray-400 text-sm">
                Cerrar
            </button>
        </div>

    </div>

</div>

{{-- Modal editar --}}
<div id="modalEditar"
     class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

    <div class="bg-white rounded-xl shadow-lg p-6 w-96">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-700">
                Editar Insumo
            </h2>
            <button onclick="cerrarModalEditar()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="formEditar" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="text-sm font-semibold text-gray-700">Nombre</label>
                <input type="text" id="editNombre" name="nombre_insumo"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-[#d90000] mt-1"
                       required/>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Costo por unidad</label>
                <input type="number" step="0.01" id="editCosto" name="costo_unidad"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-[#d90000] mt-1"
                       required/>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="cerrarModalEditar()"
                    class="px-4 py-2 rounded-md bg-gray-300 hover:bg-gray-400 text-sm">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 rounded-md bg-[#8ab4c8] hover:bg-[#6a9ab8] text-white text-sm">
                    Guardar Cambios
                </button>
            </div>

        </form>

    </div>

</div>

<script>

function filtrarTabla() {
    const q = document.getElementById('buscador').value.toLowerCase();
    const filas = document.querySelectorAll('#tablaInsumos tbody tr');

    filas.forEach(fila => {
        fila.style.display =
        fila.innerText.toLowerCase().includes(q)
        ? ''
        : 'none';
    });
}

// Modal eliminar
let formEliminar = null;

function abrirModal(form) {
    formEliminar = form;
    document.getElementById("modalEliminar").classList.remove("hidden");
    document.getElementById("modalEliminar").classList.add("flex");
}

function cerrarModalEliminar() {
    document.getElementById("modalEliminar").classList.add("hidden");
    document.getElementById("modalEliminar").classList.remove("flex");
}

function confirmarEliminar() {
    if (formEliminar) {
        formEliminar.submit();
    }
}

// Modal ver
function abrirModalVer(id) {
    fetch(`/admin/insumos/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('verNombre').textContent = data.nombre_insumo;
            document.getElementById('verCosto').textContent = '$' + parseFloat(data.costo_unidad).toFixed(2);
            document.getElementById("modalVer").classList.remove("hidden");
            document.getElementById("modalVer").classList.add("flex");
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del insumo');
        });
}

function cerrarModalVer() {
    document.getElementById("modalVer").classList.add("hidden");
    document.getElementById("modalVer").classList.remove("flex");
}

// Modal editar
function abrirModalEditar(id) {
    fetch(`/admin/insumos/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editNombre').value = data.nombre_insumo;
            document.getElementById('editCosto').value = data.costo_unidad;
            document.getElementById('formEditar').action = `/admin/insumos/${id}`;
            document.getElementById("modalEditar").classList.remove("hidden");
            document.getElementById("modalEditar").classList.add("flex");
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del insumo');
        });
}

function cerrarModalEditar() {
    document.getElementById("modalEditar").classList.add("hidden");
    document.getElementById("modalEditar").classList.remove("flex");
}

</script>

@endsection
