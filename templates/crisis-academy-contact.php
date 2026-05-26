<?php
/**
 * Template name: Contacto de Academia de crisis
 */
get_header(); ?>

<main id="main" class="site-main" role="main">
    <header class="block">
        <?php
        if (has_post_thumbnail()) {
            echo get_the_post_thumbnail(null, 'full', ['alt' => get_the_title(), 'loading' => 'lazy', 'class' => 'background-parallax', 'data-speed' => '0.5']);
        }
        ?>
        <div class="content">
            <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
        </div>
    </header>
    <?php wp_breadcrumbs(); ?>
    <section class="block">
        <div class="content">
            <aside class="sidebar-contact">
                <div class="booking-services">
                    <h2 class="services-title">Programar un servicio</h2>
                    <div class="booking-service-item">
                        <h3 class="booking-service-title">Programar llamada de evaluación gratuita de 15 minutos</h3>
                        <span class="booking-service-description">¿Te interesa resolver una crisis mediática? Platica con nosotros, reserva una llamada y te daremos una evaluación gratuita.</span>
                        <span class="booking-service-time-limit">15 minutos</span>
                    </div>
                </div>
                <div class="crisis-experts">
                    <h2 class="experts-title">Escoge a un especialista</h2>
                    <div class="crisis-expert-item">
                        <img src="<?php echo get_template_directory_uri() ?>/assets/img/carolinaeslava.png" alt="">
                        <h3 class="expert-name">Carolina Eslava</h3>
                        <span class="expert-position">Speaker Trainer</span>
                    </div>
                    
                </div>
            </aside>        
            
        </div>
    </section>
</main><!-- .site-main -->

<!-- widget de AtMeetly -->
            <div id="atmeetly"></div>
            <script 
                src="https://atmeetly.com/widget.js" 
                data-user="thecrisisacademy" 
                data-base-url="https://atmeetly.com" 
                data-container-id="atmeetly">
            </script>

<?php get_footer(); ?>