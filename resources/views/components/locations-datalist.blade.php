@php
    $id = $id ?? 'artid-locations';
    $locations = [
        'Buenos Aires, Argentina', 'Caba, Argentina', 'Córdoba, Argentina', 'La Plata, Argentina', 'Mendoza, Argentina', 'Rosario, Argentina', 'Mar del Plata, Argentina', 'Salta, Argentina', 'Misiones, Argentina',
        'Ciudad de México, México', 'Guadalajara, México', 'Monterrey, México', 'Oaxaca, México', 'Puebla, México', 'Cancún, México',
        'Madrid, España', 'Barcelona, España', 'Valencia, España', 'Sevilla, España', 'Málaga, España', 'Bilbao, España', 'Granada, España',
        'Milán, Italia', 'Roma, Italia', 'Nápoles, Italia', 'Venecia, Italia', 'Florencia, Italia',
        'París, Francia', 'Lyon, Francia', 'Marsela, Francia', 'Niza, Francia',
        'Londres, Reino Unido', 'Manchester, Reino Unido', 'Edimburgo, Reino Unido', 'Glasgow, Reino Unido',
        'Berlín, Alemania', 'Múnich, Alemania', 'Hamburgo, Alemania', 'Colonia, Alemania',
        'Amberes, Bélgica', 'Bruselas, Bélgica',
        'Ámsterdam, Países Bajos', 'Róterdam, Países Bajos',
        'Zúrich, Suiza', 'Ginebra, Suiza', 'Basilea, Suiza',
        'Nueva York, Estados Unidos', 'Los Ángeles, Estados Unidos', 'Chicago, Estados Unidos', 'Miami, Estados Unidos', 'San Francisco, Estados Unidos',
        'Tokio, Japón', 'Kioto, Japón', 'Osaka, Japón',
        'Seúl, Corea del Sur', 'Busan, Corea del Sur',
        'Sídney, Australia', 'Melbourne, Australia',
        'São Paulo, Brasil', 'Río de Janeiro, Brasil', 'Buenos Aires, Brasil', 'Salvador, Brasil',
        'Ciudad de Guatemala, Guatemala', 'Medellín, Colombia', 'Bogotá, Colombia', 'Cali, Colombia',
        'Lima, Perú', 'Cusco, Perú',
        'Santiago, Chile', 'Valparaíso, Chile',
        'Caracas, Venezuela', 'Maracaibo, Venezuela',
        'Lisboa, Portugal', 'Oporto, Portugal',
        'Praga, República Checa', 'Brno, República Checa',
        'Viena, Austria', 'Salzburgo, Austria',
        'Budapest, Hungría', 'Praga, Hungría',
        'Varsavia, Polonia', 'Cracovia, Polonia',
        'Estambul, Turquía', 'Cumhuriyet, Turquía',
        'El Cairo, Egipto', 'El Aaiún, Egipto',
        'Marrakech, Marruecos', 'Casablanca, Marruecos', 'Rabat, Marruecos',
        'Túnez, Túnez', 'El Jazira, Túnez',
        'Ciudad del Cabo, Sudáfrica', 'Johannesburgo, Sudáfrica', 'Cap Town, Sudáfrica', 'Durban, Sudáfrica',
    ];
@endphp
<datalist id="{{ $id }}">
    @foreach ($locations as $loc)
        <option value="{{ $loc }}">{{ $loc }}</option>
    @endforeach
</datalist>
