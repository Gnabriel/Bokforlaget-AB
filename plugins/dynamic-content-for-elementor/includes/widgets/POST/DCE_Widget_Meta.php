<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use Elementor\Utils;
use DynamicContentForElementor\DCE_Helper;
use DynamicContentForElementor\DCE_Tokens;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dynamic Content Title
 *
 * Widget Meta for Dynamic Content for Elementor
 *
 */
class DCE_Widget_Meta extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dce-meta';
    }

    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Meta Fields', DCE_TEXTDOMAIN);
    }

    public function get_description() {
        return __('Add a customized field', DCE_TEXTDOMAIN);
    }

    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/acf-fields/';
    }

    public function get_icon() {
        return 'icon-dyn-acffields';
    }

    protected function _register_controls() {

        // ********************************************************************************* Section BASE
        $this->start_controls_section(
                'section_content', [
            'label' => __('Field', DCE_TEXTDOMAIN)
                ]
        );

        $metas = array();
        $templates = array();
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $metas = DCE_Helper::get_post_metas(true);
            $templates = DCE_Helper::get_all_template();
        }
        //var_dump($metas); die();
        

        $i = 0;
        $metas_select = array();
        if (!empty($metas)) {
            foreach ($metas as $mkey => $ameta) {
                ksort($ameta);
                $metas_select[$mkey]['label'] = $mkey;
                $metas_select[$mkey]['options'] = $ameta;
                $i++;
            }
        }

        $this->add_control(
                'dce_meta_key', [
            'label' => __('META Field', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::SELECT,
            //'options' => $this->get_acf_field(),
            'groups' => $metas_select,
            //'description' => 'Select the Field',
                ]
        );
        $this->add_control(
                'dce_meta_array', [
            'label' => __('Multiple postmeta', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::SWITCHER,
            'description' => __("Post has many postmeta with same meta_name.", DCE_TEXTDOMAIN),
                ]
        );
        $this->add_control(
                'dce_meta_array_filter', [
            'label' => __('Filter occurrences', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'all' => [
                    'title' => __('All', DCE_TEXTDOMAIN),
                    'icon' => 'fa fa-bars',
                ],
                'first' => [
                    'title' => __('First', DCE_TEXTDOMAIN),
                    'icon' => 'fa fa-hand-o-up',
                ],
                'last' => [
                    'title' => __('Last', DCE_TEXTDOMAIN),
                    'icon' => 'fa fa-hand-o-down',
                ],
            ],
            'default' => 'all',
            'toggle' => false,
            'condition' => [
                'dce_meta_array!' => '',
            ],
                ]
        );
        $this->end_controls_section();

        // RENDER
        $this->start_controls_section(
                'dce_meta_render', [
            'label' => __('Render mode', DCE_TEXTDOMAIN),
            'condition' => [
                'dce_meta_key!' => '',
            ],
                ]
        );
        
        $this->add_control(
                'dce_meta_type', [
            'label' => __('Render as', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'dynamic' => __('AUTO', DCE_TEXTDOMAIN),
                'custom' => __('CUSTOM', DCE_TEXTDOMAIN),
                'id' => __('ID', DCE_TEXTDOMAIN),
                'text' => __('Text', DCE_TEXTDOMAIN),
                //'number' => __('Number', DCE_TEXTDOMAIN),
                //'url' => __('Url', DCE_TEXTDOMAIN),
                'button' => __('Button', DCE_TEXTDOMAIN),
                'date' => __('Date', DCE_TEXTDOMAIN),
                'image' => __('Image', DCE_TEXTDOMAIN),
                'map' => __('Map', DCE_TEXTDOMAIN),
                //'video' => __('Video oembed', DCE_TEXTDOMAIN),
                'multiple' => __('Multiple (like Relationship, Select, Checkboxes, etc)', DCE_TEXTDOMAIN),
                'repeater' => __('Repeater', DCE_TEXTDOMAIN),
            //'audio' => __( 'Audio', DCE_TEXTDOMAIN ),
            //'file' => __( 'File', DCE_TEXTDOMAIN ),
            //'map' => __( 'Map', DCE_TEXTDOMAIN ),
            //'gallery' => __( 'Gallery', DCE_TEXTDOMAIN ),
            //'terms-taxonomy' => __( 'Terms Taxonomy', DCE_TEXTDOMAIN )
            ],
            'default' => 'dynamic',
                ]
        );
        $this->add_control(
                'dce_meta_raw', [
            'label' => __('Use Raw data', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::SWITCHER,
            'description' => __("Use value stored in postmeta, without any plugin modification.", DCE_TEXTDOMAIN),
                ]
        );
        $this->add_control(
                'dce_meta_custom', [
            'label' => __('Custom HTML', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::WYSIWYG,
            'default' => "[META_VALUE]",
            'placeholder' => "[META_VALUE]",
            'description' => __("Write here some content, you can use HTML and TOKENS.", DCE_TEXTDOMAIN),
            'condition' => [
                'dce_meta_type' => 'custom',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_tag', [
            'label' => __('HTML Tag', 'elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'div' => 'div',
                'span' => 'span',
                'ul' => 'ul',
                'ol' => 'ol',
                'p' => 'p',
                '' => __('NONE', DCE_TEXTDOMAIN),
            ],
            'default' => 'div',
                ]
        );
        $this->end_controls_section();

        // REPEATER
        $this->start_controls_section(
                'dce_meta_section_repeater', [
            'label' => __('Repeater', DCE_TEXTDOMAIN),
            'condition' => [
                'dce_meta_type' => 'repeater',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_repeater', [
            'label' => __('Custom HTML', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::WYSIWYG,
            'default' => "[ROW]",
            'placeholder' => "[ROW]",
            'description' => __("Write here some content, you can use HTML and TOKENS like [ROW:field_1], [ROW:field_2] where field name is the sub field configured in repeater.", DCE_TEXTDOMAIN),
                ]
        );
        $this->end_controls_section();


        // MULTIPLE
        $this->start_controls_section(
                'dce_meta_section_multiple', [
            'label' => __('Multiple values', DCE_TEXTDOMAIN),
            'condition' => [
                'dce_meta_type' => 'multiple',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_multiple_tag', [
            'label' => __('HTML Tag', 'elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'div' => 'div',
                'span' => 'span',
                'li' => 'li',
                'p' => 'p',
                'custom' => __('CUSTOM', DCE_TEXTDOMAIN),
                '' => __('NONE', DCE_TEXTDOMAIN),
            ],
                ]
        );
        $this->add_control(
                'dce_meta_multiple_custom', [
            'label' => __('Custom HTML', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::WYSIWYG,
            'default' => "[SINGLE]",
            'placeholder' => "[SINGLE]",
            'description' => __("Write here some content, you can use HTML and TOKENS.", DCE_TEXTDOMAIN),
            'condition' => [
                'dce_meta_multiple_tag' => 'custom',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_multiple_separator',
                [
                    'label' => __('Separator', DCE_TEXTDOMAIN),
                    'type' => Controls_Manager::TEXT,
                    'condition' => [
                        'dce_meta_multiple_tag!' => 'custom',
                    ],
                ]
        );
        $this->add_control(
                'dce_meta_multiple_separator_last', [
            'label' => __('Not on last item', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'dce_meta_multiple_tag!' => 'custom',
                'dce_meta_multiple_separator!' => '',
            ],
                ]
        );
        $this->end_controls_section();


        // MAP
        $this->start_controls_section(
                'dce_meta_section_map', [
            'label' => __('Map', DCE_TEXTDOMAIN),
            'condition' => [
                'dce_meta_type' => 'map',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_map_zoom',
                [
                    'label' => __('Zoom', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'max' => 20,
                        ],
                    ],
                    'separator' => 'before',
                ]
        );
        $this->add_responsive_control(
                'dce_meta_map_height',
                [
                    'label' => __('Height', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 40,
                            'max' => 1440,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} iframe' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_control(
                'dce_meta_map_prevent_scroll',
                [
                    'label' => __('Prevent Scroll', 'elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'selectors' => [
                        '{{WRAPPER}} iframe' => 'pointer-events: none;',
                    ],
                ]
        );
        $this->end_controls_section();


        // DATE
        $this->start_controls_section(
                'dce_meta_section_date', [
            'label' => __('Date', DCE_TEXTDOMAIN),
            'condition' => [
                'dce_meta_type' => 'date',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_date_format_source',
                [
                    'label' => __('Source Format', DCE_TEXTDOMAIN),
                    'type' => Controls_Manager::TEXT,
                    'description' => '<a target="_blank" href="https://www.php.net/manual/en/function.date.php">' . __('Use standard PHP format character') . '</a>' . __(', you can also use "timestamp"'),
                    'placeholder' => __('YmdHis, d/m/Y, m-d-y', DCE_TEXTDOMAIN),
                ]
        );
        $this->add_control(
                'dce_meta_date_format_display',
                [
                    'label' => __('Display Format', DCE_TEXTDOMAIN),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => __('Y/m/d H:i:s, d/m/Y, m-d-y', DCE_TEXTDOMAIN),
                ]
        );
        $this->end_controls_section();


        // ID
        $this->start_controls_section(
                'dce_meta_section_id', [
            'label' => __('ID', DCE_TEXTDOMAIN),
            'condition' => [
                'dce_meta_type' => 'id',
            ],
                ]
        );
        /*
        $this->add_control(
                'dce_meta_id_type', [
            'label' => __('Object Type', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'post' => [
                    'title' => __('Post', DCE_TEXTDOMAIN),
                    'icon' => 'fa fa-file',
                ],
                'term' => [
                    'title' => __('Taxonomy Term', DCE_TEXTDOMAIN),
                    'icon' => 'fa fa-tags',
                ],
                'user' => [
                    'title' => __('User', DCE_TEXTDOMAIN),
                    'icon' => 'fa fa-user',
                ],
            ],
            'default' => 'post',
            'toggle' => false,
                ]
        );
        */
        $this->add_control(
                'dce_meta_id_type',
                [
                    'label' => __('Object Type', 'elementor'),
                    'type' => Controls_Manager::HIDDEN,
                    'default' => 'post',
                ]
        );
        $this->add_control(
                'dce_meta_id_render_type', [
            'label' => __('Content type', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'simple' => [
                    'title' => __('Simple', DCE_TEXTDOMAIN),
                    'icon' => 'fa fa-link',
                ],
                'text' => [
                    'title' => __('Text', DCE_TEXTDOMAIN),
                    'icon' => 'fa fa-align-left',
                ],
                'template' => [
                    'title' => __('Template', DCE_TEXTDOMAIN),
                    'icon' => 'fa fa-th-large',
                ]
            ],
            'toggle' => false,
            'default' => 'simple',
                ]
        );
        $this->add_control(
                'dce_meta_id_render_type_template', [
            'label' => __('Render Template', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::SELECT2,
            'options' => $templates,
            'condition' => [
                'dce_meta_id_render_type' => 'template',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_id_render_type_text', [
            'label' => __('Object html', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::WYSIWYG,
            'default' => '[post:thumb]<h4>[post:title]</h4><p>[post:excerpt]</p><a class="btn btn-primary" href="[post:permalink]">READ MORE</a>',
            'condition' => [
                'dce_meta_id_render_type' => 'text',
            ],
                ]
        );
        $this->end_controls_section();


        // IMAGE
        $this->start_controls_section(
                'dce_meta_section_image', [
            'label' => __('Image', DCE_TEXTDOMAIN),
            'condition' => [
                'dce_meta_type' => 'image',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'dce_meta_image_size', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
                //'default' => 'large',
                ]
        );
        $this->add_control(
                'dce_meta_image_caption_source',
                [
                    'label' => __('Caption', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'none' => __('None', 'elementor'),
                        'attachment' => __('Attachment Caption', 'elementor'),
                        'custom' => __('Custom Caption', 'elementor'),
                    ],
                    'default' => 'none',
                ]
        );
        $this->add_control(
                'dce_meta_image_caption',
                [
                    'label' => __('Custom Caption', 'elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => __('Enter your image caption', 'elementor'),
                    'condition' => [
                        'dce_meta_image_caption_source' => 'custom',
                    ],
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
        );
        $this->add_control(
                'dce_meta_image_link_to',
                [
                    'label' => __('Link', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'none',
                    'options' => [
                        '' => __('None', 'elementor'),
                        'file' => __('Media File', 'elementor'),
                        'post' => __('Post', 'elementor'),
                        'custom' => __('Custom URL', 'elementor'),
                    ],
                ]
        );
        $this->add_control(
                'dce_meta_image_link',
                [
                    'label' => __('Link', 'elementor'),
                    'type' => Controls_Manager::URL,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'placeholder' => __('https://your-link.com', 'elementor'),
                    'condition' => [
                        'dce_meta_image_link_to' => 'custom',
                    ],
                    'show_label' => false,
                ]
        );
        $this->end_controls_section();


        // BUTTON
        $this->start_controls_section(
                'dce_meta_button_section_button',
                [
                    'label' => __('Button', 'elementor'),
                    'condition' => [
                        'dce_meta_type' => 'button',
                    ],
                ]
        );
        $this->add_control(
                'dce_meta_button_type',
                [
                    'label' => __('Type', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        '' => __('Default', 'elementor'),
                        'info' => __('Info', 'elementor'),
                        'success' => __('Success', 'elementor'),
                        'warning' => __('Warning', 'elementor'),
                        'danger' => __('Danger', 'elementor'),
                    ],
                    'prefix_class' => 'elementor-button-',
                ]
        );
        $this->add_control(
                'dce_meta_button_text',
                [
                    'label' => __('Text', 'elementor'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'default' => __('Click here', 'elementor'),
                    'placeholder' => __('[META_VALUE], [META_VALUE:title], [META_VALUE:get_the_title]', 'elementor'),
                    'description' => __('Can use a mix of text, Tokens e META_VALUE data', DCE_TEXTDOMAIN),
                ]
        );
        $this->add_control(
                'dce_meta_button_link',
                [
                    'label' => __('Link', 'elementor'),
                    'type' => Controls_Manager::URL,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'placeholder' => __('[META_VALUE], [META_VALUE:url], [META_VALUE|get_permalink]', 'elementor'),
                    'default' => [
                        'url' => '#',
                    ],
                    'description' => __('Can use a mix of text, Tokens e META_VALUE data', DCE_TEXTDOMAIN),
                ]
        );
        $this->add_control(
                'dce_meta_button_size',
                [
                    'label' => __('Size', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'sm',
                    'options' => self::get_button_sizes(),
                    'style_transfer' => true,
                ]
        );
        $this->add_control(
                'dce_meta_button_icon',
                [
                    'label' => __('Icon', 'elementor'),
                    'type' => Controls_Manager::ICON,
                    'label_block' => true,
                    'default' => '',
                ]
        );
        $this->add_control(
                'dce_meta_button_icon_align',
                [
                    'label' => __('Icon Position', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left' => __('Before', 'elementor'),
                        'right' => __('After', 'elementor'),
                    ],
                    'condition' => [
                        'dce_meta_button_icon!' => '',
                    ],
                ]
        );
        $this->add_control(
                'dce_meta_button_icon_indent',
                [
                    'label' => __('Icon Spacing', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'condition' => [
                        'dce_meta_button_icon!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_control(
                'dce_meta_button_view',
                [
                    'label' => __('View', 'elementor'),
                    'type' => Controls_Manager::HIDDEN,
                    'default' => 'traditional',
                ]
        );
        $this->add_control(
                'dce_meta_button_css_id',
                [
                    'label' => __('Button ID', 'elementor'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'default' => '',
                    'title' => __('Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor'),
                    'label_block' => false,
                    'description' => __('Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elementor'),
                    'separator' => 'before',
                ]
        );
        $this->end_controls_section();



        //* FALLBACK for NO RESULTS *//
        $this->start_controls_section(
                'dce_meta_section_fallback', [
            'label' => __('Empty field behavior', DCE_TEXTDOMAIN),
                ]
        );
        $this->add_control(
                'dce_meta_fallback', [
            'label' => __('Enable a Fallback Content', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::SWITCHER,
            'description' => __("If you want to show something when field is empty (empty, null, void, false, 0).", DCE_TEXTDOMAIN),
                ]
        );
        $this->add_control(
                'dce_meta_fallback_zero', [
            'label' => __('Consider 0 as empty', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'dce_meta_fallback!' => '',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_fallback_type', [
            'label' => __('Content type', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'text' => [
                    'title' => __('Text', DCE_TEXTDOMAIN),
                    'icon' => 'fa fa-align-left',
                ],
                'template' => [
                    'title' => __('Template', DCE_TEXTDOMAIN),
                    'icon' => 'fa fa-th-large',
                ]
            ],
            'toggle' => false,
            'default' => 'text',
            'condition' => [
                'dce_meta_fallback!' => '',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_fallback_template', [
            'label' => __('Render Template', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::SELECT2,
            'options' => $templates,
            'description' => 'Use a Elementor Template as content, useful for complex structure.',
            'condition' => [
                'dce_meta_fallback!' => '',
                'dce_meta_fallback_type' => 'template',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_fallback_text', [
            'label' => __('Text Fallback', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::WYSIWYG,
            'default' => "This field is empty.",
            'description' => __("Write here some content, you can use HTML and TOKENS.", DCE_TEXTDOMAIN),
            'condition' => [
                'dce_meta_fallback!' => '',
                'dce_meta_fallback_type' => 'text',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_fallback_autop', [
            'label' => __('Remove AutoP', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'dce_meta_fallback!' => '',
                'dce_meta_fallback_type' => 'text',
            ],
                ]
        );
        $this->end_controls_section();
        
        
        $this->start_controls_section(
                'dce_meta_array_section', [
            'label' => __('Multiple postmeta', DCE_TEXTDOMAIN),
            'condition' => [
                'dce_meta_array!' => '',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_array_fallback', [
            'label' => __('Enable a Fallback Content', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::SWITCHER,
            'description' => __("If you want to show something when this post meta is not found.", DCE_TEXTDOMAIN),
                ]
        );
        $this->add_control(
                'dce_meta_array_fallback_type', [
            'label' => __('Content type', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'text' => [
                    'title' => __('Text', DCE_TEXTDOMAIN),
                    'icon' => 'fa fa-align-left',
                ],
                'template' => [
                    'title' => __('Template', DCE_TEXTDOMAIN),
                    'icon' => 'fa fa-th-large',
                ]
            ],
            'toggle' => false,
            'default' => 'text',
            'condition' => [
                'dce_meta_array_fallback!' => '',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_array_fallback_template', [
            'label' => __('Render Template', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::SELECT2,
            'options' => $templates,
            'description' => 'Use a Elementor Template as content, useful for complex structure.',
            'condition' => [
                'dce_meta_array_fallback!' => '',
                'dce_meta_array_fallback_type' => 'template',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_array_fallback_text', [
            'label' => __('Text Fallback', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::WYSIWYG,
            'default' => "This field is empty.",
            'description' => __("Write here some content, you can use HTML and TOKENS.", DCE_TEXTDOMAIN),
            'condition' => [
                'dce_meta_array_fallback!' => '',
                'dce_meta_array_fallback_type' => 'text',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_array_fallback_autop', [
            'label' => __('Remove AutoP', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'dce_meta_array_fallback!' => '',
                'dce_meta_array_fallback_type' => 'text',
            ],
                ]
        );
        $this->end_controls_section();




        /****************************** STYLE *********************************/

        $this->start_controls_section(
                'dce_meta_section_style', [
            'label' => __('Style', DCE_TEXTDOMAIN),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        /* $this->add_control(
          'dce_meta_padding', [
          'label' => __('Padding', DCE_TEXTDOMAIN),
          'type' => Controls_Manager::DIMENSIONS,
          'selectors' => [
          '{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
          ]
          );
          $this->add_control(
          'dce_meta_margin', [
          'label' => __('Margin', DCE_TEXTDOMAIN),
          'type' => Controls_Manager::DIMENSIONS,
          'selectors' => [
          '{{WRAPPER}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
          ]
          ); */
        $this->add_responsive_control(
                'dce_meta_align', [
            'label' => __('Alignment', 'elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'elementor'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'elementor'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'elementor'),
                    'icon' => 'fa fa-align-right',
                ],
                'justify' => [
                    'title' => __('Justified', 'elementor'),
                    'icon' => 'fa fa-align-justify',
                ],
            ],
            'prefix_class' => 'elementor%s-align-',
            'selectors' => [
                '{{WRAPPER}}' => 'text-align: {{VALUE}};',
            ],
                ]
        );
        $this->add_control(
                'dce_meta_color', [
            'label' => __('Text Color', 'elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                // Stronger selector to avoid section style from overwriting
                '{{WRAPPER}}, {{WRAPPER}} .elementor-widget-container >*' => 'color: {{VALUE}};',
            ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'dce_meta_typography',
            'selector' => '{{WRAPPER}}, {{WRAPPER}} .elementor-widget-container >*',
                ]
        );

        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(), [
            'name' => 'dce_meta_text_shadow',
            'selector' => '{{WRAPPER}}, {{WRAPPER}} .elementor-widget-container >*',
                ]
        );

        $this->end_controls_section();



        // IMAGE
        $this->start_controls_section(
                'section_style_image',
                [
                    'label' => __('Image', 'elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'dce_meta_type' => 'image',
                    ],
                ]
        );
        $this->add_control(
                'dce_meta_image_margin', [
            'label' => __('Margin', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .elementor-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'width',
                [
                    'label' => __('Width', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'unit' => '%',
                    ],
                    'tablet_default' => [
                        'unit' => '%',
                    ],
                    'mobile_default' => [
                        'unit' => '%',
                    ],
                    'size_units' => ['%', 'px', 'vw'],
                    'range' => [
                        '%' => [
                            'min' => 1,
                            'max' => 100,
                        ],
                        'px' => [
                            'min' => 1,
                            'max' => 1000,
                        ],
                        'vw' => [
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-image img' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_responsive_control(
                'space',
                [
                    'label' => __('Max Width', 'elementor') . ' (%)',
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'unit' => '%',
                    ],
                    'tablet_default' => [
                        'unit' => '%',
                    ],
                    'mobile_default' => [
                        'unit' => '%',
                    ],
                    'size_units' => ['%'],
                    'range' => [
                        '%' => [
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-image img' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_control(
                'separator_panel_style',
                [
                    'type' => Controls_Manager::DIVIDER,
                    'style' => 'thick',
                ]
        );
        $this->start_controls_tabs('image_effects');
        $this->start_controls_tab('normal',
                [
                    'label' => __('Normal', 'elementor'),
                ]
        );
        $this->add_control(
                'opacity',
                [
                    'label' => __('Opacity', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 1,
                            'min' => 0.10,
                            'step' => 0.01,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-image img' => 'opacity: {{SIZE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Css_Filter::get_type(),
                [
                    'name' => 'css_filters',
                    'selector' => '{{WRAPPER}} .elementor-image img',
                ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab('hover',
                [
                    'label' => __('Hover', 'elementor'),
                ]
        );
        $this->add_control(
                'opacity_hover',
                [
                    'label' => __('Opacity', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 1,
                            'min' => 0.10,
                            'step' => 0.01,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-image:hover img' => 'opacity: {{SIZE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Css_Filter::get_type(),
                [
                    'name' => 'css_filters_hover',
                    'selector' => '{{WRAPPER}} .elementor-image:hover img',
                ]
        );
        $this->add_control(
                'background_hover_transition',
                [
                    'label' => __('Transition Duration', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 3,
                            'step' => 0.1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-image img' => 'transition-duration: {{SIZE}}s',
                    ],
                ]
        );
        $this->add_control(
                'hover_animation',
                [
                    'label' => __('Hover Animation', 'elementor'),
                    'type' => Controls_Manager::HOVER_ANIMATION,
                ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'image_border',
                    'selector' => '{{WRAPPER}} .elementor-image img',
                    'separator' => 'before',
                ]
        );
        $this->add_responsive_control(
                'image_border_radius',
                [
                    'label' => __('Border Radius', 'elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'image_box_shadow',
                    'exclude' => [
                        'box_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .elementor-image img',
                ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
                'section_style_caption',
                [
                    'label' => __('Caption', 'elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'dce_meta_image_caption_source!' => 'none',
                    ],
                ]
        );
        $this->add_control(
                'caption_align',
                [
                    'label' => __('Alignment', 'elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'elementor'),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => __('Center', 'elementor'),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => __('Right', 'elementor'),
                            'icon' => 'fa fa-align-right',
                        ],
                        'justify' => [
                            'title' => __('Justified', 'elementor'),
                            'icon' => 'fa fa-align-justify',
                        ],
                    ],
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .widget-image-caption' => 'text-align: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'text_color',
                [
                    'label' => __('Text Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .widget-image-caption' => 'color: {{VALUE}};',
                    ],
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_3,
                    ],
                ]
        );
        $this->add_control(
                'caption_background_color',
                [
                    'label' => __('Background Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .widget-image-caption' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'caption_typography',
                    'selector' => '{{WRAPPER}} .widget-image-caption',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                ]
        );
        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'caption_text_shadow',
                    'selector' => '{{WRAPPER}} .widget-image-caption',
                ]
        );
        $this->add_responsive_control(
                'caption_space',
                [
                    'label' => __('Spacing', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .widget-image-caption' => 'margin-top: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->end_controls_section();


        // MAP
        $this->start_controls_section(
                'dce_meta_section_map_style',
                [
                    'label' => __('Map', 'elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'dce_meta_type' => 'map',
                    ],
                ]
        );
        $this->start_controls_tabs('dce_meta_map_filter');
        $this->start_controls_tab('dce_meta_map_normal',
                [
                    'label' => __('Normal', 'elementor'),
                ]
        );
        $this->add_group_control(
                Group_Control_Css_Filter::get_type(),
                [
                    'name' => 'dce_meta_map_css_filters',
                    'selector' => '{{WRAPPER}} iframe',
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('dce_meta_map_hover',
                [
                    'label' => __('Hover', 'elementor'),
                ]
        );
        $this->add_group_control(
                Group_Control_Css_Filter::get_type(),
                [
                    'name' => 'dce_meta_map_css_filters_hover',
                    'selector' => '{{WRAPPER}}:hover iframe',
                ]
        );
        $this->add_control(
                'dce_meta_map_hover_transition',
                [
                    'label' => __('Transition Duration', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 3,
                            'step' => 0.1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} iframe' => 'transition-duration: {{SIZE}}s',
                    ],
                ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        // BUTTON
        $this->start_controls_section(
                'dce_meta_button_section_style',
                [
                    'label' => __('Button', 'elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'dce_meta_type' => 'button',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'dce_meta_button_typography',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                    'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
                ]
        );
        $this->start_controls_tabs('tabs_button_style');
        $this->start_controls_tab(
                'tab_button_normal',
                [
                    'label' => __('Normal', 'elementor'),
                ]
        );
        $this->add_control(
                'dce_meta_button_text_color',
                [
                    'label' => __('Text Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'dce_meta_button_background_color',
                [
                    'label' => __('Background Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_4,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => __('Hover', 'elementor'),
                ]
        );
        $this->add_control(
                'dce_meta_button_hover_color',
                [
                    'label' => __('Text Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'button_background_hover_color',
                [
                    'label' => __('Background Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'button_hover_border_color',
                [
                    'label' => __('Border Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => [
                        'border_border!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
                    ],
                ]
        );
        $this->add_control(
                'dce_meta_button_hover_animation',
                [
                    'label' => __('Hover Animation', 'elementor'),
                    'type' => Controls_Manager::HOVER_ANIMATION,
                ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'dce_meta_button_border',
                    'selector' => '{{WRAPPER}} .elementor-button',
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'dce_meta_button_border_radius',
                [
                    'label' => __('Border Radius', 'elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'button_box_shadow',
                    'selector' => '{{WRAPPER}} .elementor-button',
                ]
        );
        $this->add_responsive_control(
                'dce_meta_button_text_padding',
                [
                    'label' => __('Padding', 'elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
        );
        $this->end_controls_section();
        
        // Multiple POSTMETA
        $this->start_controls_section(
                'section_style_array',
                [
                    'label' => __('Multiple PostMeta', DCE_TEXTDOMAIN),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'dce_meta_array!' => '',
                    ],
                ]
        );
        $this->add_responsive_control(
                'dce_meta_array_margin', [
            'label' => __('Margin', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .dce-meta-value' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_responsive_control(
                'dce_meta_array_padding', [
            'label' => __('Padding', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .dce-meta-value' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'dce_meta_array_border',
                    'selector' => '{{WRAPPER}} .dce-meta-value',
                    'separator' => 'before',
                ]
        );
        $this->add_control(
                'dce_meta_array_border_radius',
                [
                    'label' => __('Border Radius', 'elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .dce-meta-value' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'array_box_shadow',
                    'selector' => '{{WRAPPER}} .dce-meta-value',
                ]
        );
        $this->add_control(
                'array_css_classes',
                [
                        'label' => __( 'CSS Classes', 'elementor' ),
                        'type' => Controls_Manager::TEXT,
                        'dynamic' => [
                                'active' => true,
                        ],
                        //'prefix_class' => '',
                        'title' => __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'elementor' ),
                ]
        );
        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display(null, true);
        if (empty($settings) || !$settings['dce_meta_key'])
            return;
        
        //$metas = DCE_Helper::get_post_metas();

        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data();
        $post_id = $dce_data['id'];
        $page_type = $dce_data['type'];
        $global_is = $dce_data['is'];
        // ------------------------------------------
        // TYPES
        $acf_types = DCE_Helper::get_acf_types();
        $pods_types = DCE_Helper::get_pods_types();
        $toolset_types = DCE_Helper::get_toolset_types();
        $all_types = array_merge($acf_types, $pods_types, $toolset_types);
        //$all_types = array_unique($all_types); sort($all_types); echo '<pre>'; print_r($all_types); echo '</pre>'; die();
        /*
          [0] => accordion
          [1] => audio
          [2] => boolean
          [3] => button_group
          [4] => checkbox
          [5] => checkboxes
          [6] => clone
          [7] => code
          [8] => color
          [9] => color_picker
          [10] => colorpicker
          [11] => currency
          [12] => date
          [13] => date_picker
          [14] => date_time_picker
          [15] => datetime
          [16] => email
          [17] => embed
          [18] => file
          [19] => flexible_content
          [20] => gallery
          [21] => google_map
          [22] => group
          [23] => image
          [24] => link
          [25] => message
          [26] => number
          [27] => numeric
          [28] => oembed
          [29] => page_link
          [30] => paragraph
          [31] => password
          [32] => phone
          [33] => pick
          [34] => post_object
          [35] => radio
          [36] => range
          [37] => relationship
          [38] => repeater
          [39] => select
          [40] => skype
          [41] => tab
          [42] => taxonomy
          [43] => text
          [44] => textarea
          [45] => textfield
          [46] => time
          [47] => time_picker
          [48] => true_false
          [49] => url
          [50] => user
          [51] => video
          [52] => website
          [53] => wysiwyg
         */

        $meta_key = $settings['dce_meta_key'];
        $meta_name = DCE_Helper::get_post_meta_name($meta_key);

        $meta_values = get_post_meta($post_id, $meta_key);
        //var_dump($meta_key);
        /* if (count($meta_value) == 1) {
          $meta_value = get_post_meta($post_id, $meta_key, true);
          } */
        //var_dump($meta_value);

        if (count($meta_values) > 1 && $settings['dce_meta_array']) {
            if ($settings['dce_meta_array_filter'] && $settings['dce_meta_array_filter'] != 'all') {
                if ($settings['dce_meta_array_filter'] == 'first') {
                    $meta_values = array(reset($meta_value));
                }
                if ($settings['dce_meta_array_filter'] == 'last') {
                    $meta_values = array(end($meta_value));
                }
            }
        }

        $render_type = $settings['dce_meta_type'];

        if (!empty($meta_values)) {

            foreach ($meta_values as $mkey => $meta_value) {

                $original_type = $this->_get_meta_type($meta_name, $meta_value, $meta_key);
                //var_dump($original_type);

                if ($render_type == 'dynamic') {
                    $render_type = $original_type;
                }

                // Default ;-)
                if ($page_type == 'elementor_library' && !$meta_value) {
                    switch ($original_type) {
                        case 'text':
                            $meta_value = 'This is a ACF text';
                            break;
                        case 'textarea':
                            $meta_value = 'This is a textarea. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla placerat faucibus ultrices. Proin tristique augue turpis.';
                            break;
                        case 'select':
                            $meta_value = 'Select a field';
                            break;
                        case 'wysiwyg':
                            $meta_value = 'This is a wysiwyg text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla placerat faucibus ultrices. Proin tristique augue turpis. Phasellus accumsan nunc dui, eget mollis nibh fringilla at. Aliquam ante enim, mattis vel mi porttitor, efficitur dapibus turpis. Donec quis ipsum nisl. Sed elit sem, lobortis id erat et, tristique dignissim nisi. Donec egestas nunc tellus, sed vestibulum ex finibus sed.';
                            break;
                        case 'number':
                            $meta_value = 23;
                            break;
                    }
                }

                //var_dump($meta_value);
                //if ($settings['dce_meta_type'] == 'dynamic') {
                if (!$settings['dce_meta_raw']) {
                    $meta_value_enchanted = DCE_Helper::get_post_meta($post_id, $meta_key, false);
                    if ($meta_value_enchanted) {
                        $meta_value = $meta_value_enchanted;
                    }
                }
                //var_dump($meta_value);

                if ($original_type == 'taxonomy' && $render_type == 'multiple') {
                    $terms = array();
                    if (is_array($meta_value) && !empty($meta_value)) {
                        foreach ($meta_value as $atermid) {
                            $terms[] = get_term($atermid);
                        }
                        $meta_value = $terms;
                    }
                }

                if ($original_type == 'user' && is_numeric($meta_value)) {
                    $meta_value = get_user_by('ID', $meta_value);
                }

                if ($original_type == 'page_link' && is_numeric($meta_value)) {
                    $meta_value = get_permalink($meta_value);
                }

                if ($original_type == 'relationship') {
                    $posts = array();
                    if (is_array($meta_value) && !empty($meta_value)) {
                        foreach ($meta_value as $apostid) {
                            if (is_numeric($apostid)) {
                                $posts[] = get_post($apostid);
                            }
                        }
                        $meta_value = $posts;
                    }
                }

                if (is_numeric($meta_value) && $render_type == 'id') {
                    $meta_value = intval($meta_value);
                    if ($settings['dce_meta_id_type'] == 'post') {
                        $meta_value = get_post($meta_value);
                    }
                    if ($settings['dce_meta_id_type'] == 'term') {
                        $meta_value = get_term($meta_value);
                    }
                    if ($settings['dce_meta_id_type'] == 'user') {
                        $meta_value = get_user_by('ID', $meta_value);
                    }
                }

                switch ($render_type) {

                    case 'custom':
                        $txt = DCE_Tokens::do_tokens($settings['dce_meta_custom']);
                        $meta_html = DCE_Tokens::replace_var_tokens($txt, 'META_VALUE', $meta_value);
                        $meta_html = do_shortcode($meta_html);
                        break;

                    case 'boolean':
                    case 'true_false':
                        if ($meta_value) {
                            $meta_html = __('yes', 'elementor');
                        } else {
                            $meta_html = __('no', 'elementor');
                        }
                        break;

                    case 'multiple':
                    case 'checkboxes':
                    case 'radio';
                    case 'select':
                        $meta_html = '';
                        if (!empty($meta_value)) {
                            if (!is_array($meta_value)) {
                                $meta_value = array($meta_value);
                            }
                            $i = 1;
                            foreach ($meta_value as $avalue) {
                                if ($settings['dce_meta_multiple_tag'] && $settings['dce_meta_multiple_tag'] != 'custom') {
                                    $meta_html .= '<' . $settings['dce_meta_multiple_tag'] . ' class="dce-meta-multiple">';
                                }
                                if ($settings['dce_meta_multiple_tag'] == 'custom') {
                                    $txt = DCE_Tokens::do_tokens($settings['dce_meta_multiple_custom']);
                                    $meta_html .= DCE_Tokens::replace_var_tokens($txt, 'SINGLE', $avalue);
                                } else {
                                    $meta_html .= DCE_Helper::to_string($avalue);
                                    if (!$settings['dce_meta_multiple_separator_last'] || ($settings['dce_meta_multiple_separator_last'] && $i < count($meta_value))) {
                                        $meta_html .= $settings['dce_meta_multiple_separator'];
                                    }
                                }
                                if ($settings['dce_meta_multiple_tag'] && $settings['dce_meta_multiple_tag'] != 'custom') {
                                    $meta_html .= '</' . $settings['dce_meta_multiple_tag'] . '>';
                                }
                                $i++;
                            }
                        }

                        // TOOLSET Repeater
                        // types_render_field( "house-photos", array( "size"=>"thumbnail", "index" => "0") );
                        /* $i = 0;
                          do {
                          $avalue = types_render_field_single( $meta_key, array( "index" => $j) );
                          var_dump($avalue);
                          if ($avalue) {
                          if ($settings['dce_meta_multiple_tag'] && $settings['dce_meta_multiple_tag'] != 'custom') {
                          $meta_html .= '<' . $settings['dce_meta_multiple_tag'] . '>';
                          }
                          if ($settings['dce_meta_multiple_tag'] == 'custom') {
                          $txt = DCE_Tokens::do_tokens($settings['dce_meta_multiple_custom']);
                          $meta_html .= DCE_Tokens::replace_var_tokens($txt, 'SINGLE', $avalue);
                          } else {
                          $meta_html .= DCE_Helper::to_string($avalue);
                          if (!$settings['dce_meta_multiple_separator_last'] || ($settings['dce_meta_multiple_separator_last'] && $i < count($meta_value))) {
                          $meta_html .= $settings['dce_meta_multiple_separator'];
                          }
                          }
                          if ($settings['dce_meta_multiple_tag'] && $settings['dce_meta_multiple_tag'] != 'custom') {
                          $meta_html .= '</' . $settings['dce_meta_multiple_tag'] . '>';
                          }
                          }
                          $i++;
                          } while ($avalue !== ''); */
                        // https://toolset.com/documentation/customizing-sites-using-php/displaying-repeating-fields-one-kind/
                        // https://toolset.com/documentation/user-guides/repeating-fields/

                        break;

                    case 'email':
                        $meta_html = '<a href="mailto:' . $meta_value . '">' . $meta_value . '</a>';
                        break;

                    case 'tel':
                    case 'phone':
                        $meta_html = '<a href="tel:' . $meta_value . '">' . $meta_value . '</a>';
                        break;

                    case 'skype':
                        $meta_html = '<a href="skype:' . $meta_value['skypename'] . '">' . $meta_value['skypename'] . '</a>';
                        break;

                    case 'number':
                    case 'numeric':
                    case 'currency':
                        $meta_html = $meta_value;
                        break;

                    case 'audio':
                        $ext = pathinfo($meta_value, PATHINFO_EXTENSION);
                        //var_dump($ext);
                        if (in_array($ext, array('mp3', 'm4a', 'ogg', 'wav', 'wma'))) {
                            $meta_html = do_shortcode('[audio src="' . $meta_value . '"]');
                        }
                        // https://codex.wordpress.org/Audio_Shortcode#Options
                        break;

                    case 'video':
                        $ext = pathinfo($meta_value, PATHINFO_EXTENSION);
                        //var_dump($ext);
                        if (in_array($ext, array('mp4', 'm4v', 'webm', 'ogv', 'wmv', 'flv'))) {
                            $meta_html = do_shortcode('[video src="' . $meta_value . '"]');
                        }
                        // https://codex.wordpress.org/it:Shortcode_Video
                        break;

                    case 'gallery':
                    case 'image':
                        if (is_array($meta_value)) {
                            $meta_html = '';
                            if (!empty($meta_value)) {
                                foreach ($meta_value as $aimg) {
                                    $meta_html .= $this->_image($aimg, $post_id, $settings);
                                }
                            }
                        } else {
                            $meta_html = $this->_image($meta_value, $post_id, $settings);
                        }
                        break;
                    // img
                    // img responsive
                    // url

                    case 'youtube':
                    //$meta_html = do_shortcode('[youtube ' . $meta_value . ']');
                    //break;
                    case 'embed':
                        $meta_html = do_shortcode('[embed]' . $meta_value . '[/embed]');
                        break;

                    case 'date':
                    case 'date_picker':
                    case 'date_time_picker':
                    case 'datetime':
                    case 'time':
                    case 'time_picker':
                        $format_display = 'Y/m/d H:i:s';
                        if ($settings['dce_meta_date_format_display']) {
                            $format_display = $settings['dce_meta_date_format_display'];
                        }
                        if ($settings['dce_meta_date_format_source']) {
                            if ($settings['dce_meta_date_format_source'] == 'timestamp') {
                                $timestamp = $meta_value;
                            } else {
                                $d = \DateTime::createFromFormat($settings['dce_meta_date_format_source'], $meta_value);
                                $timestamp = $d->getTimestamp();
                            }
                        } else {
                            $timestamp = strtotime($meta_value);
                        }
                        $meta_html = date($format_display, $timestamp);
                        break;

                    case 'file':
                        if (is_object($meta_value)) {
                            $meta_html = '<a href="' . $meta_value->guid . '">' . $meta_value->post_title . '</a>';
                        }
                        if (is_array($meta_value) && isset($meta_value['guid']) && isset($meta_value['post_title'])) {
                            $meta_html = '<a href="' . $meta_value['guid'] . '">' . $meta_value['post_title'] . '</a>';
                        }
                        if (is_array($meta_value) && isset($meta_value['url']) && isset($meta_value['name'])) {
                            $meta_html = '<a href="' . $meta_value['url'] . '">' . $meta_value['name'] . '</a>';
                        }
                        if (is_string($meta_value)) {
                            $meta_html = '<a href="' . $meta_value . '">' . basename($meta_value) . '</a>';
                        }
                        break;

                    case 'color':
                    case 'color_picker':
                    case 'colorpicker':
                        $meta_html = $meta_value;
                        break;

                    case 'google_map':
                        $meta_html = '<a href="https://www.google.com/maps/@' . $meta_value['lat'] . ',' . $meta_value['lng'] . ',15z">' . ($meta_value['address'] ? $meta_value['address'] : $meta_value['lat'] . ',' . $meta_value['lng']) . '</a>';
                        break;
                    case 'map':
                        $meta_html = $this->_map($meta_value, $settings);
                        break;

                    case 'post_object':
                        $rel_post = get_post($meta_value);
                        $meta_html = '<a href="' . get_permalink($meta_value) . '">' . $rel_post->post_title . '</a>';
                        break;

                    case 'id':
                        $meta_html = $object_id = $meta_value;
                        if (!$settings['dce_meta_id_render_type'] || $settings['dce_meta_id_render_type'] == 'simple') {
                            // POST
                            if (is_object($meta_value) && $settings['dce_meta_id_type'] == 'post') {
                                $meta_html = '<a href="' . get_permalink($meta_value->ID) . '">' . $meta_value->post_title . '</a>';
                                $object_id = $meta_value->ID;
                            }
                            // TAX
                            if (is_object($meta_value) && $settings['dce_meta_id_type'] == 'term') {
                                $meta_html = '<a href="' . get_term_link($meta_value->term_id) . '">' . $meta_value->name . '</a>';
                                $object_id = $meta_value->term_id;
                            }
                            // USER
                            if (is_object($meta_value) && $settings['dce_meta_id_type'] == 'user') {
                                $meta_html = '<a href="' . get_author_posts_url($meta_value->ID) . '">' . $meta_value->display_name . '</a>';
                                $object_id = $meta_value->ID;
                            }
                        }
                        if ($settings['dce_meta_id_type'] == 'post') {
                            if (is_object($meta_value)) {
                                $object_id = $meta_value->ID;
                            }
                            if ($settings['dce_meta_id_render_type'] == 'text') {
                                global $post;
                                $original_post = $post;
                                $post = get_post($object_id);
                                $meta_html = DCE_Tokens::do_tokens($settings['dce_meta_id_render_type_text']);
                                $post = $original_post;
                            }
                            if ($settings['dce_meta_id_render_type'] == 'template') {
                                $meta_html = do_shortcode('[dce-elementor-template id="' . $settings['dce_meta_id_render_type_template'] . '" post_id="'.$object_id.'"]');
                            }
                        }
                        break;

                    case 'pick':
                        $meta_html = $meta_value;
                        // POST
                        if (is_array($meta_value) && isset($meta_value['ID']) && isset($meta_value['post_title'])) {
                            $meta_html = '<a href="' . get_permalink($meta_value['ID']) . '">' . $meta_value['post_title'] . '</a>';
                        }
                        // TAX
                        if (is_array($meta_value) && isset($meta_value['term_id']) && isset($meta_value['name'])) {
                            $meta_html .= '<a href="' . get_term_link($meta_value['term_id']) . '">' . $meta_value['name'] . '</a>';
                        }
                        // USER
                        if (is_array($meta_value) && isset($meta_value['ID']) && isset($meta_value['display_name'])) {
                            $meta_html = '<a href="' . get_author_posts_url($meta_value['ID']) . '">' . $meta_value['display_name'] . '</a>';
                        }
                        break;

                    case 'link':
                        $meta_html = '<a href="' . $meta_value['url'] . '"' . ($meta_value['target'] ? ' target="' . $meta_value['target'] . '"' : '') . '>' . $meta_value['title'] . '</a>';
                        break;

                    case 'url':
                    case 'website':
                        $pezzi = explode('/', $meta_value);
                        if (isset($pezzi[2])) {
                            $label = $pezzi[2];
                        } else {
                            $pezzi = explode(' [', $meta_name);
                            array_pop($pezzi);
                            $label = implode(' [', $pezzi);
                        }
                        $meta_html = '<a href="' . $meta_value . '">' . $label . '</a>';
                        break;

                    case 'button':
                        $meta_html = $this->_button($meta_value, $settings);
                        break;

                    case 'taxonomy':
                        $meta_html = '';
                        if (is_array($meta_value) && !empty($meta_value)) {
                            foreach ($meta_value as $atermid) {
                                $aterm = get_term($atermid);
                                $meta_html .= '<a href="' . get_term_link($aterm) . '">' . $aterm->name . '</a>';
                                if ($atermid !== end($meta_value)) {
                                    $meta_html .= ', ';
                                }
                            }
                        }
                        break;

                    case 'relationship':
                        $meta_html = '';
                        if (is_array($meta_value) && !empty($meta_value)) {
                            foreach ($meta_value as $apost) {
                                $meta_html .= '<a href="' . get_permalink($apost) . '">' . $apost->post_title . '</a>';
                                if ($apost !== end($meta_value)) {
                                    $meta_html .= ', ';
                                }
                            }
                        }
                        break;

                    case 'user':
                        if (is_array($meta_value)) {
                            $meta_html = '<a href="' . get_author_posts_url($meta_value['ID']) . '">' . $meta_value['display_name'] . '</a>';
                        }
                        if (is_object($meta_value)) {
                            $meta_html = '<a href="' . get_author_posts_url($meta_value->ID) . '">' . $meta_value->display_name . '</a>';
                        }
                        break;

                    case 'repeater':
                        $meta_html = '';
                        if (is_array($meta_value)) {
                            if (!empty($meta_value)) {
                                foreach ($meta_value as $arow) {
                                    $meta_html .= DCE_Tokens::replace_var_tokens($settings['dce_meta_repeater'], 'ROW', $arow);
                                }
                            }
                        }
                        break;

                    case 'code':
                        $meta_html = '<pre><code>' . htmlentities($meta_value) . '</code></pre>';
                        break;

                    case 'text':
                    case 'textfield':
                    case 'textarea':
                    case 'wysiwyg':

                    case 'plugin':
                    //var_dump($meta_value);
                    default:
                        $meta_html = $meta_value;
                }


                echo '<div class="dce-meta-value '.$settings['array_css_classes'].'">';
                if ($settings['dce_meta_tag']) {
                    echo '<' . $settings['dce_meta_tag'] . ' class="dce-meta-wrapper">';
                }

                // FALLBACK
                if ($meta_value == '' || $meta_value === false || $meta_value == 'false' || $meta_value === null || $meta_value === 'NULL' || ($settings['dce_meta_fallback_zero'] && ($meta_value == 0 || $meta_value == '0'))) {
                    if (isset($settings['dce_meta_fallback']) && $settings['dce_meta_fallback']) {
                        if (isset($settings['dce_meta_fallback_type']) && $settings['dce_meta_fallback_type'] == 'template') {
                            $fallback_content = '[dce-elementor-template id="' . $settings['dce_meta_fallback_template'] . '"]';
                        } else {
                            //var_dump($settings['dce_meta_fallback_text']);
                            $fallback_content = __($settings['dce_meta_fallback_text'], DCE_TEXTDOMAIN . '_texts');
                            if ($settings['dce_meta_fallback_autop']) {
                                $fallback_content = DCE_Helper::strip_tag($fallback_content, 'p');
                            }
                        }
                        $fallback_content = do_shortcode($fallback_content); // TODO FIX
                        $fallback_content = DCE_Tokens::do_tokens($fallback_content);
                        echo $fallback_content;
                    }
                } else {

                    // PRINT RESULT HTML
                    /* if (is_array($meta_html)) {
                      echo '<pre>';
                      var_dump($meta_html);
                      echo '</pre>';
                      } elseif (is_object($meta_html)) {
                      echo '<pre>';
                      var_dump($meta_html);
                      echo '</pre>';
                      } else {
                      echo $meta_html;
                      } */
                    echo DCE_Helper::to_string($meta_html);
                }

                if ($settings['dce_meta_tag']) {
                    echo '</' . $settings['dce_meta_tag'] . '>';
                }
                echo '</div>';
            }
        } else {

            if ($settings['dce_meta_array']) {
                if (isset($settings['dce_meta_array_fallback']) && $settings['dce_meta_array_fallback']) {
                    if (isset($settings['dce_meta_array_fallback_type']) && $settings['dce_meta_array_fallback_type'] == 'template') {
                        $fallback_content = '[dce-elementor-template id="' . $settings['dce_meta_array_fallback_template'] . '"]';
                    } else {
                        //var_dump($settings['dce_meta_fallback_text']);
                        $fallback_content = __($settings['dce_meta_array_fallback_text'], DCE_TEXTDOMAIN . '_texts');
                        if ($settings['dce_meta_array_fallback_autop']) {
                            $fallback_content = DCE_Helper::strip_tag($fallback_content, 'p');
                        }
                    }
                    $fallback_content = do_shortcode($fallback_content); // TODO FIX
                    $fallback_content = DCE_Tokens::do_tokens($fallback_content);
                    echo $fallback_content;
                }
            } else {
                if (isset($settings['dce_meta_fallback']) && $settings['dce_meta_fallback']) {
                    if (isset($settings['dce_meta_fallback_type']) && $settings['dce_meta_fallback_type'] == 'template') {
                        $fallback_content = '[dce-elementor-template id="' . $settings['dce_meta_fallback_template'] . '"]';
                    } else {
                        //var_dump($settings['dce_meta_fallback_text']);
                        $fallback_content = __($settings['dce_meta_fallback_text'], DCE_TEXTDOMAIN . '_texts');
                        if ($settings['dce_meta_fallback_autop']) {
                            $fallback_content = DCE_Helper::strip_tag($fallback_content, 'p');
                        }
                    }
                    $fallback_content = do_shortcode($fallback_content); // TODO FIX
                    $fallback_content = DCE_Tokens::do_tokens($fallback_content);
                    echo $fallback_content;
                }
            }
            
            
        }
    }

    public function _map($meta_value, $settings = null) {
        $address = $meta_value['address'];
        if (!$meta_value['address']) {
            if (!$meta_value['lat'] || !$meta_value['lng']) {
                return '';
            }
            $address = $meta_value['lat'] . ',' . $meta_value['lng'];
        }

        if (0 === absint($settings['dce_meta_map_zoom']['size'])) {
            $settings['zoom']['size'] = 10;
        }
        return '<div class="elementor-custom-embed"><iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=' . rawurlencode($address) . '&amp;t=m&amp;z=' . absint($settings['dce_meta_map_zoom']['size']) . '&amp;output=embed&amp;iwloc=near" aria-label="' . esc_attr($address) . '"></iframe></div>';
    }

    public function _button($meta_value, $settings = null) {
        $this->add_render_attribute('wrapper', 'class', 'elementor-button-wrapper');
        if (!empty($settings['dce_meta_button_link']['url'])) {
            $url = $settings['dce_meta_button_link']['url'];
            $url = DCE_Tokens::replace_var_tokens($url, 'META_VALUE', $meta_value);
            $url = DCE_Tokens::do_tokens($url);
            $this->add_render_attribute('button', 'href', $url);
            $this->add_render_attribute('button', 'class', 'elementor-button-link');

            if ($settings['dce_meta_button_link']['is_external']) {
                $this->add_render_attribute('button', 'target', '_blank');
            }

            if ($settings['dce_meta_button_link']['nofollow']) {
                $this->add_render_attribute('button', 'rel', 'nofollow');
            }
        }
        $this->add_render_attribute('button', 'class', 'elementor-button');
        $this->add_render_attribute('button', 'role', 'button');
        if (!empty($settings['dce_meta_button_css_id'])) {
            $id = $settings['dce_meta_button_css_id'];
            $id = DCE_Tokens::replace_var_tokens($id, 'META_VALUE', $meta_value);
            $id = DCE_Tokens::do_tokens($id);
            $this->add_render_attribute('button', 'id', $id);
        }
        if (!empty($settings['dce_meta_button_size'])) {
            $this->add_render_attribute('button', 'class', 'elementor-size-' . $settings['dce_meta_button_size']);
        }
        if ($settings['dce_meta_button_hover_animation']) {
            $this->add_render_attribute('button', 'class', 'elementor-animation-' . $settings['dce_meta_button_hover_animation']);
        }
        $this->add_render_attribute([
            'content-wrapper' => [
                'class' => 'elementor-button-content-wrapper',
            ],
            'icon-align' => [
                'class' => [
                    'elementor-button-icon',
                    'elementor-align-icon-' . $settings['dce_meta_button_icon_align'],
                ],
            ],
            'text' => [
                'class' => 'elementor-button-text',
            ],
        ]);
        $this->add_inline_editing_attributes('text', 'none');

        $txt = $settings['dce_meta_button_text'];
        $txt = DCE_Tokens::replace_var_tokens($txt, 'META_VALUE', $meta_value);
        $txt = DCE_Tokens::do_tokens($txt);

        $meta_html = '<div ' . $this->get_render_attribute_string('wrapper') . '>';
        $meta_html .= '<a ' . $this->get_render_attribute_string('button') . '>';
        $meta_html .= '<span ' . $this->get_render_attribute_string('content-wrapper') . '>';
        if (!empty($settings['dce_meta_button_icon'])) {
            $meta_html .= '<span ' . $this->get_render_attribute_string('icon-align') . '>';
            $meta_html .= '<i class="' . esc_attr($settings['dce_meta_button_icon']) . '" aria-hidden="true"></i>';
            $meta_html .= '</span>';
        }
        $meta_html .= '<span ' . $this->get_render_attribute_string('text') . '>';
        //$meta_html .= $txt;
        $meta_html .= '</span>';
        $meta_html .= '</span>';

        $meta_html .= $txt;
        $meta_html .= '</a>';
        $meta_html .= '</div>';

        return $meta_html;
    }

    public function _image($meta_value, $post_id = null, $settings = null) {
        if (!$settings) {
            //$settings = $this->get_settings_for_display();
        }
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        $meta_html = '';
        // URL
        $image_id = 0;
        if (is_array($meta_value)) { // ACF
            if (isset($meta_value['ID'])) {
                $image_id = $meta_value['ID'];
            }
            if (isset($meta_value['id'])) {
                $image_id = $meta_value['id'];
            }
        }
        if (is_numeric($meta_value) && intval($meta_value)) {
            $post_img = get_post(intval($meta_value));
            if (get_post_type($post_img) == 'attachment') {
                $image_id = intval($meta_value);
            } else {
                $img_post_id = intval($meta_value);
                $image_id = get_post_thumbnail_id($img_post_id);
            }
        }
        if (!$image_id) {
            $upload_dir = '/wp-content/uploads/';
            $pezzi = explode($upload_dir, $meta_value, 2);
            //var_dump($pezzi);
            if (count($pezzi) == 2) {
                $tmp = DCE_Helper::get_image_id($upload_dir . end($pezzi));
                if ($tmp) {
                    $image_id = $tmp;
                }
            }
        }
        if ($image_id) {
            $img = wp_get_attachment_image_src($image_id, 'full'); //, array($w,$h));
            $img_url_full = reset($img);
            $img_post_id = attachment_url_to_postid($img_url_full);

            $img_url = Group_Control_Image_Size::get_attachment_image_src($image_id, 'dce_meta_image_size', $settings);
        } else {
            $img_url_full = $img_url = $meta_value;
        }
        // CAPTION
        $caption = '';
        if ($settings['dce_meta_image_caption_source']) {
            switch ($settings['dce_meta_image_caption_source']) {
                case 'attachment':
                    $caption = wp_get_attachment_caption($image_id);
                    break;
                case 'custom':
                    $caption = !empty($settings['dce_meta_image_caption']) ? $settings['dce_meta_image_caption'] : '';
            }
        }
        // LINK
        switch ($settings['dce_meta_image_link_to']) {
            case 'custom':
                if (!isset($settings['dce_meta_image_link_to']['url'])) {
                    $link = false;
                }
                $link = $settings['dce_meta_image_link'];
                break;
            case 'file':
                $link = array('url' => $img_url_full);
                break;
            case 'post':
                if ($img_post_id) {
                    $permalink = get_permalink($img_post_id);
                } else {
                    $permalink = get_permalink($post_id);
                }
                $link = array('url' => $permalink);
                break;
            default :
                $link = false;
        }
        if ($link) {
            $this->add_render_attribute('link', ['href' => $link['url']], null, true);
            if (!empty($link['is_external'])) {
                $this->add_render_attribute('link', 'target', '_blank', true);
            }
            if (!empty($link['nofollow'])) {
                $this->add_render_attribute('link', 'rel', 'nofollow', true);
            }
        }
        $meta_html .= '<div class="elementor-image">';
        if ($caption) {
            $meta_html .= '<figure class="wp-caption">';
        }
        if ($link) {
            $meta_html .= '<a ' . $this->get_render_attribute_string('link') . '>';
        }
        $meta_html .= '<img src="' . $img_url . '">';
        if ($link) {
            $meta_html .= '</a>';
        }
        if ($caption) {
            $meta_html .= '<figcaption class="widget-image-caption wp-caption-text">' . $caption . '</figcaption>';
        }
        if ($caption) {
            $meta_html .= '</figure>';
        }
        $meta_html .= '</div>';
        return $meta_html;
    }

    // retrieves the attachment ID from the file URL
    /* public function _get_image_id($image_url) {
      //echo $image_url;
      global $wpdb;
      $sql = "SELECT ID FROM " . $wpdb->prefix . "posts WHERE guid LIKE '%" . $image_url . "';";
      //var_dump($sql);
      $attachment = $wpdb->get_col($sql);
      //var_dump($attachment);
      return reset($attachment);
      } */

    public function _get_meta_type($meta_name, $meta_value, $meta_key) {
        
        $meta_type = false;
        
        // AUTO DETECT POSSIBLE TYPE
        $pezzi = explode('[', $meta_name, 2);
        if (count($pezzi) > 1) {
            $pezzi = explode(']', end($pezzi), 2);
            $meta_type = reset($pezzi);
        } else {
            $meta_type = DCE_Helper::get_post_meta_type($meta_key);
        }
        
        if ($meta_type) {
            switch ($meta_type) {
                case 'gallery':
                    return 'image';

                case 'embed':
                    if (strpos($meta_value, 'https://www.youtube.com/') !== false || strpos($meta_value, 'https://youtu.be/') !== false) {
                        return 'youtube';
                    }

                default:
                    return $meta_type;
            }
        } else {
            if (is_numeric($meta_value)) {
                return 'number';
            }
            // Validate e-mail
            if (filter_var($meta_value, FILTER_VALIDATE_EMAIL) !== false) {
                return 'email';
            }

            // Youtube url
            if (is_string($meta_value)) {
                if (strpos($meta_value, 'https://www.youtube.com/') !== false || strpos($meta_value, 'https://youtu.be/') !== false) {
                    return 'youtube';
                }
                $ext = pathinfo($meta_value, PATHINFO_EXTENSION);
                if (in_array($ext, array('mp3', 'm4a', 'ogg', 'wav', 'wma'))) {
                    return 'audio';
                }
                if (in_array($ext, array('mp4', 'm4v', 'webm', 'ogv', 'wmv', 'flv'))) {
                    return 'video';
                }

                // Validate url
                if (filter_var($meta_value, FILTER_SANITIZE_URL) !== false) {
                    return 'url';
                }
                if (substr($meta_value, 0, 7) == 'http://' || substr($meta_value, 0, 8) == 'https://') {
                    return 'url';
                }
            }
        }
        return 'text';
    }

    public static function get_button_sizes() {
        return [
            'xs' => __('Extra Small', 'elementor'),
            'sm' => __('Small', 'elementor'),
            'md' => __('Medium', 'elementor'),
            'lg' => __('Large', 'elementor'),
            'xl' => __('Extra Large', 'elementor'),
        ];
    }

}
