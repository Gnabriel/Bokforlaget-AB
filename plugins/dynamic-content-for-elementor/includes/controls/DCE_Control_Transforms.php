<?php
namespace DynamicContentForElementor\Controls;

use Elementor\Control_Base_Multiple;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Transforms Control.
 *
 */
class DCE_Control_Transforms extends Control_Base_Multiple {

	/**
	 * Get box shadow control type.
	 *
	 * Retrieve the control type, in this case `transforms`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'transforms';
	}
	public function enqueue() {
		// Styles
		//wp_register_style( 'emojionearea', 'https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.1/emojionearea.css', [], '3.4.1' );
		//wp_enqueue_style( 'emojionearea' );

		// Scripts
		//wp_register_script( 'emojionearea', 'https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.1/emojionearea.js', [], '3.4.1' );
		wp_register_script( 'transforms-control', plugins_url('/assets/js/transforms-control.js', DCE__FILE__), [ 'jquery' ], '1.0.0' );
		wp_enqueue_script( 'transforms-control' );
	}
	/**
	 * Get box shadow control default value.
	 *
	 * Retrieve the default value of the box shadow control. Used to return the
	 * default values while initializing the box shadow control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control default value.
	 */
	public function get_default_value() {
		return parent::get_default_value();
	}
	/*public function get_default_value() {
		return array_merge(
			parent::get_default_value(), [
				'angle' => 0,
				'rotate_x' => 0,
				'rotate_y' => 0,
				'translate_x' => 0,
				'translate_y' => 0,
				'translate_z' => 0,
				'scale' => 1,
			]
		);
	}*/
	protected function get_default_settings() {
		return [
			'label_block' => true,
		];
	}
	/**
	 * Get box shadow control sliders.
	 *
	 * Retrieve the sliders of the box shadow control. Sliders are used while
	 * rendering the control output in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control sliders.
	 */
	public function get_sliders() {
		return [
			'angle' => [
				'label' => __( 'Angle', DCE_TEXTDOMAIN ),
				'min' => -360,
				'max' => 360,
				'step' => 1
			],
			'rotate_x' => [
				'label' => __( 'Rotate X', DCE_TEXTDOMAIN ),
				'min' => -360,
				'max' => 360,
				'step' => 1
			],
			'rotate_y' => [
				'label' => __( 'Rotate Y', DCE_TEXTDOMAIN ),
				'min' => -360,
				'max' => 360,
				'step' => 1
			],
			'translate_x' => [
				'label' => __( 'Translate X', DCE_TEXTDOMAIN ),
				'min' => -1000,
				'max' => 1000,
				'step' => 1
			],
			'translate_y' => [
				'label' => __( 'Translate Y', DCE_TEXTDOMAIN ),
				'min' => -1000,
				'max' => 1000,
				'step' => 1
			],
			'translate_z' => [
				'label' => __( 'Translate Z', DCE_TEXTDOMAIN ),
				'min' => -1000,
				'max' => 1000,
				'step' => 1
			],
			'scale' => [
				'label' => __( 'Scale', DCE_TEXTDOMAIN ),
				'min' => 0.1,
				'max' => 3,
				'step' => 0.1
			],
			
		];
	}
	/**
	 * Render box shadow control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title control-title-first">{{{ data.label }}}</label>
			<button href="#" class="reset-controls" title="Reset"><i class="fa fa-close"></i></button>
		</div>
		<?php
		foreach ( $this->get_sliders() as $slider_name => $slider ) :
			$control_uid = $this->get_control_uid( $slider_name );
			?>
			<div class="elementor-control-field elementor-control-type-slider">
				<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title-transforms"><?php echo $slider['label']; ?></label>
				<div class="elementor-control-input-wrapper">
					<div class="elementor-slider" data-input="<?php echo esc_attr( $slider_name ); ?>"></div>
					<div class="elementor-slider-input">
						<input id="<?php echo esc_attr( $control_uid ); ?>" type="number" min="<?php echo esc_attr( $slider['min'] ); ?>" max="<?php echo esc_attr( $slider['max'] ); ?>" step="<?php echo esc_attr( $slider['step'] ); ?>" data-setting="<?php echo esc_attr( $slider_name ); ?>"/>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php
	}
}