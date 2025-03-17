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
        $css_version
    );

    // Enqueue JavaScript with dynamic version
    wp_enqueue_script(
        'custom-category-slider',
        plugins_url('js/slider.js', __FILE__),
        array(),
        $js_version,
        true
    );
}
add_action('wp_enqueue_scripts', 'custom_category_slider_scripts');

function get_optimized_slider_image_data($image_url, $position = 1, $title = '') {
    // Convert URL to attachment ID
    $image_id = attachment_url_to_postid($image_url);
    
    if (!$image_id) {
        return array(
            'url' => $image_url, // Fallback to original URL if no attachment found
            'width' => '',
            'height' => '',
            'alt' => $title,
            'loading' => 'lazy',
            'fetchpriority' => 'auto'
        );
    }

    // Get image sizes
    $mobile_size = 'large'; // Adjust based on your needs
    $desktop_size = 'full';

    // Get image data for both sizes
    $mobile_data = wp_get_attachment_image_src($image_id, $mobile_size);
    $desktop_data = wp_get_attachment_image_src($image_id, $desktop_size);
    
    // Determine loading strategy based on position
    $is_mobile = wp_is_mobile();
    $eager_load_threshold = $is_mobile ? 1 : 2;
    $should_eager_load = $position <= $eager_load_threshold;
    
    // Get alt text
    $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
    if (empty($alt_text)) {
        $alt_text = $title;
    }
    
    return array(
        'mobile_url' => $mobile_data ? $mobile_data[0] : $image_url,
        'mobile_width' => $mobile_data ? $mobile_data[1] : '',
        'mobile_height' => $mobile_data ? $mobile_data[2] : '',
        'desktop_url' => $desktop_data ? $desktop_data[0] : $image_url,
        'desktop_width' => $desktop_data ? $desktop_data[1] : '',
        'desktop_height' => $desktop_data ? $desktop_data[2] : '',
        'alt' => $alt_text,
        'loading' => $should_eager_load ? 'eager' : 'lazy',
        'fetchpriority' => $position === 1 ? 'high' : 'auto'
    );
}

function custom_category_slider_shortcode($atts) {
    $slides = array(
        array(
            'mobile_image' => 'https://mellmon.in/wp-content/uploads/2025/03/bnnn-1-1536x854-1.webp',
            'desktop_image' => 'https://mellmon.in/wp-content/uploads/2025/03/bnnn-1-1536x854-1.webp',
            'link' => '#',
            'title' => 'Slide 1'
        ),
        array(
            'mobile_image' => 'https://mellmon.in/wp-content/uploads/2025/03/Webppro_out_85b0767aad573290e0aa2728554f3ca6-1536x854-1.webp',
            'desktop_image' => 'https://mellmon.in/wp-content/uploads/2025/03/Webppro_out_85b0767aad573290e0aa2728554f3ca6-1536x854-1.webp',
            'link' => '#',
            'title' => 'Slide 2'
        ),
        array(
            'mobile_image' => 'https://mellmon.in/wp-content/uploads/2025/03/bnnn-1-1536x854-1.webp',
            'desktop_image' => 'https://mellmon.in/wp-content/uploads/2025/03/bnnn-1-1536x854-1.webp',
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
                    <?php foreach ($slides as $index => $slide) : 
                        $mobile_image = get_optimized_slider_image_data($slide['mobile_image'], $index + 1, $slide['title']);
                    ?>
                        <div class="VueCarousel-slide <?php echo $index === 0 ? 'VueCarousel-slide-active VueCarousel-slide-center' : ''; ?>">
                            <div class="prelative shimmerBgColor">
                                <a href="<?php echo esc_url($slide['link']); ?>" class="d-inline-block w-100">
                                    <img src="<?php echo esc_url($mobile_image['mobile_url']); ?>"
                                         alt="<?php echo esc_attr($mobile_image['alt']); ?>"
                                         width="<?php echo esc_attr($mobile_image['mobile_width']); ?>"
                                         height="<?php echo esc_attr($mobile_image['mobile_height']); ?>"
                                         loading="<?php echo esc_attr($mobile_image['loading']); ?>"
                                         fetchpriority="<?php echo esc_attr($mobile_image['fetchpriority']); ?>"
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
                    <?php foreach ($slides as $index => $slide) : 
                        $desktop_image = get_optimized_slider_image_data($slide['desktop_image'], $index + 1, $slide['title']);
                    ?>
                        <div class="VueCarousel-slide <?php echo $index === 0 ? 'VueCarousel-slide-active VueCarousel-slide-center' : ''; ?>">
                            <div class="prelative shimmerBgColor">
                                <div class="shimmerBgColor">
                                    <a href="<?php echo esc_url($slide['link']); ?>" class="clickable d-inline-block w-100">
                                        <img src="<?php echo esc_url($desktop_image['desktop_url']); ?>"
                                             alt="<?php echo esc_attr($desktop_image['alt']); ?>"
                                             width="<?php echo esc_attr($desktop_image['desktop_width']); ?>"
                                             height="<?php echo esc_attr($desktop_image['desktop_height']); ?>"
                                             loading="<?php echo esc_attr($desktop_image['loading']); ?>"
                                             fetchpriority="<?php echo esc_attr($desktop_image['fetchpriority']); ?>"
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
