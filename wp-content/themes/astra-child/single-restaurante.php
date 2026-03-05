<?php
/**
 * Single Restaurante (CPT: restaurante)
 * Archivo: single-restaurante.php
 */
if ( ! defined('ABSPATH') ) exit;

get_header();

// Astra: opcional
remove_action( 'astra_entry_header', 'astra_post_meta', 10 );

$titulo = get_the_title();

/* ===== 1) Imagen destacada ===== */
$img_url = '';
if ( has_post_thumbnail() ) {
  $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
}

/* ===== 2) Contenido / Lead ===== */
$raw_content = get_the_content();
$content_html = apply_filters('the_content', $raw_content);

if ( has_excerpt() ) {
  $lead = get_the_excerpt();
} else {
  $lead = wp_trim_words( wp_strip_all_tags( $raw_content ), 26 );
}

/* ===== 3) Helpers: extraer secciones por encabezado ===== */
function rs_extract_section_text($content, $heading){
  $heading = preg_quote($heading, '/');
  $pattern = '/<h[2-4][^>]*>\s*' . $heading . '\:?\s*<\/h[2-4]>\s*(.*?)\s*(?=<h[2-4][^>]*>|$)/is';
  if ( preg_match($pattern, $content, $m) ) {
    $text = trim( wp_strip_all_tags( $m[1] ) );
    return $text;
  }
  return '';
}

function rs_extract_section_list_items($content, $heading){
  $heading = preg_quote($heading, '/');
  $pattern = '/<h[2-4][^>]*>\s*' . $heading . '\:?\s*<\/h[2-4]>\s*(.*?)\s*(?=<h[2-4][^>]*>|$)/is';
  if ( preg_match($pattern, $content, $m) ) {
    $section_html = $m[1];
    if ( preg_match('/<ul[^>]*>(.*?)<\/ul>/is', $section_html, $ul) ) {
      preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $ul[0], $lis);
      $items = [];
      foreach ($lis[1] as $li){
        $t = trim( wp_strip_all_tags($li) );
        if ($t !== '') $items[] = $t;
      }
      return $items;
    }
  }
  return [];
}

/* ===== 4) Datos sacados del contenido ===== */
$cpt_tipo      = rs_extract_section_text($content_html, 'Tipo de cocina');
$cpt_precio    = rs_extract_section_text($content_html, 'Precio medio');
$cpt_ubicacion = rs_extract_section_text($content_html, 'Ubicación');
$cpt_horario   = rs_extract_section_text($content_html, 'Horario');

$incluye_items   = rs_extract_section_list_items($content_html, 'Incluye');
$noincluye_items = rs_extract_section_list_items($content_html, 'No incluye');
?>

<main class="ps-wrap ps-single">

  <!-- HERO -->
  <header class="ps-hero">
    <div class="ps-hero__media" style="<?php echo $img_url ? 'background-image:url(' . esc_url($img_url) . ');' : ''; ?>">
      <?php if ( ! $img_url ): ?>
        <div class="ps-hero__placeholder"></div>
      <?php endif; ?>
      <span class="ps-hero__badge">Restaurante · Sevilla</span>
    </div>

    <div class="ps-hero__content">
      <h1 class="ps-title"><?php echo esc_html($titulo); ?></h1>

      <?php if ( ! empty($lead) ): ?>
        <p class="ps-lead"><?php echo esc_html($lead); ?></p>
      <?php endif; ?>

      <div class="ps-hero__actions">
        <a class="ps-btn ps-btn--primary" href="#reserva">Reservar mesa</a>
        <a class="ps-btn ps-btn--ghost" href="/restaurantes">Ver todos los restaurantes</a>
      </div>

      <ul class="ps-pills">
        <?php if ( ! empty($cpt_tipo) ): ?><li class="ps-pill">🍽 <?php echo esc_html($cpt_tipo); ?></li><?php endif; ?>
        <?php if ( ! empty($cpt_precio) ): ?><li class="ps-pill">💶 <?php echo esc_html($cpt_precio); ?></li><?php endif; ?>
      </ul>
    </div>
  </header>

  <section class="ps-grid">

    <!-- CONTENIDO PRINCIPAL -->
    <article class="ps-card ps-card--main">
      <div class="ps-card__body">
        <h2 class="ps-h2">Descripción</h2>

        <?php if ( trim(wp_strip_all_tags($raw_content)) !== '' ): ?>
          <div class="ps-prose"><?php echo $content_html; ?></div>
        <?php else: ?>
          <p class="ps-muted">Escribe la descripción del restaurante en el editor.</p>
        <?php endif; ?>
      </div>
    </article>

    <!-- ASIDE -->
    <aside class="ps-card ps-card--aside">
      <div class="ps-card__body">
        <h2 class="ps-h2">Información</h2>

        <dl class="ps-meta">
          <div class="ps-meta__row"><dt>Tipo de cocina</dt><dd><?php echo $cpt_tipo ? esc_html($cpt_tipo) : '—'; ?></dd></div>
          <div class="ps-meta__row"><dt>Precio medio</dt><dd><?php echo $cpt_precio ? esc_html($cpt_precio) : '—'; ?></dd></div>
          <div class="ps-meta__row"><dt>Ubicación</dt><dd><?php echo $cpt_ubicacion ? esc_html($cpt_ubicacion) : '—'; ?></dd></div>
          <div class="ps-meta__row"><dt>Horario</dt><dd><?php echo $cpt_horario ? esc_html($cpt_horario) : '—'; ?></dd></div>
        </dl>

        <div class="ps-divider"></div>

        <h3 class="ps-h3">Incluye</h3>
        <?php if ( $incluye_items ): ?>
          <ul class="ps-list"><?php foreach($incluye_items as $it){ echo '<li>' . esc_html($it) . '</li>'; } ?></ul>
        <?php else: ?>
          <p class="ps-muted">Añade “Incluye” + lista en el contenido.</p>
        <?php endif; ?>

        <div class="ps-divider"></div>

        <h3 class="ps-h3">No incluye</h3>
        <?php if ( $noincluye_items ): ?>
          <ul class="ps-list ps-list--danger"><?php foreach($noincluye_items as $it){ echo '<li>' . esc_html($it) . '</li>'; } ?></ul>
        <?php else: ?>
          <p class="ps-muted">Añade “No incluye” + lista en el contenido.</p>
        <?php endif; ?>

        <div class="ps-cta" id="reserva">
          <p class="ps-note">Reserva directa con el restaurante.</p>
          <a class="ps-btn ps-btn--primary ps-btn--block" href="">Reservar mesa</a>
        </div>

      </div>
    </aside>

  </section>

</main>

<?php get_footer();