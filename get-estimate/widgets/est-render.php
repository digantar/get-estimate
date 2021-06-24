<?php
namespace ElementorHelloWorld\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class ESTRender extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'est-render';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Render Output', 'est-render' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'elementor-hello-world' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'Settings', 'est-render' ),
			]
		);
		$this->add_control(
			'type',
			[
				'label' => __( 'Type', 'est-render' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'price',
				'options' => [
					'price'  => __( 'Price', 'est-render' ),
					'perMonth' => __( 'Per Month', 'est-render' ),
					'extra' => __( 'Extra', 'est-render' ),
                    'productImage' => __('Product Image', 'est-render')
				],
			]
		);
        $this->add_control(
			'selection',
			[
				'label' => __( 'Selection', 'est-render' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => __( '1', 'est-render' ),
					'2' => __( '2', 'est-render' ),
					'3' => __( '3', 'est-render' )
				],
			]
		);

		$this->end_controls_section();
        $this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'est-render' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'est-render' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				]
			]
		);
        $this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'est-render' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				]
			]
		);
        $this->add_control(
			'size',
			[
				'label' => __( 'Size', 'est-render' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
				],
				'default' => [
					'unit' => 'px',
					'size' => 20
				]
			]
		);
        $this->end_controls_section();
		
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
        $sel = intval($settings["selection"])-1;
        $type = $settings["type"];
        $output = "";
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
            $url = "https://";   
        else  
            $url = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url.= $_SERVER['HTTP_HOST'];
        $url.= $_SERVER['REQUEST_URI'];
        
        $id = (isset($_COOKIE['est_data'])) ? json_decode(stripslashes($_COOKIE['est_data']), true)["2"] : $_GET['cid'];
        $options_structure = json_decode(get_option('est_step2'), true);
        if ($type == "productImage") {
            $output = "<img style='display: block; width: " . $settings["size"]["size"].$settings["size"]["unit"] . "' src='" . $options_structure[$id][$type][$sel] . "'>";
        } else {
            $output = $options_structure[$id][$type][$sel];
        }
        echo "<span style='background-color: " . $settings["background_color"] . "; color: " . $settings["text_color"] . ";font-size: " . $settings["size"]["size"].$settings["size"]["unit"] . ";'>" . $output . "</span>";
	}
	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		
		<?php
	}
}
