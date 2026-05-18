<?php
/**
 * Template part for displaying the Upcoming Events section on the homepage.
 *
 * Shows a horizontally-scrollable panel of upcoming workshops and trainings,
 * with the next event highlighted and a live world clock footer.
 *
 * @package Avante
 * @subpackage Template-parts/crisisacademy-homepage
 * @since 1.0.0
 * @version 1.0.0
 */

/**
 * Upcoming events data.
 * Each event: [ day, month_abbr (ES), title, time_range, location, url ]
 */
$events = [
    [
        'day'      => '23',
        'month'    => 'JUN',
        'title'    => 'Taller de Comunicación en Crisis',
        'time'     => '9:00AM — 1:00PM',
        'location' => '@ Campus Online · Zoom',
        'url'      => '/taller-de-especializacion',
    ],
    [
        'day'      => '07',
        'month'    => 'JUL',
        'title'    => 'Masterclass: Vocería Ejecutiva',
        'time'     => '7:00PM — 9:00PM',
        'location' => '@ Campus Online · Zoom',
        'url'      => '/taller-de-especializacion',
    ],
    [
        'day'      => '19',
        'month'    => 'JUL',
        'title'    => 'Auditoría de Manuales de Crisis',
        'time'     => '10:00AM — 2:00PM',
        'location' => '@ Presencial · CDMX',
        'url'      => '/taller-de-especializacion',
    ],
    [
        'day'      => '02',
        'month'    => 'AGO',
        'title'    => 'Certificación en Gestión de Crisis',
        'time'     => '9:00AM — 6:00PM',
        'location' => '@ Campus Online · Zoom',
        'url'      => '/taller-de-especializacion',
    ],
    [
        'day'      => '16',
        'month'    => 'AGO',
        'title'    => 'Simulacro de Crisis Reputacional',
        'time'     => '10:00AM — 1:00PM',
        'location' => '@ Campus Online · Zoom',
        'url'      => '/taller-de-especializacion',
    ],
    [
        'day'      => '04',
        'month'    => 'SEP',
        'title'    => 'Taller: Fake News y Desinformación',
        'time'     => '9:00AM — 12:00PM',
        'location' => '@ Campus Online · Zoom',
        'url'      => '/taller-de-especializacion',
    ],
    [
        'day'      => '20',
        'month'    => 'SEP',
        'title'    => 'Estrategia de Crisis en Redes Sociales',
        'time'     => '11:00AM — 2:00PM',
        'location' => '@ Presencial · Bogotá',
        'url'      => '/taller-de-especializacion',
    ],
    [
        'day'      => '11',
        'month'    => 'OCT',
        'title'    => 'Certificación Avanzada: Dirección de Crisis',
        'time'     => '9:00AM — 6:00PM',
        'location' => '@ Campus Online · Zoom',
        'url'      => '/taller-de-especializacion',
    ],
    [
        'day'      => '29',
        'month'    => 'OCT',
        'title'    => 'Playbook Corporativo: Taller Intensivo',
        'time'     => '8:00AM — 5:00PM',
        'location' => '@ Presencial · CDMX',
        'url'      => '/taller-de-especializacion',
    ],
    [
        'day'      => '15',
        'month'    => 'NOV',
        'title'    => 'Masterclass: IA y Gestión de Crisis',
        'time'     => '7:00PM — 9:00PM',
        'location' => '@ Campus Online · Zoom',
        'url'      => '/taller-de-especializacion',
    ],
];

$world_clocks = [
    [ 'city' => 'CDMX',   'tz' => 'America/Mexico_City' ],
    [ 'city' => 'BOG',    'tz' => 'America/Bogota' ],
    [ 'city' => 'MIA',    'tz' => 'America/New_York' ],
    [ 'city' => 'MAD',    'tz' => 'Europe/Madrid' ],
];
$testimonials = [
    [
        'avatar' => 'https://i.pravatar.cc/150?img=11',
        'username' => '@steven',
        'time' => 'hace 10 min',
        'channel' => '#celebraciones',
        'message' => 'Muchas gracias a @cristian por organizar el simulacro increíble sobre el plan de respuesta rápida. Los beneficios para el equipo y la empresa fueron inmediatos 📈👏 ¡Tendremos la grabación para los que no pudieron estar!',
        'reactions' => ['👏', '1']
    ],
    [
        'avatar' => 'https://i.pravatar.cc/150?img=12',
        'username' => '@andrea',
        'time' => 'hace 45 min',
        'channel' => '#general',
        'message' => 'Increíble la sesión de hoy con @equipo. Me llevo muchos aprendizajes sobre manejo de crisis en medios digitales. 🔥',
        'reactions' => ['💯', '4']
    ],
    [
        'avatar' => 'https://i.pravatar.cc/150?img=13',
        'username' => '@manuel',
        'time' => 'hace 2 horas',
        'channel' => '#marketing',
        'message' => 'El taller nos dio las herramientas exactas que necesitábamos para actualizar nuestros protocolos de RRPP. Totalmente recomendado.',
        'reactions' => ['💡', '7']
    ],
    [
        'avatar' => 'https://i.pravatar.cc/150?img=14',
        'username' => '@daniela',
        'time' => 'hace 5 horas',
        'channel' => '#formacion',
        'message' => 'Gran trabajo hoy en la certificación intensiva! Aprendí más en un día que en meses de teoría auto-estudiada.',
        'reactions' => ['🎉', '12']
    ],
    [
        'avatar' => 'https://i.pravatar.cc/150?img=15',
        'username' => '@roberto',
        'time' => 'ayer',
        'channel' => '#casos-exito',
        'message' => 'Ayer pusimos en práctica los frameworks vistos el mes pasado ante un pequeño incidente y todo funcionó perfecto. ¡Gracias crisis academy!',
        'reactions' => ['🙌', '9']
    ],
];
?>

<section id="upcoming-events" class="block">
    <div class="content">
        <div class="events-container">
            <header class="events-header">
                <span class="span-pretext pretext-reveal">
                    <?= avante_get_icon('calendar'); ?>
                    Agenda 2026
                </span>
                <h2 class="title-section title-reveal">Próximos eventos</h2>
            </header>
            <div class="upcoming-events__track-wrapper" id="upcoming-events-track-wrapper">
                <div class="upcoming-events__track" id="upcoming-events-track">
                    <?php foreach ($events as $i => $event) : ?>
                        <a
                            href="<?= esc_url($event['url']); ?>"
                            class="event-card<?= $i === 0 ? ' event-card--featured' : ''; ?>"
                            style="--card-index: <?= $i; ?>;"
                        >
                            <div class="event-card__date">
                                <span class="event-card__day"><?= esc_html($event['day']); ?></span>
                                <span class="event-card__month"><?= esc_html($event['month']); ?></span>
                            </div>
                            <div class="event-card__body">
                                <p class="event-card__title"><?= esc_html($event['title']); ?></p>
                                <p class="event-card__time"><?= esc_html($event['time']); ?></p>
                                <p class="event-card__location"><?= esc_html($event['location']); ?></p>
                            </div>
                            <?php if ($i === 0) : ?>
                                <span class="event-card__badge">Próximo</span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <footer class="events-footer">
                <div class="events-footer--testimonials">
                    <div class="container">
                        <div class="slideshow--wrapper">
                            <div class="slideshow">
                                <?php foreach ($testimonials as $testimonial) : ?>
                                <div class="testimonials-item">
                                    <div class="slack-msg">
                                        <div class="slack-avatar">
                                            <!-- <img src="<?= esc_url($testimonial['avatar']); ?>" alt="<?= esc_attr($testimonial['username']); ?>"> -->
                                        </div>
                                        <div class="slack-content">
                                            <div class="slack-header">
                                                <span class="slack-username"><?= esc_html($testimonial['username']); ?></span>
                                                <span class="slack-meta">
                                                    <?= esc_html($testimonial['time']); ?> ago in <span class="slack-channel"><?= esc_html($testimonial['channel']); ?></span>
                                                </span>
                                            </div>
                                            <div class="slack-body">
                                                <?= wp_kses_post(preg_replace('/(@\w+)/', '<span class="slack-mention">$1</span>', $testimonial['message'])); ?>
                                            </div>
                                            <?php if (!empty($testimonial['reactions'])) : ?>
                                            <div class="slack-reactions">
                                                <div class="slack-reaction-badge">
                                                    <span class="emoji"><?= esc_html($testimonial['reactions'][0]); ?></span>
                                                    <span class="count"><?= esc_html($testimonial['reactions'][1]); ?></span>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="slideshow-bullets-wrapper">
                            <button class="slideshow-prev btn-pagination small-pagination" aria-label="diapositiva anterior">
                                <?= avante_get_icon('backward'); ?>
                            </button>
                            <div class="slideshow-bullets bullets"></div>
                            <button class="slideshow-next btn-pagination small-pagination" aria-label="siguiente diapositiva">
                                <?= avante_get_icon('forward'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</section>