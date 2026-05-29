<?php
/**
 * PIXIS SOCIAL MEDIATOR
 * Este archivo sirve metadatos puros a los bots de WhatsApp/Facebook/etc.
 */

// Forzar que el servidor NUNCA cachee esta respuesta.
// Los scrapers de WhatsApp y Facebook siempre leerán metadata fresca.
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$slug = isset($_GET['producto']) ? $_GET['producto'] : '';
$bannerId = isset($_GET['banner']) ? $_GET['banner'] : '';
$categoriaId = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$theProduct = null;
$theBanner = null;
$theCategory = null;

// Diccionario de SEO Personalizado para Categorías
$CATEGORIAS_SEO = [
    "cargadores" => [
        "titulo" => "Pixis Informática | Cargadores y Cables Especializados",
        "descripcion" => "⚡ CARGADORES / CABLES USB TIPO C Y V8 — Encontrá cargadores rápidos de pared, fuentes para portátiles y conectividad premium con stock inmediato en Santiago del Estero.",
        "imagen" => "https://mistyrose-ibex-626891.hostingersite.com/assets/meta/banner-cargadores.jpg"
    ],
    "almacenamiento" => [
        "titulo" => "Pixis Informática | Discos SSD y Almacenamiento de todo tipo para tu PC",
        "descripcion" => "💾 DISCOS SÓLIDOS Y ALMACENAMIENTO M.2 NVMe — Optimizá la velocidad de tu PC o Notebook. Unidades de alto rendimiento y almacenamiento externo.",
        "imagen" => "https://mistyrose-ibex-626891.hostingersite.com/assets/meta/banner-almacenamiento.jpg"
    ],
    "memorias ram" => [
        "titulo" => "Pixis Informática | Memorias RAM de Alto Rendimiento para tu PC",
        "descripcion" => "🚀 MEMORIAS RAM DDR4 Y DDR5 — Potenciá tu rendimiento multitarea. Módulos de alta velocidad ideales para Gaming, Diseño y Oficina.",
        "imagen" => "https://mistyrose-ibex-626891.hostingersite.com/assets/meta/banner-ram.jpg"
    ],
    "cables" => [
        "titulo" => "Pixis Informática | Adaptadores y Cables todo lo que necesitas para tu PC y Consola",
        "descripcion" => "🔌 ADAPTADORES & CABLES — Conectividad garantizada. Cables HDMI, DisplayPort, adaptadores de video y red para PC y Consolas.",
        "imagen" => ""
    ],
    "camara de seguridad" => [
        "titulo" => "Pixis Informática | Cámaras de Seguridad y Vigilancia",
        "descripcion" => "📷 CÁMARAS DE SEGURIDAD — Protegé lo que más importa. Equipos de vigilancia en alta definición, cámaras IP y kits completos para tu hogar o comercio.",
        "imagen" => ""
    ],
    "fuentes" => [
        "titulo" => "Pixis Informática | Fuentes de Alimentación para cuidar tu inversión",
        "descripcion" => "🔋 FUENTES DE ALIMENTACIÓN — Energía estable y segura para tu hardware. Fuentes certificadas 80 Plus, modulares y de alta gama.",
        "imagen" => ""
    ],
    "gabinetes" => [
        "titulo" => "Pixis Informática | Gabinetes Gamers y de Oficina",
        "descripcion" => "🖥️ GABINETES GAMER — Diseños con flujo de aire optimizado, vidrio templado y coolers RGB. Encontrá el chasis perfecto para tu setup.",
        "imagen" => ""
    ],
    "herramientas" => [
        "titulo" => "Pixis Informática | Herramientas de Precisión",
        "descripcion" => "🔧 HERRAMIENTAS Y MANTENIMIENTO — Destornilladores de precisión, pastas térmicas y herramientas esenciales para service técnico y ensamble de PC.",
        "imagen" => ""
    ],
    "monitores" => [
        "titulo" => "Pixis Informática | Monitores Gamer full hd y de Oficina",
        "descripcion" => "🖥️ MONITORES — Disfrutá de la mejor definición. Pantallas de alta tasa de refresco, paneles IPS, curvos y planos para Gaming o Trabajo.",
        "imagen" => ""
    ],
    "notebook" => [
        "titulo" => "Pixis Informática | PC gamers, Notebooks y mini pcs",
        "descripcion" => "💻 PORTÁTILES Y MINI PCs — Notebooks para estudio, trabajo y gaming de las mejores marcas. Rendimiento móvil garantizado.",
        "imagen" => ""
    ],
    "periféricos" => [
        "titulo" => "Pixis Informática | Periféricos y Accesorios todo para tu PC",
        "descripcion" => "🖱️ TECLADOS, MOUSES Y AURICULARES — Periféricos ergonómicos y mecánicos para elevar tu experiencia de juego y productividad en el día a día.",
        "imagen" => ""
    ],
    "placas madres" => [
        "titulo" => "Pixis Informática | PLACAS MADRES AMD AM4 y AM5 ",
        "descripcion" => "🔲 PLACAS MADRES — La base de tu potencia. Chipsets Intel y AMD de última generación, listos para ensamblar tu nueva computadora.",
        "imagen" => ""
    ],
    "placas de video" => [
        "titulo" => "Pixis Informática | MIRA LAS PLACAS DE VIDEO NVIDIA y AMD DISPONIBLES",
        "descripcion" => "🎮 TARJETAS GRÁFICAS — Rendimiento extremo en tus juegos y diseño. GPUs Nvidia GeForce RTX, AMD Radeon, listas con stock inmediato.",
        "imagen" => ""
    ],
    "procesadores" => [
        "titulo" => "Pixis Informática | Procesadores AMD RYZEN ",
        "descripcion" => "⚙️ PROCESADORES INTEL Y AMD — El cerebro de tu máquina. CPUs de alto rendimiento para Gaming, Edición y Ofimática.",
        "imagen" => ""
    ],
    "red" => [
        "titulo" => "Pixis Informática | Conectividad y Redes",
        "descripcion" => "📡 ROUTERS, PLACAS WI-FI Y SWITCHES — Mantené tu conexión al máximo. Soluciones de conectividad cableada e inalámbrica de alto alcance.",
        "imagen" => ""
    ],
    "refrigeracion" => [
        "titulo" => "Pixis Informática | Soluciones termicas confiables para tu PC",
        "descripcion" => "❄️ COOLERS Y REFRIGERACIÓN LÍQUIDA — Disipadores de calor eficientes. Mantené tus temperaturas bajo control y el máximo rendimiento de tu procesador.",
        "imagen" => ""
    ],
    "sillas y escritorios gamer" => [
        "titulo" => "Pixis Informática | TODO PARA TU SETUP GAMER: SILLAS Y ESCRITORIOS ",
        "descripcion" => "🪑 ERGONOMÍA GAMER — Sillas ultra cómodas y escritorios premium para pasar horas jugando o trabajando con la mejor postura.",
        "imagen" => ""
    ],
    "destacados" => [
        "titulo" => "Pixis Informática | MIRA LOS PRODUCTOS DESTACADOS EN PIXIS",
        "descripcion" => "🔥 LOS MÁS ELEGIDOS — Descubrí los productos más populares y recomendados de nuestra tienda con la mejor relación calidad-precio.",
        "imagen" => ""
    ],
    "nuevos" => [
        "titulo" => "Pixis Informática | MIRA LOS NUEVOS INGRESOS EN PIXIS",
        "descripcion" => "✨ RECIÉN LLEGADOS — Las últimas novedades en tecnología y hardware que acaban de ingresar a nuestro catálogo. ¡No te las pierdas!",
        "imagen" => ""
    ]
];

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

// --- NUEVA LÓGICA DE DETECCIÓN DE PLATAFORMA ---
// WhatsApp y Telegram prefieren la imagen original (ancha)
// Facebook y Discord prefieren la imagen ajustada (1.91:1) para no recortar
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$isWhatsApp = (strpos($userAgent, 'WhatsApp') !== false || strpos($userAgent, 'Telegram') !== false);

if ($bannerId) {
    $redirectUrl = $baseUrl . "/index.html?banner=" . urlencode($bannerId);
} elseif ($categoriaId) {
    $redirectUrl = $baseUrl . "/index.html?categoria=" . urlencode($categoriaId);
} else {
    $redirectUrl = $baseUrl . "/index.html" . ($slug ? "?producto=" . urlencode($slug) : "");
}

/**
 * Convierte un título a slug idéntico al del JS
 * JS: text.normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'')
 */
function makeSlug($text) {
    // Transliterar caracteres especiales (tildes, ñ, ™, etc.)
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    $text = strtolower($text);
    // Reemplazar todo lo que no sea letra o número por guion
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    // Quitar guiones del inicio y fin
    $text = trim($text, '-');
    return $text;
}

/**
 * Convierte la ruta de imagen a una URL absoluta válida para la web
 * Maneja: barras invertidas, espacios, caracteres especiales
 */
function makeImageUrl($imgPath, $baseUrl) {
    // Si ya es una URL absoluta, devolverla tal cual
    if (strpos($imgPath, 'http') === 0) {
        return $imgPath;
    }

    // 1. Reemplazar barras invertidas de Windows por barras normales
    $imgPath = str_replace('\\', '/', $imgPath);

    // 2. Quitar espacios/caracteres al inicio
    $imgPath = ltrim(trim($imgPath), '/');

    // 3. Codificar cada segmento del path por separado (preservar las /)
    $segments = explode('/', $imgPath);
    $encodedSegments = array_map(function($seg) {
        return rawurlencode($seg);
    }, $segments);
    $encodedPath = implode('/', $encodedSegments);

    return $baseUrl . '/' . $encodedPath;
}

// 1. Cargar datos (Productos, Banners o Categorías)
if ($bannerId) {
    // Buscamos metadata del banner en site.json
    $siteData = @file_get_contents(__DIR__ . '/data/site.json');
    if ($siteData) {
        $site = json_decode($siteData, true);
        $siteBanners = $site['banners'] ?? [];
        
        // Fallback quirúrgico para banners conocidos si no están en el JSON
        $fallbackBanners = [
            'kitryzen' => ['t' => 'Kits de Actualización Ryzen'],
            'pccombo' => ['t' => 'PC Gamers y Combos'],
            'monitor' => ['t' => 'Monitores Raptor'],
            'rtx5050' => ['t' => 'Tarjetas Gráficas RTX 5050'],
            'rtx5060' => ['t' => 'Tarjetas Gráficas RTX 5060'],
            'notebooks' => ['t' => 'Notebooks y Mini PCs'],
            'prolongadores' => ['t' => 'Prolongadores Kelyx'],
            'ssd-hiksemi' => ['t' => 'SSDs Hiksemi'],
            'refrigeracion-raptor' => ['t' => 'Refrigeración Raptor'],
            'gabinetes-raptor' => ['t' => 'Gabinetes Raptor'],
            'perifericos-raptor' => ['t' => 'Periféricos Raptor']
        ];

        if (isset($siteBanners[$bannerId])) {
            $theBanner = $siteBanners[$bannerId];
        } elseif (isset($fallbackBanners[$bannerId])) {
            $theBanner = $fallbackBanners[$bannerId];
        }

        if ($theBanner) {
            // Buscar la imagen en los carruseles (donde sí están guardadas)
            $allSlides = array_merge($site['carouselTop'] ?? [], $site['carouselBottom'] ?? []);
            foreach ($allSlides as $slide) {
                if (($slide['bannerId'] ?? '') === $bannerId) {
                    $theBanner['img'] = $slide['imgPc'] ?? $slide['imgMobile'] ?? '';
                    break;
                }
            }
        }
    }
} elseif ($categoriaId) {
    // Buscamos metadata de la categoría en categories.json
    $categoriesData = @file_get_contents(__DIR__ . '/data/categories.json');
    if ($categoriesData) {
        $categories = json_decode($categoriesData, true);
        if (is_array($categories)) {
            foreach ($categories as $cat) {
                if (isset($cat['id']) && strtolower($cat['id']) === strtolower($categoriaId)) {
                    $theCategory = $cat;
                    break;
                }
            }
        }
    }

    if ($theCategory) {
        // Si la categoría tiene un ícono personalizado (la preview del botón), lo usamos directamente.
        // Si no tiene, buscamos el primer producto que pertenezca a esta categoría para usar su imagen.
        if (!empty($theCategory['customIcon'])) {
            $theCategory['img'] = $theCategory['customIcon'];
        } else {
            // Buscamos el primer producto que pertenezca a esta categoría para usar su imagen
            $prodsData = @file_get_contents(__DIR__ . '/data/products.json');
            if ($prodsData) {
                $products = json_decode($prodsData, true);
                if (is_array($products)) {
                    foreach ($products as $p) {
                        $assignedCats = [];
                        if (isset($p['category'])) $assignedCats[] = strtolower(trim($p['category']));
                        if (isset($p['category2'])) $assignedCats[] = strtolower(trim($p['category2']));
                        if (isset($p['category3'])) $assignedCats[] = strtolower(trim($p['category3']));

                        if (in_array(strtolower($theCategory['id']), $assignedCats)) {
                            $rawImg = $p['img'] ?? '';
                            $firstImg = trim(explode(',', $rawImg)[0]);
                            if ($firstImg) {
                                $theCategory['img'] = $firstImg;
                                break;
                            }
                        }
                    }
                }
            }
        }
    }
} elseif ($slug) {
    // Buscamos el producto en products.json
    $prodsData = @file_get_contents(__DIR__ . '/data/products.json');
    if ($prodsData) {
        $products = json_decode($prodsData, true);
        if (is_array($products)) {
            foreach ($products as $p) {
                $currentSlug = makeSlug($p['title'] ?? 'producto');
                if ($currentSlug === $slug) {
                    $theProduct = $p;
                    break;
                }
            }
        }
    }
}

// 2. Definir valores finales de metadata
if ($theBanner) {
    $bannerTitle = $theBanner['t'] ?? 'Promoción';
    $title = $bannerTitle . " - Pixis Informatica | 🚀 Ofertas";
    $description = "Aprovechá las mejores ofertas en " . $bannerTitle . ". Envíos a todo el país y el mejor servicio técnico.";
    $directBannerImg = makeImageUrl($theBanner['img'] ?? 'img/logo_pixis.png', $baseUrl);
    
    // BANNER: Original en WhatsApp, Ajustado en Facebook
    $image = $isWhatsApp ? $directBannerImg : ($baseUrl . "/meta_image.php?url=" . urlencode($directBannerImg));

} elseif ($theCategory || isset($CATEGORIAS_SEO[strtolower($categoriaId)])) {
    $catLower = strtolower($categoriaId);
    $hasCustomSeo = isset($CATEGORIAS_SEO[$catLower]);
    
    $categoryName = $theCategory['name'] ?? $categoriaId;
    
    // Valores por defecto
    $title = "Categoría " . $categoryName . " - Pixis Informatica | ⚡ Especialistas";
    $description = "Encontrá los mejores productos de " . $categoryName . " en Pixis Informática. Accesorios gamer, hardware de alto rendimiento y envíos a todo el país.";
    
    $imgSource = $theCategory['img'] ?? ($theCategory['customIcon'] ?? 'img/logo_pixis.png');
    
    // Reemplazar si el diccionario tiene valores custom
    if ($hasCustomSeo) {
        if (!empty($CATEGORIAS_SEO[$catLower]['titulo'])) {
            $title = $CATEGORIAS_SEO[$catLower]['titulo'];
        }
        if (!empty($CATEGORIAS_SEO[$catLower]['descripcion'])) {
            $description = $CATEGORIAS_SEO[$catLower]['descripcion'];
        }
        // Solo sobrescribimos la imagen con el SEO personalizado si la categoría NO tiene un customIcon
        if (empty($theCategory['customIcon']) && !empty($CATEGORIAS_SEO[$catLower]['imagen'])) {
            $imgSource = $CATEGORIAS_SEO[$catLower]['imagen'];
        }
    }
    
    $directCategoryImg = makeImageUrl($imgSource, $baseUrl);

    // CATEGORÍA: Original en WhatsApp, Ajustado en Facebook
    $image = $isWhatsApp ? $directCategoryImg : ($baseUrl . "/meta_image.php?url=" . urlencode($directCategoryImg));

} elseif ($theProduct) {
    $productTitle = $theProduct['title'];
    $priceLocal = isset($theProduct['priceLocal']) ? $theProduct['priceLocal'] : ($theProduct['price'] ?? 0);
    // Formato con decimales: $143.500,00
    $fmtLocal = "$" . number_format($priceLocal, 2, ',', '.');
    
    // Formato solicitado: Nombre del Producto - Pixis Informatica | Precio especial: $143.500,00
    $title = $productTitle . " - Pixis Informatica | Precio especial: " . $fmtLocal;
    
    $rawDesc = isset($theProduct['desc']) ? trim($theProduct['desc']) : '';
    $rawDesc = preg_replace('/[\x{1F300}-\x{1FFFF}]/u', '', $rawDesc);
    $rawDesc = preg_replace('/\s+/', ' ', $rawDesc);
    $rawDesc = trim($rawDesc);
    $description = mb_strlen($rawDesc) > 150
        ? mb_substr($rawDesc, 0, 150) . '...'
        : ($rawDesc ?: 'Disponible en Pixis Informática');
    
    $rawImg = $theProduct['img'] ?? '';
    $firstImg = trim(explode(',', $rawImg)[0]);
    $directProductImage = makeImageUrl($firstImg, $baseUrl);

    // PRODUCTO: Original en WhatsApp, Ajustado en Facebook para evitar recortes
    $image = $isWhatsApp ? $directProductImage : ($baseUrl . "/meta_image.php?url=" . urlencode($directProductImage));

} else {
    $title = "Pixis Informática | Especialistas en Computación";
    $description = "Tienda de computación online en Santiago del Estero. Venta de accesorios gamer y hardware.";
    $defaultImg = $baseUrl . "/img/logo_pixis.png";
    $image = $isWhatsApp ? $defaultImg : ($baseUrl . "/meta_image.php?url=" . urlencode($defaultImg));
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title); ?></title>
    
    <!-- Metadatos para Robots (Pro-SEO) -->
    <meta name="description" content="<?php echo htmlspecialchars($description); ?>">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="Pixis Informática">
    <meta property="og:locale" content="es_AR">
    <meta property="og:title" content="<?php echo htmlspecialchars($title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($description); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($image); ?>">
    <meta property="og:image:alt" content="<?php echo htmlspecialchars($title); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($redirectUrl); ?>">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($description); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($image); ?>">

    <!-- Redirección para humanos (por si acaso caen aquí) -->
    <script>
        window.location.replace("<?php echo $redirectUrl; ?>");
    </script>
</head>
<body>
    <p>Redirigiendo a Pixis Informática...</p>
</body>
</html>
