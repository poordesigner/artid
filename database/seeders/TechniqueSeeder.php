<?php

namespace Database\Seeders;

use App\Models\Technique;
use Illuminate\Database\Seeder;

class TechniqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $techniques = [
            ['Óleo', 'Pigmentos con base de aceite sobre lienzo.'],
            ['Acrílico', 'Pintura plástica soluble en agua de secado rápido.'],
            ['Acuarela', 'Pigmentos transparentes fijados con goma arábiga sobre papel.'],
            ['Pintura digital', 'Ilustración realizada con tabletas gráficas y software especializado.'],
            ['Modelado 3D', 'Creación de objetos tridimensionales virtuales mediante software informático.'],
            ['Arte generativo', 'Obras creadas mediante algoritmos y código de programación.'],
            ['Arte de inteligencia artificial', 'Imágenes generadas mediante redes neuronales y descripciones de texto.'],
            ['Arte Electrónico', 'Arte creado usando código, microprocesadores y otros componentes electrónicos.'],
            ['Graffiti', 'Marcas o dibujos pintados directamente sobre una superficie pública con aerosoles o acrílicos.'],
            ['Arte Urbano', 'Expresión artística ligada al contexto urbano: murales, stencils y obras en espacio público.'],
            ['Stencil', 'Técnica de grabado en la que se recorta una plantilla y se aplica pigmento sobre la superficie.'],
            ['Pixel art', 'Dibujo digital editado a nivel de píxeles individuales.'],
            ['Grabado', 'Transferencia de tinta desde una matriz tallada al papel.'],
            ['Carboncillo', 'Dibujo con palillos de madera carbonizada de tono negro intenso.'],
            ['Grafito', 'Dibujo clásico con lápices de diferentes durezas sobre papel.'],
            ['Tinta china', 'Dibujo con base de agua y carbón usando plumillas o pinceles.'],
            ['Pastel', 'Barras de pigmento seco que aportan una textura aterciopelada.'],
            ['Fresco', 'Pintura ejecutada sobre una superficie de cal húmeda.'],
            ['Temple', 'Técnica pictórica que usa la yema de huevo como aglutinante.'],
            ['Encaústica', 'Uso de cera derretida mezclada con pigmentos de color.'],
            ['Collage', 'Ensamblaje de diversos elementos planos sobre un soporte rígido.'],
            ['Mosaico', 'Unión de pequeñas piezas de piedra, vidrio o cerámica.'],
            ['Tallado', 'Eliminación de material en bloques de piedra o madera.'],
            ['Vaciado', 'Reproducción de esculturas vertiendo metal o yeso en moldes.'],
            ['Ensamblaje', 'Construcción tridimensional utilizando objetos encontrados o materiales diversos.'],
            ['Arte interactivo', 'Obras electrónicas que reaccionan al movimiento o acciones del público.'],
            ['Videoarte', 'Uso de pantallas, proyectores y cintas de video como medio artístico.'],
            ['Instalación lumínica', 'Creación de espacios artísticos mediante luces LED, neón o láseres.'],
            ['Mapping moderno', 'Proyección de video adaptada a las formas de fachadas arquitectónicas.'],
            ['Net.art', 'Obras creadas específicamente para internet y plataformas web conectadas.'],
            ['Circuit bending', 'Modificación creativa de circuitos electrónicos de juguetes para generar sonidos o visuales.'],
            ['Arte robótico', 'Uso de autómatas, servomotores y sistemas mecánicos programados para crear experiencias artísticas.'],
        ];

        foreach ($techniques as [$name, $description]) {
            Technique::updateOrCreate(['name' => $name], ['description' => $description]);
        }
    }
}
