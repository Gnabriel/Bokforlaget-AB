<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use DynamicContentForElementor\DCE_Helper;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Elementor Name of Type
 *
 * Single post/page title element for elementor.
 *
 */
class DCE_Widget_TitleType extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-titleType';
    }
    
    static public function is_enabled() {
        return true;
    }

    public function get_title() {
        return __('Title Type', DCE_TEXTDOMAIN);
        /* $post_type_object = get_post_type_object( get_post_type() );

          return sprintf(
          // translators: %s: Post type singular name (e.g. Post or Page)
          __( '%s Title', DCE_TEXTDOMAIN ),
          $post_type_object->labels->singular_name
          ); */
    }
    public function get_description() {
        return __('Put the title of the post type on a page', DCE_TEXTDOMAIN);
    }
    public function get_docs() {
        return 'https://www.dynamic.ooo/widget/title-type/';
    }
    public function get_icon() {
        return 'icon-dyn-title-type';
    }
    
    static public function get_position() {
        return 1;
    }

    protected function _register_controls() {

        $post_type_object = get_post_type_object(get_post_type());

        $this->start_controls_section(
            'section_titleType', [
                'label' => 'Type Title'
            ]
        );
        $this->add_control(
            'titleType_text_before', [
                'label' => __('Text Before', DCE_TEXTDOMAIN),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $this->add_control(
            'titleType_text_after', [
                'label' => __('Text After', DCE_TEXTDOMAIN),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $this->add_control(
            'html_tag', [
                'label' => __('HTML Tag', DCE_TEXTDOMAIN),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => __('H1', DCE_TEXTDOMAIN),
                    'h2' => __('H2', DCE_TEXTDOMAIN),
                    'h3' => __('H3', DCE_TEXTDOMAIN),
                    'h4' => __('H4', DCE_TEXTDOMAIN),
                    'h5' => __('H5', DCE_TEXTDOMAIN),
                    'h6' => __('H6', DCE_TEXTDOMAIN),
                    'p' => __('p', DCE_TEXTDOMAIN),
                    'div' => __('div', DCE_TEXTDOMAIN),
                    'span' => __('span', DCE_TEXTDOMAIN),
                ],
                'default' => 'h2',
            ]
        );

        $this->add_responsive_control(
            'align', [
                'label' => __('Alignment', DCE_TEXTDOMAIN),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', DCE_TEXTDOMAIN),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', DCE_TEXTDOMAIN),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', DCE_TEXTDOMAIN),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', DCE_TEXTDOMAIN),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_to', [
                'label' => __('Link to', DCE_TEXTDOMAIN),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None', DCE_TEXTDOMAIN),
                    'home' => __('Home URL', DCE_TEXTDOMAIN),
                    'post' => 'Post URL',
                    'custom' => __('Custom URL', DCE_TEXTDOMAIN),
                ],
            ]
        );

        $this->add_control(
            'link', [
                'label' => __('Link', DCE_TEXTDOMAIN),
                'type' => Controls_Manager::URL,
                'placeholder' => __('http://your-link.com', DCE_TEXTDOMAIN),
                'condition' => [
                    'link_to' => 'custom',
                ],
                'default' => [
                    'url' => '',
                ],
                'show_label' => false,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style', [
                'label' => 'Title', DCE_TEXTDOMAIN,
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color', [
                'label' => __('Text Color', DCE_TEXTDOMAIN),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dynamic-content-for-elementor-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .dynamic-content-for-elementor-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .dynamic-content-for-elementor-title',
            ]
        );
        $this->add_control(
            'rollhover_heading',
            [
                'label' => __( 'Roll-Hover', DCE_TEXTDOMAIN ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'link_to!' => 'none',
                ],
            ]
        );
        $this->add_control(
            'hover_color', [
                'label' => __('Hover Text Color', DCE_TEXTDOMAIN),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} .dynamic-content-for-elementor-title a:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'link_to!' => 'none',
                ],
            ]
        );
         $this->add_control(
            'hover_animation', [
                'label' => __('Hover Animation', DCE_TEXTDOMAIN),
                'type' => Controls_Manager::HOVER_ANIMATION,
                'condition' => [
                    'link_to!' => 'none',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings))
         return;

        // ------------------------------------------
        $dce_data = DCE_Helper::dce_dynamic_data();
        $id_page = $dce_data['id'];
        $global_is = $dce_data['is'];
        $type_page = $dce_data['type'];
        // ------------------------------------------
        $title = '';
        $postTypeObj = get_post_type_object($type_page);
        if ( $settings['titleType_text_before'] != "" )
            $title .= '<span>' . __($settings['titleType_text_before'], DCE_TEXTDOMAIN) . '</span>';
        if( isset($postTypeObj->label) ) $title .= $postTypeObj->label;
        if ($settings['titleType_text_after'] != "")
            $title .= '<span>' . __($settings['titleType_text_after'], DCE_TEXTDOMAIN) . '</span>';
        // ----------------------------------------
        if (empty($title))
            return;



        switch ($settings['link_to']) {
            case 'custom' :
                if (!empty($settings['link']['url'])) {
                    $link = esc_url($settings['link']['url']);
                } else {
                    $link = false;
                }
                break;

            case 'post' :
                $link = get_post_type_archive_link( $type_page ); //esc_url(get_the_permalink($id_page));
                break;

            case 'home' :
                $link = esc_url(get_home_url());
                break;

            case 'none' :
            default:
                $link = false;
                break;
        }



        $target = $settings['link']['is_external'] ? 'target="_blank"' : '';

        $animation_class = !empty($settings['hover_animation']) ? 'elementor-animation-' . $settings['hover_animation'] : '';

        $html = sprintf('<%1$s class="dynamic-content-for-elementor-title %2$s">', $settings['html_tag'], $animation_class);
        if ($link) {
            $html .= sprintf('<a href="%1$s" %2$s>%3$s</a>', $link, $target, $title);
        } else {
            $html .= $title;
        }
        $html .= sprintf('</%s>', $settings['html_tag']);

        echo $html;
    }

    protected function _content_template() {
        /* global $global_ID;
          global $global_TYPE;
          global $is_blocks;


          $postTypeObj = $global_ID; */
        /*
          ?>
          <#

          var title = settings.titleType_text_before+"<?php echo $postTypeObj; ?>"+settings.titleType_text_after;

          var link_url;
          switch( settings.link_to ) {
          case 'custom':
          link_url = settings.link.url;
          break;
          case 'post':
          link_url = '<?php echo esc_url( get_the_permalink() ); ?>';
          break;
          case 'home':
          link_url = '<?php echo esc_url( get_home_url() ); ?>';
          break;
          case 'none':
          default:
          link_url = false;
          }
          var target = settings.link.is_external ? 'target="_blank"' : '';

          var animation_class = '';
          if ( '' !== settings.hover_animation ) {
          animation_class = 'elementor-animation-' + settings.hover_animation;
          }

          var html = '<' + settings.html_tag + ' class="dynamic-content-for-elementor-title ' + animation_class + '">';
          if ( link_url ) {
          html += '<a href="' + link_url + '" ' + target + '>' + title + '</a>';
          } else {
          html += title;
          }
          html += '</' + settings.html_tag + '>';

          print( html );
          #>
          <?php
         */
    }

}
