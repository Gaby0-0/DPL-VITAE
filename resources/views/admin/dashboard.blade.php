@extends('layouts.admin')

@section('title', 'Dashboard administrador')
@section('header', 'DASHBOARD')

@section('content')
    <div class="space-y-8">

        <!--Resumen -->
        <section>
            <h3 class="text-2xl font-semibold text-gray-800 mb-5">Resumen general</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl shadow-md p-6 border-l-8 border-[#d90000]">
                    <p class="text-sm text-gray-500">Cotizaciones recibidas</p>
                    <h4 class="text-4xl font-bold mt-2 text-[#d90000]">24</h4>
                    <p class="text-sm text-gray-500 mt-2">Solicitudes registradas hoy</p>
                </div>

                <div class="bg-white rounded-2xl shadow-md p-6 border-l-8 border-[#d90000]">
                    <p class="text-sm text-gray-500">Servicios activos</p>
                    <h4 class="text-4xl font-bold mt-2 text-[#7aa6c2]">12</h4>
                    <p class="text-sm text-gray-500 mt-2">Servicios en proceso</p>
                </div>

                <div class="bg-white rounded-2xl shadow-md p-6 border-l-8 border-[#d90000]">
                    <p class="text-sm text-gray-500">Ambulancias disponibles</p>
                    <h4 class="text-4xl font-bold mt-2 text-[#d90000]">8</h4>
                    <p class="text-sm text-gray-500 mt-2">Unidades listas para operar</p>
                </div>

                <div class="bg-white rounded-2xl shadow-md p-6 border-l-8 border-[#d90000]">
                    <p class="text-sm text-gray-500">Pacientes registrados</p>
                    <h4 class="text-4xl font-bold mt-2 text-[#7aa6c2]">36</h4>
                    <p class="text-sm text-gray-500 mt-2">Ultimos registros</p>
                </div>
            </div>
        </section>

        <!-- acciones rapidas -->
        <section>
            <h3 class="text-2xl font-semibold text-gray-800 mb-5">Acciones rápidas</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <button class="bg-[#d90000] hover:bg-red-800 text-white rounded-2xl p-5 text-left shadow-md transition">
                    <p class="text-sm opacity-90">Nueva solicitud</p>
                    <h4 class="text-xl font-bold mt-2">Registrar cotización</h4>
                </button>

                <button class="bg-[#7aa6c2] hover:bg-[#6894b0] text-white rounded-2xl p-5 text-left shadow-md transition">
                    <p class="text-sm opacity-90">Control</p>
                    <h4 class="text-xl font-bold mt-2">Ver ambulancias</h4>
                </button>

                <button class="bg-white hover:bg-gray-50 text-gray-800 rounded-2xl p-5 text-left shadow-md transition border">
                    <p class="text-sm text-gray-500">Gestión</p>
                    <h4 class="text-xl font-bold mt-2">Administrar personal</h4>
                </button>

                <button class="bg-white hover:bg-gray-50 text-gray-800 rounded-2xl p-5 text-left shadow-md transition border">
                    <p class="text-sm text-gray-500">Inventario</p>
                    <h4 class="text-xl font-bold mt-2">Consultar insumos</h4>
                </button>
            </div>
        </section>

        <!-- tabla de cotizaciones -->
        <section>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
                <h3 class="text-2xl font-semibold text-gray-800">Cotizaciones recientes</h3>

                <div class="w-full md:w-80">
                    <input
                        type="text"
                        placeholder="Buscar cotización..."
                        class="w-full rounded-2xl bg-[#e9e9e9] px-5 py-3 outline-none focus:ring-2 focus:ring-[#7aa6c2]"
                    >
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-[#d90000] text-white">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold">Paciente</th>
                            <th class="px-6 py-4 text-sm font-semibold">Servicio</th>
                            <th class="px-6 py-4 text-sm font-semibold">Fecha</th>
                            <th class="px-6 py-4 text-sm font-semibold">Origen</th>
                            <th class="px-6 py-4 text-sm font-semibold">Destino</th>
                            <th class="px-6 py-4 text-sm font-semibold">Estado</th>
                            <th class="px-6 py-4 text-sm font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">Ana López García</td>
                            <td class="px-6 py-4">Traslado</td>
                            <td class="px-6 py-4">14/03/2026 10:30</td>
                            <td class="px-6 py-4">Centro</td>
                            <td class="px-6 py-4">Hospital General</td>
                            <td class="px-6 py-4">
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-medium">
                                    Pendiente
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-3 text-lg">
                                    <button class="hover:scale-110 transition">ver</button>
                                    <button class="hover:scale-110 transition">editar</button>
                                    <button class="hover:scale-110 transition">borrar</button>
                                </div>
                            </td>
                        </tr>


                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">Lucía Martínez Soto</td>
                            <td class="px-6 py-4">Evento</td>
                            <td class="px-6 py-4">14/03/2026 12:00</td>
                            <td class="px-6 py-4">Reforma</td>
                            <td class="px-6 py-4">Monte Albán</td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                    Aprobada
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-3 text-lg">
                                    <button class="hover:scale-110 transition">ver</button>
                                    <button class="hover:scale-110 transition">editar</button>
                                    <button class="hover:scale-110 transition">borrar</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- panel inferior -->
        <section class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4">Estado del sistema</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Ambulancias activas</span>
                        <span class="font-bold text-[#d90000]">8/10</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full">
                        <div class="h-3 bg-[#d90000] rounded-full w-[80%]"></div>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-gray-600">Insumos disponibles</span>
                        <span class="font-bold text-[#7aa6c2]">72%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full">
                        <div class="h-3 bg-[#7aa6c2] rounded-full w-[72%]"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4">Actividad reciente</h3>
                <ul class="space-y-4 text-sm text-gray-700">
                    <li class="border-b pb-3">Se registró una nueva cotización para <strong>Ana López García</strong>.</li>
                    <li class="border-b pb-3">La ambulancia <strong>AMB-3</strong> fue marcada como disponible.</li>
                    <li class="border-b pb-3">Se actualizó el inventario de <strong>Oxígeno</strong>.</li>
                    <li>Un administrador revisó la cotización de <strong>Carlos Pérez Ruiz</strong>.</li>
                </ul>
            </div>
        </section>
    </div>
@endsection