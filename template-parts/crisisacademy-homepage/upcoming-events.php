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
                            class="card-reveal event-card<?= $i === 0 ? ' event-card--featured' : ''; ?>"
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
        </div>
    </div>
</section>