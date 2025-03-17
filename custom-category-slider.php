<?php
/*
Plugin Name: Custom Category Slider
Description: A responsive image slider with separate mobile and desktop views
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit;
}

function custom_category_slider_scripts() {
    // Get the last modified timestamp of the CSS and JS files
    $css_version = filemtime(plugin_dir_path(__FILE__) . 'css/slider.css');
    $js_version = filemtime(plugin_dir_path(__FILE__) . 'js/slider.js');

    // Enqueue CSS with dynamic version
    wp_enqueue_style(
        'custom-category-slider',
        plugins_url('css/slider.css', __FILE__),
        array(),
        $css_version // Use file modification time as version
    );

    // Enqueue JavaScript with dynamic version
    wp_enqueue_script(
        'custom-category-slider',
        plugins_url('js/slider.js', __FILE__),
        array(),
        $js_version, // Use file modification time as version
        true // Load script in footer
    );
}
add_action('wp_enqueue_scripts', 'custom_category_slider_scripts');

function custom_category_slider_shortcode($atts) {
    $slides = array(
        array(
            'mobile_image' => 'https://mellmon.in/wp-content/uploads/2024/05/bnnn-1-1536x854.webp',
            'desktop_image' => 'https://mellmon.in/wp-content/uploads/2024/05/bnnn-1-1536x854.webp',
            'link' => '#',
            'title' => 'Slide 1'
        ),
        array(
            'mobile_image' => 'https://mellmon.in/wp-content/uploads/2024/05/bnnn-1-1536x854.webp',
            'desktop_image' => 'https://mellmon.in/wp-content/uploads/2024/05/bnnn-1-1536x854.webp',
            'link' => '#',
            'title' => 'Slide 2'
        ),
        array(
            'mobile_image' => 'https://mellmon.in/wp-content/uploads/2024/05/bnnn-1-1536x854.webp',
            'desktop_image' => 'https://mellmon.in/wp-content/uploads/2024/05/bnnn-1-1536x854.webp',
            'link' => '#',
            'title' => 'Slide 3'
        ),
    );

    ob_start();
    ?>
    <!-- Mobile Slider -->
    <div class="headtop prelative d-block d-md-none">
        <div class="VueCarousel mobileBannerView">
            <div class="VueCarousel-wrapper">
                <div class="VueCarousel-inner">
                    <?php foreach ($slides as $index => $slide) : ?>
                        <div class="VueCarousel-slide <?php echo $index === 0 ? 'VueCarousel-slide-active VueCarousel-slide-center' : ''; ?>">
                            <div class="prelative shimmerBgColor">
                                <a href="<?php echo esc_url($slide['link']); ?>" class="d-inline-block w-100">
                                    <img loading="<?php echo $index < 2 ? 'eager' : 'lazy'; ?>"
                                         src="<?php echo esc_url($slide['mobile_image']); ?>"
                                         alt="<?php echo esc_attr($slide['title']); ?>"
                                         class="w-100 img-h-auto customFade-active"
                                    />
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="VueCarousel-pagination">
                <div class="VueCarousel-dot-container">
                    <?php foreach ($slides as $index => $slide) : ?>
                        <button class="VueCarousel-dot <?php echo $index === 0 ? 'VueCarousel-dot--active' : ''; ?>"
                                style="margin-top: 2px; padding: 1px; width: 6px; height: 6px; background-color: <?php echo $index === 0 ? '#000' : '#979A9F'; ?>;">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Slider -->
    <div class="headtop prelative d-none d-md-block">
        <div class="VueCarousel bannerview">
            <div class="VueCarousel-wrapper">
                <div class="VueCarousel-inner">
                    <?php foreach ($slides as $index => $slide) : ?>
                        <div class="VueCarousel-slide <?php echo $index === 0 ? 'VueCarousel-slide-active VueCarousel-slide-center' : ''; ?>">
                            <div class="prelative shimmerBgColor">
                                <div class="shimmerBgColor">
                                    <a href="<?php echo esc_url($slide['link']); ?>" class="clickable d-inline-block w-100">
                                        <img loading="<?php echo $index < 2 ? 'eager' : 'lazy'; ?>"
                                             src="<?php echo esc_url($slide['desktop_image']); ?>"
                                             alt="<?php echo esc_attr($slide['title']); ?>"
                                             class="w-100 img-auto customFade-active"
                                        />
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="VueCarousel-pagination">
                <div class="VueCarousel-dot-container">
                    <?php foreach ($slides as $index => $slide) : ?>
                        <button class="VueCarousel-dot <?php echo $index === 0 ? 'VueCarousel-dot--active' : ''; ?>"
                                style="margin-top: 14px; padding: 7px; width: 10px; height: 10px; background-color: <?php echo $index === 0 ? '#117A7A' : '#EFEFEF'; ?>;">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('category_slider', 'custom_category_slider_shortcode');