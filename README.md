<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulario de Datos Personales</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-1A4w1kPbbhtD3QXt2Qs8j5Hwoy6yEebnzvjBtquxkGk="
    crossorigin=""
  />
  <script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-9b6Nk5M0UZGExE8G9MvFw+diF3Yg6bG3axrYf0fu3Xk="
    crossorigin=""
  ></script>
</head>
<body class="bg-gray-100 font-sans">

  <div class="max-w-3xl mx-auto mt-10 bg-white rounded-2xl shadow-xl p-8">
    <h1 class="text-3xl font-bold text-center text-indigo-700 mb-6"># formparcial3</h1>
    <p class="text-center text-gray-600 mb-8">Formulario de datos personales con ubicación implementada con <strong>© OpenStreetMap</strong>.</p>

    <form class="space-y-6">
      <div>
        <label class="block text-gray-700 font-semibold mb-2" for="nombre">Nombre completo</label>
        <input type="text" id="nombre" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="Juan Pérez" />
      </div>

      <div>
        <label class="block text-gray-700 font-semibold mb-2" for="email">Correo electrónico</label>
        <input type="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="correo@ejemplo.com" />
      </div>

      <div>
        <label class="block text-gray-700 font-semibold mb-2" for="telefono">Teléfono</label>
        <input type="tel" id="telefono" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="55 1234 5678" />
      </div>

      <div>
        <label class="block text-gray-700 font-semibold mb-2">Ubicación</label>
        <div id="map" class="w-full h-64 rounded-lg shadow-md"></div>
        <input type="hidden" id="lat" name="lat">
        <input type="hidden" id="lng" name="lng">
        <p class="text-sm text-gray-500 mt-2">Haz clic en el mapa para seleccionar tu ubicación.</p>
      </div>

      <div class="text-center">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">Enviar</button>
      </div>
    </form>
  </div>

  <script>
    // Inicializa el mapa
    const map = L.map('map').setView([19.4326, -99.1332], 12); // CDMX por defecto

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker;

    map.on('click', function (e) {
      const { lat, lng } = e.latlng;
      if (marker) {
        marker.setLatLng(e.latlng);
      } else {
        marker = L.marker(e.latlng).addTo(map);
      }
      document.getElementById('lat').value = lat;
      document.getElementById('lng').value = lng;
    });
  </script>

</body>
</html>
