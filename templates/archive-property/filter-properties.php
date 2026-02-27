<?php
/**
 * Filter Properties Template Part
 *
 * This template part displays the filter form for the property archive page.
 *
 * @package Avante
 * @since Avante 1.0.0
 */
?>
<aside class="properties--filter">
    <!-- Filters -->
    <form class="property-filter-form" id="property-filters">
        <!-- Search -->
        <div class="filter">
            <input type="text" id="filter-search" name="search" aria-label="<?php esc_attr_e('Palabras clave', 'avante'); ?>" placeholder="<?php esc_html_e('Ciudad, estado, tipo, etc...', 'avante'); ?>">
        </div>

        <!-- Operation -->
        <?php $operation_types = get_query_var('operation_types', []); ?>
        <?php if (!empty($operation_types)) : ?>
            <fieldset class="menu-flex">
                <legend class="screen-reader-text" style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0;"><?php esc_html_e('Tipo de Operación', 'avante'); ?></legend>
                <div class="menu-flex--operation">
                    <?php if (in_array('sale', $operation_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="op-sale" name="operation[]" value="sale"><label for="op-sale"><?= avante_get_icon('sale'); esc_html_e('Venta', 'avante'); ?></label></div>
                    <?php endif; ?>
                    <?php if (in_array('rental', $operation_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="op-rent" name="operation[]" value="rental"><label for="op-rent"><?= avante_get_icon('rent'); esc_html_e('Renta', 'avante'); ?></label></div>
                    <?php endif; ?>
                </div>
            </fieldset>
        <?php endif; ?>

        <!-- Type -->
        <?php $existing_types = get_query_var('property_types', []); ?>
        <?php if (!empty($existing_types)) : ?>
            <fieldset class="menu-flex">
                <legend class="screen-reader-text" style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0;"><?php esc_html_e('Tipo de Propiedad', 'avante'); ?></legend>
                <div class="menu-flex--type">
                    <?php if (in_array('house', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-casa" name="type[]" value="house"><label for="type-casa"><?= avante_get_icon('home'); esc_html_e('Casa', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('bedroom', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-bedroom" name="type[]" value="bedroom"><label for="type-bedroom"><?= avante_get_icon('bedroom'); esc_html_e('Habitación', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('house_with_land_use', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-house-with-land-use" name="type[]" value="house_with_land_use"><label for="type-house-with-land-use"><?= avante_get_icon('home'); esc_html_e('Casa c/uso de suelo', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('apartment', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-apartment" name="type[]" value="apartment"><label for="type-apartment"><?= avante_get_icon('construction'); esc_html_e('Departamento', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('house_in_condo', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-house-in-condo" name="type[]" value="house_in_condo"><label for="type-house-in-condo"><?= avante_get_icon('home'); esc_html_e('Condominio', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('land', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-land" name="type[]" value="land"><label for="type-land"><?= avante_get_icon('garden'); esc_html_e('Terreno', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('lot', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-lot" name="type[]" value="lot"><label for="type-lot"><?= avante_get_icon('garden'); esc_html_e('Lote', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('commercial', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-commercial" name="type[]" value="commercial"><label for="type-commercial"><?= avante_get_icon('store'); esc_html_e('Local comercial', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('office', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-office" name="type[]" value="office"><label for="type-office"><?= avante_get_icon('home'); esc_html_e('Oficina', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('doctor_office', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-doctor-office" name="type[]" value="doctor_office"><label for="type-doctor-office"><?= avante_get_icon('home'); esc_html_e('Consultorio', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('warehouse', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-warehouse" name="type[]" value="warehouse"><label for="type-warehouse"><?= avante_get_icon('warehouse'); esc_html_e('Bodega', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('industrial_warehouse', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-industrial-warehouse" name="type[]" value="industrial_warehouse"><label for="type-industrial-warehouse"><?= avante_get_icon('warehouse'); esc_html_e('Nave', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('building', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-building" name="type[]" value="building"><label for="type-building"><?= avante_get_icon('construction'); esc_html_e('Edificio', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('penthouse', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-penthouse" name="type[]" value="penthouse"><label for="type-penthouse"><?= avante_get_icon('construction'); esc_html_e('PH', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('loft', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-loft" name="type[]" value="loft"><label for="type-loft"><?= avante_get_icon('construction'); esc_html_e('Loft', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('villa', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-villa" name="type[]" value="villa"><label for="type-villa"><?= avante_get_icon('home'); esc_html_e('Villa', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('ranch', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-ranch" name="type[]" value="ranch"><label for="type-ranch"><?= avante_get_icon('garden'); esc_html_e('Rancho', 'avante'); ?></label></div>
                    <?php endif; ?>

                    <?php if (in_array('other', $existing_types)) : ?>
                        <div class="filter-property"><input type="checkbox" id="type-other" name="type[]" value="other"><label for="type-other"><?= avante_get_icon('home'); esc_html_e('Otro', 'avante'); ?></label></div>
                    <?php endif; ?>
                </div>
            </fieldset>
        <?php endif; ?>

        <ul class="filter-navigation menu">
            <!-- Location -->
            <?php $locations = get_query_var('locations', []); ?>
            <?php if (!empty($locations)) : ?>
                <li class="menu-item-has-children filter state">
                    <button class="btn hollow button-for-submenu" type="button" aria-expanded="false" aria-label="<?php esc_attr_e('Abrir submenú para ubicación', 'avante'); ?>">
                        <?php esc_html_e('Ubicación', 'avante'); ?>
                        <?= avante_get_icon('chevron-down'); ?>
                    </button>
                    <ul class="sub-menu">
                        
                        <?php foreach ($locations as $state => $cities): 
                            $state_id = 'state-' . sanitize_title($state);
                        ?>
                            <li class="menu-item-has-children">
                                <div class="btn hollow wrapper-for-title">
                                    <p class="checkbox-filter-properties"><input type="checkbox" id="<?php echo esc_attr($state_id); ?>" name="state[]" value="<?php echo esc_attr($state); ?>"><label for="<?php echo esc_attr($state_id); ?>"><?php echo esc_html($state); ?></label></p>
                                    <button class="button-for-submenu" type="button" aria-expanded="false" aria-label="<?php printf(esc_attr__('Abrir submenú para %s', 'avante'), $state); ?>">
                                        <?= avante_get_icon('plus-circle'); ?>
                                    </button>
                                </div>
                                <ul class="sub-menu">
                                    
                                    <?php foreach ($cities as $city): 
                                        $city_id = 'city-' . sanitize_title($city);
                                    ?>
                                        <li><p class="checkbox-filter-properties"><input type="checkbox" id="<?php echo esc_attr($city_id); ?>" name="city[]" value="<?php echo esc_attr($city); ?>"><label for="<?php echo esc_attr($city_id); ?>"><?php echo esc_html($city); ?></label></p></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- Rooms -->
            <li class="menu-item-has-children filter rooms">
                <button class="btn hollow button-for-submenu" type="button" aria-expanded="false" aria-label="<?php esc_attr_e('Abrir submenú para habitaciones', 'avante'); ?>">
                    <?php esc_html_e('Habitaciones', 'avante'); ?>
                    <?= avante_get_icon('chevron-down'); ?>
                </button>
                <ul class="sub-menu">
                     
                    <li>
                        <label for="bedrooms"><?php esc_html_e('Recámaras', 'avante'); ?></label>
                        <div class="number-input-wrapper">
                            <button type="button" class="btn-decrease" data-target="bedrooms" aria-label="decrease number of bedrooms"><?= avante_get_icon('minus'); ?></button>
                            <input type="number" name="bedrooms" id="bedrooms" min="0" placeholder="0">
                            <button type="button" class="btn-increase" data-target="bedrooms" aria-label="increase number of bedrooms"><?= avante_get_icon('plus'); ?></button>
                        </div>
                    </li>
                    <li>
                        <label for="bathrooms"><?php esc_html_e('Baños', 'avante'); ?></label>
                        <div class="number-input-wrapper">
                            <button type="button" class="btn-decrease" data-target="bathrooms" aria-label="decrease number of bathrooms"><?= avante_get_icon('minus'); ?></button>
                            <input type="number" name="bathrooms" id="bathrooms" min="0" placeholder="0">
                            <button type="button" class="btn-increase" data-target="bathrooms" aria-label="increase number of bathrooms"><?= avante_get_icon('plus'); ?></button>
                        </div>
                    </li>
                </ul>
            </li>

            <!-- Price -->
            <li class="menu-item-has-children filter price">
                <button class="btn hollow button-for-submenu" type="button" aria-expanded="false" aria-label="<?php esc_attr_e('Abrir submenú para precio', 'avante'); ?>">
                    <?php esc_html_e('Precio', 'avante'); ?>
                    <?= avante_get_icon('chevron-down'); ?>
                </button>

                <ul class="sub-menu">
                     
                    <li> 
                        <div>
                            <label for="price_min"><?php esc_html_e('Mínimo', 'avante'); ?></label>
                            <input type="number" id="price_min" name="price_min" placeholder="<?php esc_html_e('Min $', 'avante'); ?>" min="0" value="<?php echo esc_attr(get_query_var('price_range')['min'] ?? 0); ?>">
                        </div>  
                        <div>
                            <label for="price_max"><?php esc_html_e('Máximo', 'avante'); ?></label>    
                            <input type="number" id="price_max" name="price_max" placeholder="<?php esc_html_e('Max $', 'avante'); ?>" min="0" value="<?php echo esc_attr(get_query_var('price_range')['max'] ?? 0); ?>">
                        </div>

                        <div class="price-range">
                            <label for="price_range"><?php esc_html_e('Rango estimado:', 'avante'); ?></label>
                            <input type="range" id="price_range" min="<?php echo esc_attr(get_query_var('price_range')['min'] ?? 0); ?>" max="<?php echo esc_attr(get_query_var('price_range')['max'] ?? 10000); ?>" step="100" value="<?php echo esc_attr(get_query_var('price_range')['min'] ?? 500); ?>">
                            <span class="range-value"><span id="price-range-value"><?= '$' . format_numeric(get_query_var('price_range')['min'] ?? 500); ?></span></span>
                        </div>
                    </li>
                </ul>
            </li>

            <!-- Size (Construction + Land) -->
            <li class="menu-item-has-children filter size">
                <button class="btn hollow button-for-submenu" type="button" aria-expanded="false" aria-label="<?php esc_attr_e('Abrir submenú para medidas', 'avante'); ?>">
                    <?php esc_html_e('Medidas (m²)', 'avante'); ?>
                    <?= avante_get_icon('chevron-down'); ?>
                </button>

                <ul class="sub-menu">
                     

                    <!-- Construction -->
                    <li>
                        <label><?php esc_html_e('Construcción (m²)', 'avante'); ?></label>
                        <?php $construction_range = get_query_var('construction_range'); ?>
                        <div>
                            <label for="construction_min" class="screen-reader-text" style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0;"><?php esc_html_e('Min Construcción', 'avante'); ?></label>
                            <input type="number" id="construction_min" name="construction_min" placeholder="<?php esc_html_e('Min m²', 'avante'); ?>" min="0" value="<?php echo esc_attr($construction_range['min'] ?? 0); ?>">
                        </div>
                        <div>
                            <label for="construction_max" class="screen-reader-text" style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0;"><?php esc_html_e('Max Construcción', 'avante'); ?></label>
                            <input type="number" id="construction_max" name="construction_max" placeholder="<?php esc_html_e('Max m²', 'avante'); ?>" min="0" value="<?php echo esc_attr($construction_range['max'] ?? 0); ?>">
                        </div>
                        <div class="construction-range">
                            <label for="construction_range"><?php esc_html_e('Rango construcción:', 'avante'); ?></label>
                            <input type="range" id="construction_range" name="construction_range" min="<?php echo esc_attr($construction_range['min'] ?? 0); ?>" max="<?php echo esc_attr($construction_range['max'] ?? 1000); ?>" step="10" value="<?php echo esc_attr($construction_range['min'] ?? 100); ?>">
                            <span class="range-value"><span id="construction-range-value"><?php echo format_numeric($construction_range['min'] ?? 100); ?></span> m²</span>
                        </div>
                    </li>

                    <!-- Land -->
                    <li>
                        <label><?php esc_html_e('Terreno (m²)', 'avante'); ?></label>
                        <?php $land_range = get_query_var('land_range'); ?>
                        <div>
                            <label for="land_min" class="screen-reader-text" style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0;"><?php esc_html_e('Min Terreno', 'avante'); ?></label>
                            <input type="number" id="land_min" name="land_min" placeholder="<?php esc_html_e('Min m²', 'avante'); ?>" min="0" value="<?php echo esc_attr($land_range['min'] ?? 0); ?>">
                        </div>
                        <div>
                            <label for="land_max" class="screen-reader-text" style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0;"><?php esc_html_e('Max Terreno', 'avante'); ?></label>
                            <input type="number" id="land_max" name="land_max" placeholder="<?php esc_html_e('Max m²', 'avante'); ?>" min="0" value="<?php echo esc_attr($land_range['max'] ?? 0); ?>">
                        </div>
                        <div class="land-range">
                            <label for="land_range"><?php esc_html_e('Rango terreno:', 'avante'); ?></label>
                            <input type="range" id="land_range" name="land_range" min="<?php echo esc_attr($land_range['min'] ?? 0); ?>" max="<?php echo esc_attr($land_range['max'] ?? 2000); ?>" step="10" value="<?php echo esc_attr($land_range['min'] ?? 200); ?>">
                            <span class="range-value"><span id="land-range-value"><?php echo format_numeric($land_range['min'] ?? 200); ?></span> m²</span>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Buttons -->
        <!-- <div class="filter-buttons">
            <button class="btn hollow reset-button" type="button" id="reset-filters"><?php esc_html_e('Limpiar', 'avante'); ?></button>
            <button class="btn primary" type="submit"><?php esc_html_e('Filtrar', 'avante'); ?></button>
        </div> -->
    </form>
</aside>