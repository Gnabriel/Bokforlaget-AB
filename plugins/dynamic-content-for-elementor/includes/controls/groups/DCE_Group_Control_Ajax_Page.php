<?php
namespace DynamicContentForElementor\Controls;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use DynamicContentForElementor\DCE_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Custom AjaxPage group control
 *
 * @since 0.5.0
 */
class DCE_Group_Control_Ajax_Page extends Group_Control_Base {
	
	protected static $fields;
	protected static $control_id;

	public static function get_type() {
		return 'ajax-page';
	}
	public static function get_anim_open() {
		
		$anim_p = [
			'none' => _x( 'None', 'Ajax Page', DCE_TEXTDOMAIN ),
			'enterFromFade' => _x( 'Fade', 'Ajax Page', DCE_TEXTDOMAIN ),
			'enterFromLeft' => _x( 'Left', 'Ajax Page', DCE_TEXTDOMAIN ),
			'enterFromRight' => _x( 'Right', 'Ajax Page', DCE_TEXTDOMAIN ),
			'enterFromTop' => _x( 'Top', 'Ajax Page', DCE_TEXTDOMAIN ),
			'enterFromBottom' => _x( 'Bottom', 'Ajax Page', DCE_TEXTDOMAIN ),
			'enterFormScaleBack' => _x( 'Zoom Back', 'Ajax Page', DCE_TEXTDOMAIN ),
			'enterFormScaleFront' => _x( 'Zoom Front', 'Ajax Page', DCE_TEXTDOMAIN ),
			'flipInLeft' => _x( 'Flip Left', 'Ajax Page', DCE_TEXTDOMAIN ),
			'flipInRight' => _x( 'Flip Right', 'Ajax Page', DCE_TEXTDOMAIN ),
			'flipInTop' => _x( 'Flip Top', 'Ajax Page', DCE_TEXTDOMAIN ),
			'flipInBottom' => _x( 'Flip Bottom', 'Ajax Page', DCE_TEXTDOMAIN ),
			//'flip' => _x( 'Flip', 'Ajax Page', DCE_TEXTDOMAIN ),
			//'pushSlide' => _x( 'Push Slide', 'Ajax Page', DCE_TEXTDOMAIN ),
		];
		
		return $anim_p;
	}
	public static function get_anim_close() {
		
		$anim_p = [
			'none' => _x( 'None', 'Ajax Page', DCE_TEXTDOMAIN ),
			'exitToFade' => _x( 'Fade', 'Ajax Page', DCE_TEXTDOMAIN ),
			'exitToLeft' => _x( 'Left', 'Ajax Page', DCE_TEXTDOMAIN ),
			'exitToRight' => _x( 'Right', 'Ajax Page', DCE_TEXTDOMAIN ),
			'exitToTop' => _x( 'Top', 'Ajax Page', DCE_TEXTDOMAIN ),
			'exitToBottom' => _x( 'Bottom', 'Ajax Page', DCE_TEXTDOMAIN ),
			'exitToScaleBack' => _x( 'Zoom Back', 'Ajax Page', DCE_TEXTDOMAIN ),
			'exitToScaleFront' => _x( 'Zoom Front', 'Ajax Page', DCE_TEXTDOMAIN ),
			'flipOutLeft' => _x( 'Flip Left', 'Ajax Page', DCE_TEXTDOMAIN ),
			'flipOutRight' => _x( 'Flip Right', 'Ajax Page', DCE_TEXTDOMAIN ),
			'flipOutTop' => _x( 'Flip Top', 'Ajax Page', DCE_TEXTDOMAIN ),
			'flipOutBottom' => _x( 'Flip Bottom', 'Ajax Page', DCE_TEXTDOMAIN ),
			//'flip' => _x( 'Flip', 'Ajax Page', DCE_TEXTDOMAIN ),
			//'pushSlide' => _x( 'Push Slide', 'Ajax Page', DCE_TEXTDOMAIN ),
		];
		
		return $anim_p;
	}
	
	protected function init_fields() {
		$fields = [];

		$fields['enabled'] = [
			'label' => __( 'Enable Ajax Page', DCE_TEXTDOMAIN ),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'label_on' => __( 'Yes', DCE_TEXTDOMAIN ),
            'label_off' => __( 'No', DCE_TEXTDOMAIN ),
            'return_value' => 'open',
            //'render_type' => 'template',
            'frontend_available' => true,
            'prefix_class' => 'ajax-',
		];
		$fields['template'] = [
                'label' => __('Select Template', DCE_TEXTDOMAIN),
                'type' => Controls_Manager::SELECT,
                //'options' => get_post_taxonomies( $post->ID ),
                'options' => DCE_Helper::get_all_template(false),
                'default' => '0',
                'frontend_available' => true,
                'condition' => [
                    'enabled' => 'open',
                ],
            ];
        /*$fields['id_of_content'] = [
        	'label'       => __( 'ID (#) of container', DCE_TEXTDOMAIN ),
             'type'        => Controls_Manager::TEXT,
             'description' => 'Intercetta nel tuo thema il contenitore dell\'intero sito, quello che si trova appena dentro al BODY e che contiene l\'header il content e il footer',
             'default'     => __( 'wrap', DCE_TEXTDOMAIN ),
             'placeholder' => __( 'wrap', DCE_TEXTDOMAIN ),
             'frontend_available' => true,
        ];*/
        $fields['animations_heading_modal'] = [
            'label' => __('Animation MODAL', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
				'enabled' => 'open',
			],
        ];
		$fields['animation_open_modal'] = [
			'label' => _x( 'Enter modal from', 'Animation Control', DCE_TEXTDOMAIN ),
			'type' => Controls_Manager::SELECT,
			'default' => 'enterFromRight',
			'options' => self::get_anim_open(),
			'condition' => [
					'enabled' => 'open',
				],
			'selectors' => [
				
				'body.modal-p-on.modal-p-{{ID}} .wrap-p .modal-p' => 'animation-name: {{VALUE}}; -webkit-animation-name: {{VALUE}};',
			],
		];
		$fields['animation_close_modal'] = [
			'label' => _x( 'Close modal to', 'Animation Control', DCE_TEXTDOMAIN ),
			'type' => Controls_Manager::SELECT,
			'default' => 'exitToRight',
			'options' => self::get_anim_close(),
			'condition' => [
					'enabled' => 'open',
				],
			'selectors' => [
				'body.modal-p-off.modal-p-{{ID}} .wrap-p .modal-p' => 'animation-name: {{VALUE}}; -webkit-animation-name: {{VALUE}};',
			],
		];
		$fields['animations_timingFunction_heading_modal'] = [
            'label' => __('Timing Function', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::HEADING,
            'condition' => [
				'enabled' => 'open',
			]
		];
		$fields['timingfunction_modal_enter'] = [
			'label' => _x( 'Enter modal', 'Animation Control', DCE_TEXTDOMAIN ),
			'type' => Controls_Manager::SELECT,
			'default' => 'ease',
			'options' => DCE_Helper::get_anim_timingFunctions(),
			'selectors' => [
				'body.modal-p-on.modal-p-{{ID}} .wrap-p .modal-p' => 'animation-timing-function: {{VALUE}}; -webkit-animation-timing-function: {{VALUE}};',
			],
			'condition' => [
					'enabled' => 'open',
				],
		];
		$fields['timingfunction_modal_close'] = [
			'label' => _x( 'Close modal', 'Animation Control', DCE_TEXTDOMAIN ),
			'type' => Controls_Manager::SELECT,
			'default' => 'ease',
			'options' => DCE_Helper::get_anim_timingFunctions(),
			'selectors' => [
				'body.modal-p-off.modal-p-{{ID}} .wrap-p .modal-p' => 'animation-timing-function: {{VALUE}}; -webkit-animation-timing-function: {{VALUE}};',
			],
			'condition' => [
					'enabled' => 'open',
				],
		];
		$fields['animations_time_heading_modal'] = [
            'label' => __('Time', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::HEADING,
            'condition' => [
				'enabled' => 'open',
			]
		];
		$fields['duration_modal'] = [
            'label' => _x( 'Duration', 'Animation Control', DCE_TEXTDOMAIN ),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'unit' => 's',
                'size' => 0.7
            ],
            'range' => [
                's' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            'size_units' => [ 's' ],

            'selectors' => [
				 'body.modal-p-{{ID}} .wrap-p .modal-p' => 'animation-duration: {{SIZE}}{{UNIT}}; -webkit-animation-duration: {{SIZE}}{{UNIT}};',
			],
            'condition' => [
				'enabled' => 'open',
			],
        ];
		
		$fields['delay_modal'] = [
            'label' => _x( 'Enter delay', 'Animation Control', DCE_TEXTDOMAIN ), 
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'unit' => 's',
                'size' => 0,
            ],
            'range' => [
                's' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            'size_units' => [ 's' ],
            'selectors' => [
				'body.modal-p-on.modal-p-{{ID}} .wrap-p .modal-p' => 'animation-delay: {{SIZE}}{{UNIT}}; -webkit-animation-delay: {{SIZE}}{{UNIT}};',
			],
            'condition' => [
				'enabled' => 'open',
			],
			'separator' => 'after',
        ];
		$fields['animations_heading_body'] = [
            'label' => __('Animation BODY', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
				'enabled' => 'open',
			],
        ];
		$fields['animation_close_body'] = [
			'label' => _x( 'Exit body to', 'Animation Control', DCE_TEXTDOMAIN ),
			'type' => Controls_Manager::SELECT,
			'default' => 'exitToLeft',
			'options' => self::get_anim_close(),
			'condition' => [
					'enabled' => 'open',
				],
			'selectors' => [
				'body.modal-p-on.modal-p-{{ID}} #dce-wrap' => 'animation-name: {{VALUE}}; -webkit-animation-name: {{VALUE}};',
			],
		];
		$fields['animation_open_body'] = [
			'label' => _x( 'Return body from', 'Animation Control', DCE_TEXTDOMAIN ),
			'type' => Controls_Manager::SELECT,
			'default' => 'enterFromLeft',
			'options' => self::get_anim_open(),
			'condition' => [
					'enabled' => 'open',
				],
			'selectors' => [
				'body.modal-p-off.modal-p-{{ID}} #dce-wrap' => 'animation-name: {{VALUE}}; -webkit-animation-name: {{VALUE}};',
			],
		];
		$fields['animations_timingFunction_heading_body'] = [
            'label' => __('Timing Function', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::HEADING,
            'condition' => [
				'enabled' => 'open',
			]
		];
		$fields['timingfunction_exit_body'] = [
			'label' => _x( 'Exit', 'Animation Control', DCE_TEXTDOMAIN ),
			'type' => Controls_Manager::SELECT,
			'default' => 'ease',
			'options' => DCE_Helper::get_anim_timingFunctions(),
			'selectors' => [
				'body.modal-p-on.modal-p-{{ID}} #dce-wrap' => 'animation-timing-function: {{VALUE}}; -webkit-animation-timing-function: {{VALUE}};',
			],
			'condition' => [
					'enabled' => 'open',
				],
		];
		$fields['timingfunction_return_body'] = [
			'label' => _x( 'Return', 'Animation Control', DCE_TEXTDOMAIN ),
			'type' => Controls_Manager::SELECT,
			'default' => 'ease',
			'options' => DCE_Helper::get_anim_timingFunctions(),
			'selectors' => [
				'body.modal-p-off.modal-p-{{ID}} #dce-wrap' => 'animation-timing-function: {{VALUE}}; -webkit-animation-timing-function: {{VALUE}};',
			],
			'condition' => [
					'enabled' => 'open',
				],
		];
		$fields['animations_time_heading_body'] = [
            'label' => __('Time', DCE_TEXTDOMAIN),
            'type' => Controls_Manager::HEADING,
            'condition' => [
				'enabled' => 'open',
			]
		];
		$fields['duration_body'] = [
            'label' => _x( 'Duration', 'Animation Control', DCE_TEXTDOMAIN ),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'unit' => 's',
                'size' => 0.7
            ],
            'range' => [
                's' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            'size_units' => [ 's' ],
            

            'selectors' => [
				'body.modal-p-{{ID}} #dce-wrap' => 'animation-duration: {{SIZE}}{{UNIT}}; -webkit-animation-duration: {{SIZE}}{{UNIT}};',
			],
            'condition' => [
				'enabled' => 'open',
			],
        ];
		$fields['delay_body'] = [
            'label' => _x( 'Return delay', 'Animation Control', DCE_TEXTDOMAIN ), 
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'unit' => 's',
                'size' => 0,
            ],
            'range' => [
                's' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            'size_units' => [ 's' ],
            'selectors' => [
				'body.modal-p-off.modal-p-{{ID}} #dce-wrap' => 'animation-delay: {{SIZE}}{{UNIT}}; -webkit-animation-delay: {{SIZE}}{{UNIT}};',
			],
            'condition' => [
				'enabled' => 'open',
			],
        ];
        //var_dump($this->get_args().$this->get_options());
		return $fields;
	}
	/*
	animation-timing-function: steps(10);

	animation-name: example;
    animation-duration: 4s;
    
    animation-iteration-count: 3;
    animation-iteration-count: infinite;

    animation-direction: alternate;
    animation-direction: alternate-reverse;

	animation-delay: 2s;

    animation-fill-mode: forwards;
    animation-fill-mode: backwards;
    animation-fill-mode: both; 

    -webkit-animation-timing-function: linear;
	
	animation-play-state: paused; running

	animation-timing-function: linear
	animation-timing-function: ease
	animation-timing-function: ease-in
	animation-timing-function: ease-out
	animation-timing-function: ease-in-out

    animation: example 5s linear 2s infinite alternate;
	*/
	protected function add_group_args_to_field( $control_id, $field_args ) {
		self::$control_id = $control_id;
		$field_args = parent::add_group_args_to_field( $control_id, $field_args );

		/*$args = $this->get_args();

		//if ( in_array( $control_id, self::get_scheme_fields_keys() ) && ! empty( $args['scheme'] ) ) {
			$field_args['scheme'] = [
				'type' => self::get_type(),
				'value' => $args['scheme'],
				'key' => $control_id,
			];
		//}*/

		return $field_args;
	}
	
	protected function get_default_options() {
		return [
			'popover' => false,
			/*'popover' => [
				'starter_title' => _x( 'Ajax Page.', 'Animation Control', DCE_TEXTDOMAIN ),
				'starter_name' => 'ajax_page',
			],*/
		];
	}
}
