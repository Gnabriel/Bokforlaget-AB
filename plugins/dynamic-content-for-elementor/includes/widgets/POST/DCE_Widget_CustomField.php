<?php
namespace DynamicContentForElementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use DynamicContentForElementor\DCE_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Press Elements Post Custom Field BETA
 *
 * Elementor widget for Dynamic Content for Elementor
 *
 */
class DCE_Widget_CustomField extends DCE_Widget_Prototype {

    public function get_name() {
        return 'dyncontel-custom-field';
    }
    static public function is_enabled() {
        return false;
    }
    public function get_title() {
        return __('CustomField', DCE_TEXTDOMAIN);
        
    }
    public function get_icon() {
        return 'icon-dyn-customfields';
    }
    static public function get_position() {
        return 3;
    }
    protected function _register_controls() {
        $post_type_object = get_post_type_object(get_post_type());
        $this->start_controls_section('section_pro_feature', array(
            'label' => __('Custom Field', DCE_TEXTDOMAIN),
        ));

        $this->end_controls_section();

        $this->start_controls_section(
            'section_dce_settings', [
                'label' => __('Dynamic content', DCE_TEXTDOMAIN),
                'tab' => Controls_Manager::TAB_SETTINGS,

            ]
        );
         $this->add_control(
            'data_source',
            [
              'label' => __( 'Source', DCE_TEXTDOMAIN ),
              'description' => __( 'Select the data source', DCE_TEXTDOMAIN ),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'label_on' => __( 'Same', DCE_TEXTDOMAIN ),
              'label_off' => __( 'other', DCE_TEXTDOMAIN ),
              'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'other_post_source', [
              'label' => __('Select from other source post', DCE_TEXTDOMAIN),
              'type' => Controls_Manager::SELECT,
              
              'groups' => DCE_Helper::get_all_posts(get_the_ID(), true),
              'default' => '',
              'condition' => [
                'data_source' => '',
              ], 
            ]
        );
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( empty( $settings ) )
            return;
        //
        // ------------------------------------------
        $demoPage = get_post_meta(get_the_ID(), 'demo_id', true);
        //
        $id_page = ''; //get_the_ID();
        $type_page = '';
        //
        if( $settings['data_source'] == 'yes' ){
            global $global_ID;
            global $global_TYPE;
            global $is_blocks;
            global $global_is;
            //
            if(!empty($demoPage)){
                $id_page = $demoPage;
                $type_page = get_post_type($demoPage);
                //echo 'DEMO ...';
            } 
            else if (!empty($global_ID)) {
                $id_page = $global_ID;
                $type_page = get_post_type($id_page);
                //echo 'global ...';
            } else {
                $id_page = get_the_id();
                $type_page = get_post_type();
                //echo 'natural ...';
            }
        }else{
            $id_page = $settings['other_post_source'];
            $type_page = get_post_type($id_page);
        }
        // ------------------------------------------
        //
    }
}
