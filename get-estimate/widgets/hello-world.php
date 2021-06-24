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
class Hello_World extends Widget_Base {

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
		return 'hello-world';
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
		return __( 'Get Estimate', 'elementor-hello-world' );
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
			'section_cf7',
			[
				'label' => __( 'Contact Form', 'elementor-hello-world' ),
			]
		);
		$this->add_control(
			'cf7_shortcode',
			[
				'label' => __( 'Contact Form Shortcode', 'elementor-hello-world' ),
				'type' => Controls_Manager::TEXT,
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'elementor-hello-world' ),
			]
		);

		$this->add_control(
			'step1_title',
			[
				'label' => __( 'Step1 Title', 'elementor-hello-world' ),
				'type' => Controls_Manager::TEXT,
			]
		);
		$this->add_control(
			'step2_title',
			[
				'label' => __( 'Step2 Title', 'elementor-hello-world' ),
				'type' => Controls_Manager::TEXT,
			]
		);
		$this->add_control(
			'step3_title',
			[
				'label' => __( 'Step3 Title', 'elementor-hello-world' ),
				'type' => Controls_Manager::TEXT,
			]
		);$this->add_control(
			'step4_title',
			[
				'label' => __( 'Step4 Title', 'elementor-hello-world' ),
				'type' => Controls_Manager::TEXT,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'elementor-hello-world' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'size1',
			[
				'label' => __( 'Size1', 'elementor-hello-world' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				]
			]
		);
		$this->add_control(
			'margin1',
			[
				'label' => __( 'Margin1', 'elementor-hello-world' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				]
			]
		);
		$this->add_control(
			'hover1_background_color',
			[
				'label' => __( 'Hover1 Background Color', 'elementor-hello-world' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				]
			]
		);
		$this->add_control(
			'step1_background_color',
			[
				'label' => __( 'Step1 Background Color', 'elementor-hello-world' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				]
			]
		);
		$this->add_control(
			'title1_color',
			[
				'label' => __( 'Title1 Text Color', 'elementor-hello-world' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				]
			]
		);
		$this->add_control(
			'size2',
			[
				'label' => __( 'Size2', 'elementor-hello-world' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				]
			]
		);
		$this->add_control(
			'margin2',
			[
				'label' => __( 'Margin2', 'elementor-hello-world' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				]
			]
		);
		$this->add_control(
			'hover2_background_color',
			[
				'label' => __( 'Step2 Background Color', 'elementor-hello-world' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				]
			]
		);
		$this->add_control(
			'step2_background_color',
			[
				'label' => __( 'Step2 Background Color', 'elementor-hello-world' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				]
			]
		);
		$this->add_control(
			'title2_color',
			[
				'label' => __( 'Title2 Text Color', 'elementor-hello-world' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				]
			]
		);
		$this->add_control(
			'size3',
			[
				'label' => __( 'Size3', 'elementor-hello-world' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				]
			]
		);
		$this->add_control(
			'margin3',
			[
				'label' => __( 'Margin3', 'elementor-hello-world' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				]
			]
		);
		$this->add_control(
			'hover3_background_color',
			[
				'label' => __( 'Hover3 Background Color', 'elementor-hello-world' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				]
			]
		);
		$this->add_control(
			'step3_background_color',
			[
				'label' => __( 'Step3 Background Color', 'elementor-hello-world' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				]
			]
		);
		$this->add_control(
			'title3_color',
			[
				'label' => __( 'Title3 Text Color', 'elementor-hello-world' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				]
			]
		);
		$this->add_control(
			'step4_background_color',
			[
				'label' => __( 'Step4 Background Color', 'elementor-hello-world' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				]
			]
		);
		$this->add_control(
			'title4_color',
			[
				'label' => __( 'Title4 Text Color', 'elementor-hello-world' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
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
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
			$url = "https://";   
		   else  
			$url = "http://";   
		   // Append the host(domain name, ip) to the URL.   
		   $url.= $_SERVER['HTTP_HOST'];
		   $url.= $_SERVER['REQUEST_URI'];
		
?>
	<style>

#est_input {
	text-align: center;
}
#est_input > div > a {
	display: inline-block;
	box-sizing: border-box;
	padding: 10px;
}
#est_input > div#est_step1_frontend > a:hover {
	background-color: <?php echo $settings['hover1_background_color'] ?>
}
#est_input > div#est_step2_frontend > a:hover {
	background-color: <?php echo $settings['hover2_background_color'] ?>
}
#est_input > div#est_step3_frontend > a:hover {
	background-color: <?php echo $settings['hover3_background_color'] ?>
}
#est_input > div > a > div {
	width: 100%;
}

#est_input > div  a.est_prev {
	display: block;
	text-align: left;
}


#est_input > div > a > div > span {
	width: 100%;
	display: block;
}
#est_input > div > a > div > img {
	width: 100%;
	display: block;
}
	</style>
	<div id="est_input" >
		<div id="est_step1_frontend" style="background-color: <?php if(isset($settings["step1_background_color"])) echo $settings["step1_background_color"] ?>;">
<?php
	$title1color = "#000";
	if (isset($settings["title1_color"])) $title1color = $settings["title1_color"];
	if (isset($settings["step1_title"])) echo "<h1 style='color: " . $title1color . "'>" . $settings["step1_title"] . "</h1>";
    foreach (json_decode(get_option("est_step1"), true) as $key => $obj) {
    
?>
        <a href="#" onclick="est_next('<?php echo $key ?>')" style="margin: <?php echo $settings["margin1"]['size'].$settings["margin1"]['unit'] ?>; width: <?php echo $settings["size1"]['size'].$settings["size1"]['unit']; ?>">
            <div>
                <img src="<?php echo  $obj["image"] ?>">
                <span><?php echo $obj["description"]; ?></span>
            </div>
        </a>
<?php
    }
?>
    </div>
    <div id="est_step2_frontend" style="background-color: <?php if(isset($settings["step2_background_color"])) echo $settings["step2_background_color"]?>; display: none;">
        <div><a href="#" class="est_prev" onclick="est_prev()"><i class="fas fa-arrow-left"></i></a></div>
<?php
	$title2color = "#000";
	if (isset($settings["title2_color"])) $title2color = $settings["title2_color"];
	if (isset($settings["step2_title"])) echo "<h1 style='color: " . $title2color . "'>" . $settings["step2_title"] . "</h1>";
    foreach (json_decode(get_option("est_step2"), true) as $key => $obj) {
?>
        <a href="#" onclick="est_next('<?php echo $key ?>')" style="margin: <?php echo $settings["margin2"]['size'].$settings["margin2"]['unit'] ?>; width: <?php echo $settings["size2"]['size'].$settings["size2"]['unit']; ?>">
            <div>
<?php
        if (strlen($obj["image"])> 0) {
?>
                <img src="<?php echo $obj["image"] ?>">
<?php
        }
?>
				<span><?php echo $obj["category"] ?></span>
            </div>
        </a>
<?php
    }
?>
    </div>
    <div id="est_step3_frontend"  style="background-color: <?php if(isset($settings["step3_background_color"])) echo $settings["step3_background_color"]?>; display: none;">
        <div><a href="#" class="est_prev" onclick="est_prev()"><i class="fas fa-arrow-left"></i></a></div>
<?php
	$title3color = "#000";
	if (isset($settings["title3_color"])) $title3color = $settings["title3_color"];
	if (isset($settings["step3_title"])) echo "<h1 style='color: " . $title3color . "'>" . $settings["step3_title"] . "</h1>";
    foreach (json_decode(get_option("est_step3"), true) as $key => $obj) {
?>
        <a href="#" onclick="est_next('<?php echo $key ?>')" style="margin: <?php echo $settings["margin3"]['size'].$settings["margin3"]['unit'] ?>; width: <?php echo $settings["size3"]['size'].$settings["size3"]['unit']; ?>">
            <div>
                <img src="<?php echo $obj["image"] ?>">
                <span><?php echo $obj["description"] ?></span>
            </div>
        </a>
<?php
    }
?>
    </div>
    <div id="est_step4_frontend"  style="background-color: <?php if(isset($settings["step4_background_color"])) echo $settings["step4_background_color"]?>; display: none;">
        <a href="#" class="est_prev" onclick="est_prev()"><i class="fas fa-arrow-left"></i></a>
<?php
		$title4color = "#000";
	if (isset($settings["title4_color"])) $title3color = $settings["title4_color"];
	if (isset($settings["step4_title"])) echo "<h1 style='color: " . $title4color . "'>" . $settings["step4_title"] . "</h1>";

 if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
 $url = "https://";   
else  
 $url = "http://";   
// Append the host(domain name, ip) to the URL.   
$url.= $_SERVER['HTTP_HOST'];   

// Append the requested resource location to the URL   
$url.= $_SERVER['REQUEST_URI'];
?>
            <div id="est_dynamic_holder">
				<?php echo do_shortcode($settings["cf7_shortcode"]); ?>
            </div><br>
    	</div>
	</div>
    <script>
        document.addEventListener( 'wpcf7mailsent', function( event ) {
			location = '<?php get_option("est_estimate_shortcode_link") ?>';
		}, false );
        var step = 1;
        var data ={1: "", 2: "", 3: ""};
        function est_prev() {
            jQuery("#est_step" + step + "_frontend").css('display', 'none');
            step--;
            jQuery("#est_step" + step + "_frontend").css('display', 'block');
        }
        function est_next(id) {
            data[step] = id;
            jQuery("#est_step" + step + "_frontend").css('display', 'none');
            step++;
            jQuery("#est_step" + step + "_frontend").css('display', 'block');
			if (step == 4) {
				est_submission();
			}
        }
        function est_submission() {
            
            var obj = {
                action: 'est_set_data',
                data: JSON.stringify(data)
            };
			<?php
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
			$url = "https://";   
		   else  
			$url = "http://";   
		   // Append the host(domain name, ip) to the URL.   
		   $url.= $_SERVER['HTTP_HOST'];
		   $url.= $_SERVER['REQUEST_URI'];
		   
		   $url = explode("/", $url);
		   array_pop($url);
		   array_pop($url);
		   $url = implode("/", $url);
			?>
            jQuery.post('<?php echo $url ?>/wp-admin/admin-ajax.php', obj, function(response) {    
                console.log(response);
            });
        }
    </script>
<?php
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
