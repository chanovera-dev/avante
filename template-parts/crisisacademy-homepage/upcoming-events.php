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
$pretext = get_field('upcoming_events_pretext');
$title = get_field('upcoming_events_title');
?>

<section id="upcoming-events" class="block">
    <div class="content card-reveal">
        <div class="events-container">
            <header class="events-header">
                <?php if ($pretext) : ?>
                    <span class="span-pretext pretext-reveal">
                        <?= avante_get_icon('calendar'); ?>
                        <?= esc_html($pretext); ?>
                    </span>
                <?php endif; ?>
                <?php if ($title) : ?>
                    <h2 class="title-section title-reveal"><?= esc_html($title); ?></h2>
                <?php endif; ?>
            </header>
            <div class="upcoming-events__track-wrapper" id="upcoming-events-track-wrapper">
                <div class="upcoming-events__track" id="upcoming-events-track">
                    <?php if (have_rows('upcoming_events')) : ?>
                        <?php $i = 0; ?>
                        <?php while (have_rows('upcoming_events')) : the_row(); ?>
                            <div class="event-card<?= $i === 0 ? ' event-card--featured' : ''; ?>" style="--card-index: <?= $i; ?>;">
                                <div class="event-card__date">
                                    <span class="event-card__day"><?= esc_html(get_sub_field('event_day')); ?></span>
                                    <span class="event-card__month"><?= esc_html(get_sub_field('event_month')); ?></span>
                                </div>
                                <div class="event-card__body">
                                    <p class="event-card__title"><?= esc_html(get_sub_field('event_title')); ?></p>
                                    <p class="event-card__time"><?= esc_html(get_sub_field('event_time')); ?></p>
                                    <p class="event-card__location"><?= esc_html(get_sub_field('event_location')); ?></p>
                                </div>
                            </div>
                            <?php $i++; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <p>No hay eventos próximos.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>