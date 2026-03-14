<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotiza tu servicio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f3f3f3] min-h-screen overflow-hidden">

    <div class="relative min-h-screen flex items-center justify-center px-6 py-10">

        <!-- círculo inferior izquierdo -->
        <div class="absolute bottom-0 left-0 w-48 h-48 md:w-64 md:h-64 bg-[#d90000] rounded-tr-full"></div>

        <!-- círculo derecho -->
        <div class="absolute top-0 right-0 w-[42%] h-full bg-[#d90000] rounded-l-full hidden lg:block"></div>

        <div class="relative z-10 w-full max-w-7xl grid grid-cols-1 lg:grid-cols-2 items-center gap-10">

            <!-- formulario -->
            <div class="flex justify-center">
                <div class="w-full max-w-xl">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-8 text-center lg:text-left"
                        style="font-family: arial;">
                        Cotiza tu servicio
                    </h1>

                <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-200">                        <form action="#" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-sm text-gray-700 mb-2">Tipo de servicio</label>
                                <select name="tipo_servicio" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-600 outline-none focus:ring-2 focus:ring-[#d90000]">>
                                    <option selected disabled>Elija una opción</option>
                                    <option value="traslado">Traslado</option>
                                    <option value="evento">Cobertura de evento</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700 mb-2">Nombre(s) del paciente</label>
                                <input type="text" name="nombre" placeholder="Ej. Ana"
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#d90000]">
                            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm text-gray-700 mb-2">Apellido paterno del paciente</label>
                    <input type="text" name="ap_paterno" placeholder="Ej. López"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#d90000]">
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-2">Apellido materno del paciente</label>
                    <input type="text" name="ap_materno" placeholder="Ej. García"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#d90000]">
                </div>

            </div>


                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">Edad</label>
                                    <input type="number" name="edad" placeholder="Ej. 18"
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#d90000]">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">Peso</label>
                                    <input type="text" name="peso" placeholder="Ej. 45 kg"
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#d90000]">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">Altura</label>
                                    <input type="text" name="altura" placeholder="Ej. 1.65 m"
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#d90000]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700 mb-2">Fecha y hora del servicio solicitado</label>
                                <input type="datetime-local" name="fecha_hora"
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#d90000]">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">Origen</label>
                                    <input type="text" name="origen"
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#d90000]">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">Destino</label>
                                    <input type="text" name="destino"
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#d90000]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-700 mb-3">Viaje redondo</label>
                                <div class="flex items-center gap-8 text-sm text-gray-700">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="viaje_redondo" value="si" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        Sí
                                    </label>

                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="viaje_redondo" value="no" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        No
                                    </label>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit"
                                    class="w-full bg-[#d90000] hover:bg-red-800 text-white font-semibold py-3 rounded-xl transition">
                                    Solicitar cotización
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- imagen -->
            <div class="hidden lg:flex justify-center items-center">
                    <img src="{{ asset('images/ambulance.jpeg') }}"
                     alt="Ambulancia"
                     class="w-full max-w-lg drop-shadow-2xl">
            </div>

        </div>
    </div>

</body>
</html>