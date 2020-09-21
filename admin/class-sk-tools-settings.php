<?php
/**
 * The admin settings functionality of the plugin.
 *
 * @link       angel@curiousexplorations.com/about
 * @since      1.1.0
 *
 * @package    Sk_Tools
 * @subpackage Sk_Tools/public
 */

/**
 * The admin settings functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sk_Tools
 * @subpackage Sk_Tools/public
 * @author     Angel Knight <angel@curiousexplorations.com>
 */

class SK_Settings {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Admin options for this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      array    $admin_options    Array of general admin options.
	 */
	private $admin_options;

	/**
	 * Breadcrumb (shortcode) options for this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      array    $breadcrumb_options    Array of 'breadcrumb' shortcode options.
	 */
	private $breadcrumb_options;

	/**
	 * Notice (shortcode) options for this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      array    $notice_options    Array of 'notice' shortcode options.
	 */
	private $notice_options;

	/**
	 * Date-Time (shortcode) options for this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      array    $datetime_options    Array of 'datetime' shortcode options.
	 */
	private $datetime_options;

	/*
	Default Options
	 */
	private $default_admin_options = array(
		'clear_options_on_deactivation' => 'false',
		'enable_addressbook'			=> 'true',
		'enable_breadcrumbs'			=> 'true',
		'enable_notices'					=> 'true',
		'enable_filter'						=> 'true',
		'enable_datetime'					=> 'true',
		'enable_colorpalettes'		=> 'true',	
	);

	/**
	 * Breadcrumb Options
	 * show_home: (string) true, false, icon; show 
	 * 		true = text only, icon = icon + text
	 * home_icon_only: (bool) show 'home' link as an icon
	 * 		if true, overrides show_home but show only icon
	 * show_current: (string) true, false, url; show title of current page in breadcrumbs
	 * 		if url, current title will be a URL (to the current page)
	 */
	private $default_breadcrumb_options = array(
		// true, false, icon
		// true = text only, icon = icon + text
		'show_home'				=> 'true',
		'home_icon_only'	=> 'true',
		'show_current'		=> 'true'
	);

	/**
	 * Notice Options
	 * [Notice is an enclosing shortcode]
	 * 	default_date_format: (string); use PHP date formatting
	 * 	default_message_type: (string)
	 * 		simple, info, success, alert, hi-alert, warn, default
	 * 	default_weekdays: (array); A.K.A "business days"
	 * 		can be used to display a message on "weekends" or "weekdays"
	 */
	private $default_notice_options = array(
		'default_date_format'		=> 'l, F j, Y',
		'default_message_type' 	=> 'default', 
		'default_weekdays'			=> array('mon','tue','wed','thu','fri')
	);

	/**
	 * DateTime Options
	 * [date & time formats are separate for ease of use]
	 * default_date_format: (string); use PHP date formatting
	 * 		EX// l, F j, Y => Monday, November 18, 2019
	 * default_time_format: (string); use PHP time formatting
	 * 		Note: g/G no leading 0, h/H leading 0 (12/24)
	 */
	private $default_datetime_options = array(
		'default_date_format' => 'l, F j, Y',
		'default_time_format' => 'h:i A'
	);

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct() {
		add_action( 'admin_init', array($this, 'page_init') );
	}

	/**
   * Options page callback
   */
  public function create_page() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sk-tools-admin.css', array(), $this->version, 'all' );

    // Set class property
    $this->admin_options = get_option( 'sk_admin_options', $this->default_admin_options ); // my_option_name

    $this->breadcrumb_options = get_option( 'sk_breadcrumb_options', $this->default_breadcrumb_options );
    $this->notice_options = get_option( 'sk_notice_options', $this->default_notice_options );
    $this->datetime_options = get_option( 'sk_datetime_options', $this->default_datetime_options );

		/**
     * Tabs built based on Tom McFarlin's article @ code.tutsplus.com
     * https://code.tutsplus.com/tutorials/the-wordpress-settings-api-part-5-tabbed-navigation-for-settings--wp-24971
     */
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'admin_options';

		?>
    <div class="wrap">
      <h1>SiriusAccess Settings</h1>
      <div class="nav-tab-wrapper">
        <h2 class="nav-tab <?php if($active_tab === 'admin_options') { echo 'active-tab'; } ?>"><a href="?page=sk_settings&tab=admin_options">Admin Options</a></h2>
        <h2 class="nav-tab <?php if($active_tab === 'breadcrumb_options') { echo 'active-tab'; } ?>"><a href="?page=sk_settings&tab=breadcrumb_options">Breadcrumb Options</a></h2>
        <h2 class="nav-tab <?php if($active_tab === 'notice_options') { echo 'active-tab'; } ?>"><a href="?page=sk_settings&tab=notice_options">Notice Options</a></h2>
        <h2 class="nav-tab <?php if($active_tab === 'datetime_options') { echo 'active-tab'; } ?>"><a href="?page=sk_settings&tab=datetime_options">Date-Time Display Options</a></h2>
      </div>
      <div class="tab-content">
	      <form method="post" action="options.php" class="<?php echo $active_tab; ?>">
	        <input type="hidden" name="sk_options_nonce" value="<?php echo wp_create_nonce() ?>" />
	      <?php

	        if( $active_tab === 'admin_options' ) {
	          // This prints out all hidden setting fields
	          settings_fields( 'sk_admin_option_group' ); // my_option_group
	          do_settings_sections( 'sk-admin-settings' ); // my-setting-admin
	          submit_button();

	        } elseif( $active_tab === 'breadcrumb_options' ) {
	          settings_fields( 'sk_breadcrumb_option_group' ); // my_option_group
	          do_settings_sections( 'sk-breadcrumb-settings' ); // my-setting-admin
	          submit_button();

	        } elseif( $active_tab === 'notice_options' ) {
						settings_fields( 'sk_notice_option_group' ); // my_option_group
	          do_settings_sections( 'sk-notice-settings' ); // my-setting-admin
	          submit_button();

	        } elseif( $active_tab === 'datetime_options' ) {
						settings_fields( 'sk_datetime_option_group' ); // my_option_group
	          do_settings_sections( 'sk-datetime-settings' ); // my-setting-admin
	          submit_button();

	        }

	      ?>
	      </form>
	    </div>
    </div>
    <?php
  }

  public function page_init() {
  	$this->admin_settings();
  	$this->breadcrumb_settings();
  	$this->notice_settings();
  	$this->datetime_settings();
  }

	/**
	 * REGISTER SETTING SECTIONS & FIELDS
	 */
  private function admin_settings() {
  	$admin_args = array(
			'sanitize_callback' => array($this, 'sanitize_admin_settings'),
			'default' => $this->default_admin_options
  	);

  	register_setting(
			'sk_admin_option_group', // option group
			'sk_admin_options', // option name
			$admin_args,
			array($this, 'sanitize') // sanitize callback
  	);

  	/**
  	 * ADMIN SETTINGS SECTION
  	 */
  	add_settings_section(
			'sk_settings_admin_section', // ID
			'Admin Settings', // Title
			array( $this, 'print_admin_section_info' ), // Callback
			'sk-admin-settings' // Page
  	);

  	add_settings_field(
			'clear_options_on_deactivation', // ID
			'Clear options on deactivation', // title
			array( $this, 'clear_options_on_deactivation_callback' ), // callback
			'sk-admin-settings', // page
			'sk_settings_admin_section' // section
  	);
  	add_settings_field(
			'enable_addressbook', // ID
			'Enable AddressBook Shortcode', // title
			array( $this, 'enable_addressbook_callback' ), // callback
			'sk-admin-settings', // page
			'sk_settings_admin_section' // section
  	);
  	add_settings_field(
			'enable_breadcrumbs', // ID
			'Enable Breadcrumbs Shortcode', // title
			array( $this, 'enable_breadcrumbs_callback' ), // callback
			'sk-admin-settings', // page
			'sk_settings_admin_section' // section
  	);
  	add_settings_field(
			'enable_notices', // ID
			'Enable Notice Shortcode', // title
			array( $this, 'enable_notices_callback' ), // callback
			'sk-admin-settings', // page
			'sk_settings_admin_section' // section
  	);
  	add_settings_field(
			'enable_filter', // ID
			'Enable Filter Shortcode', // title
			array( $this, 'enable_filter_callback' ), // callback
			'sk-admin-settings', // page
			'sk_settings_admin_section' // section
  	);
  	add_settings_field(
			'enable_datetime', // ID
			'Enable Date-Time Shortcode', // title
			array( $this, 'enable_datetime_callback' ), // callback
			'sk-admin-settings', // page
			'sk_settings_admin_section' // section
  	);
  	add_settings_field(
			'enable_colorpalettes', // ID
			'Enable Color-Palettes Shortcode', // title
			array( $this, 'enable_colorpalettes_callback' ), // callback
			'sk-admin-settings', // page
			'sk_settings_admin_section' // section
  	);
  }

	private function breadcrumb_settings() {
		$breadcrumb_args = array(
			'sanitize_callback' => array($this, 'sanitize_breadcrumb_settings'),
			'default' => $this->default_breadcrumb_options
  	);

  	register_setting(
			'sk_breadcrumb_option_group', // option group
			'sk_breadcrumb_options', // option name
			$breadcrumb_args,
			array($this, 'sanitize') // sanitize callback
  	);

		/**
  	 * BREADCRUMB SETTINGS SECTION
  	 */
  	add_settings_section(
			'sk_settings_breadcrumb_section', // ID
			'Breadcrumb Settings', // Title
			array( $this, 'print_breadcrumb_section_info' ), // Callback
			'sk-breadcrumb-settings' // Page
  	);

  	add_settings_field(
			'show_home', // ID
			"Show 'home' link", // title
			array( $this, 'show_home_callback' ), // callback
			'sk-breadcrumb-settings', // page
			'sk_settings_breadcrumb_section' // section
  	);

  	add_settings_field(
			'home_icon_only', // ID
			"Show 'home' link as ONLY an icon", // title
			array( $this, 'home_icon_only_callback' ), // callback
			'sk-breadcrumb-settings', // page
			'sk_settings_breadcrumb_section' // section
  	);

  	add_settings_field(
			'show_current', // ID
			"Show current page title <br><small>(select 'url' to make page title a link)</small>", // title
			array( $this, 'show_current_callback' ), // callback
			'sk-breadcrumb-settings', // page
			'sk_settings_breadcrumb_section' // section
  	);
	}

	private function notice_settings() {
		$notice_args = array(
			'sanitize_callback' => array($this, 'sanitize_notice_settings'),
			'default' => $this->default_notice_options
  	);

  	register_setting(
			'sk_notice_option_group', // option group
			'sk_notice_options', // option name
			$notice_args
			// array($this, 'sanitize') // sanitize callback
  	);

  	/**
  	 * NOTICE SETTINGS SECTION
  	 */
  	add_settings_section(
			'sk_settings_notice_section', // ID
			'Notice Settings', // title
			array( $this, 'print_notice_section_info' ), // callback
			'sk-notice-settings' // page
  	);

  	add_settings_field(
			'default_notice_date_format', // ID
			'Default Date Format', // title
			array( $this, 'default_notice_date_format_callback' ), // callback
			'sk-notice-settings', // page
			'sk_settings_notice_section' // section
  	);

  	add_settings_field(
			'default_notice_message_type', // ID
			'Default Message Type', // title
			array( $this, 'default_notice_message_type_callback' ), // callback
			'sk-notice-settings', // page
			'sk_settings_notice_section' // section
  	);

  	add_settings_field(
			'default_notice_weekdays', // ID
			'Default Weekdays', // title
			array( $this, 'default_notice_weekdays_callback' ), // callback
			'sk-notice-settings', // page
			'sk_settings_notice_section' // section
  	);
	}

	private function datetime_settings() {
		$datetime_args = array(
			'sanitize_callback' => array($this, 'sanitize_datetime_settings'),
			'default' => $this->default_datetime_options
  	);

  	register_setting(
			'sk_datetime_option_group', // option group
			'sk_datetime_options', // option name
			$datetime_args,
			array($this, 'sanitize') // sanitize callback
  	);

  	/**
  	 * DATETIME SETTINGS SECTION
  	 */
  	add_settings_section(
			'sk_settings_datetime_section', // ID
			'Date-Time Settings', // title
			array( $this, 'print_datetime_section_info' ), // callback
			'sk-datetime-settings' // page
  	);

  	add_settings_field(
			'default_datetime_date_format', // ID
			'Default Date Format', // title
			array( $this, 'default_datetime_date_format_callback' ), // callback
			'sk-datetime-settings', // page
			'sk_settings_datetime_section' // section
  	);

  	add_settings_field(
			'default_datetime_time_format', // ID
			'Default Date Format', // title
			array( $this, 'default_datetime_time_format_callback' ), // callback
			'sk-datetime-settings', // page
			'sk_settings_datetime_section' // section
  	);
	}

	/**
	 * SANITIZE FIELDS
	 *
	 * @param  array $input Contains all settings fields as array keys
	 */
	public function sanitize_admin_settings( $input ) {
		return $input;
	}

	public function sanitize_breadcrumb_settings( $input ) {
		return $input;
	}

	public function sanitize_notice_settings( $input ) {
		return $input;
	}

	public function sanitize_datetime_settings( $input ) {
		return $input;
	}

	/**
	 * PRINT SECTION INFO
	 */
	public function print_admin_section_info() {
		print 'General admin settings for SK-Tools shortcodes.';
		// $this->list_admin_options();
	}

	public function print_breadcrumb_section_info() {
		print 'Set the defaults for the sk_breadcrumbs shortcode. Setting the show home as icon ONLY option to true overrides the show home link option.';
		// $this->list_breadcrumb_options();
	}

	public function print_notice_section_info() {
		print 'Set the defaults for the sk_notice shortcode. Use PHP formatting for the date format (can include time).';
		// $this->list_notice_options();
	}

	public function print_datetime_section_info() {
		print 'Set the default date and time format for the sk_datetime shortcode. Use PHP formatting.';
		// $this->list_datetime_options();
	}

	/**
	 * ADMIN SETTINGS
	 */
	public function clear_options_on_deactivation_callback() {
		printf(
			'<input type="hidden" name="sk_admin_options[clear_options_on_deactivation]" value="false">'.
			'<input class="sk-checkbox" type="checkbox" name="sk_admin_options[clear_options_on_deactivation]" value="true" %1$s>',
			checked( get_option("sk_admin_options")["clear_options_on_deactivation"], "true", false )
		);
	}
	public function enable_addressbook_callback() {
		printf(
			'<input type="hidden" name="sk_admin_options[enable_addressbook]" value="false">'.
			'<input class="sk-checkbox" type="checkbox" name="sk_admin_options[enable_addressbook]" value="true" %1$s>',
			checked( get_option("sk_admin_options")["enable_addressbook"], "true", false )
		);
	}
	public function enable_breadcrumbs_callback() {
		printf(
			'<input type="hidden" name="sk_admin_options[enable_breadcrumbs]" value="false">'.
			'<input class="sk-checkbox" type="checkbox" name="sk_admin_options[enable_breadcrumbs]" value="true" %1$s>',
			checked( get_option("sk_admin_options")["enable_breadcrumbs"], "true", false )
		);
	}
	public function enable_notices_callback() {
		printf(
			'<input type="hidden" name="sk_admin_options[enable_notices]" value="false">'.
			'<input class="sk-checkbox" type="checkbox" name="sk_admin_options[enable_notices]" value="true" %1$s>',
			checked( get_option("sk_admin_options")["enable_notices"], "true", false )
		);
	}
	public function enable_datetime_callback() {
		printf(
			'<input type="hidden" name="sk_admin_options[enable_datetime]" value="false">'.
			'<input class="sk-checkbox" type="checkbox" name="sk_admin_options[enable_datetime]" value="true" %1$s>',
			checked( get_option("sk_admin_options")["enable_datetime"], "true", false )
		);
	}
	public function enable_filter_callback() {
		printf(
			'<input type="hidden" name="sk_admin_options[enable_filter]" value="false">'.
			'<input class="sk-checkbox" type="checkbox" name="sk_admin_options[enable_filter]" value="true" %1$s>',
			checked( get_option("sk_admin_options")["enable_filter"], "true", false )
		);
	}
	public function enable_colorpalettes_callback() {
		printf(
			'<input type="hidden" name="sk_admin_options[enable_colorpalettes]" value="false">'.
			'<input class="sk-checkbox" type="checkbox" name="sk_admin_options[enable_colorpalettes]" value="true" %1$s>',
			checked( get_option("sk_admin_options")["enable_colorpalettes"], "true", false )
		);
	}

	/**
	 * BREADCRUMBS SETTINGS
	 */
	public function show_home_callback() {
		printf(
			'<select class="sk-select" name="sk_breadcrumb_options[show_home]" %1$s>'.
			'<option value="%1$s" %2$s>%3$s</option>'.
			'<option value="%4$s" %5$s>%6$s</option>'.
			'<option value="%7$s" %8$s>%9$s</option>'.
			'</select>',
			'true', selected( get_option('sk_breadcrumb_options')['show_home'], 'true', false ), 'True',
			'false', selected( get_option('sk_breadcrumb_options')['show_home'], 'false', false ), 'False',
			'icon', selected( get_option('sk_breadcrumb_options')['show_home'], 'icon', false ), 'With Icon'
		);
	}
	public function home_icon_only_callback() {
		printf(
			'<input type="hidden" name="sk_breadcrumb_options[home_icon_only]" value="false">'.
			'<input class="sk-checkbox" type="checkbox" name="sk_admin_options[home_icon_only]" value="true" %1$s>',
			checked( get_option("sk_breadcrumb_options")["home_icon_only"], "true", false )
		);
	}
	public function show_current_callback() {
		printf(
			'<select class="sk-select" name="sk_breadcrumb_options[show_current]" %1$s>'.
			'<option value="%1$s" %2$s>%3$s</option>'.
			'<option value="%4$s" %5$s>%6$s</option>'.
			'<option value="%7$s" %8$s>%9$s</option>'.
			'</select>',
			'true', selected( get_option('sk_breadcrumb_options')['show_current'], 'true', false ), 'True',
			'false', selected( get_option('sk_breadcrumb_options')['show_current'], 'false', false ), 'False',
			'url', selected( get_option('sk_breadcrumb_options')['show_current'], 'url', false ), 'As URL'
		);
	}

	/**
	 * NOTICE SETTINGS
	 */
	public function default_notice_date_format_callback() {
		printf(
			'<input type="text" name="sk_notice_options[default_date_format]" value="%1$s">',
			get_option('sk_notice_options')['default_date_format']
		);
	}
	public function default_notice_message_type_callback() {
		// simple, info, success, alert, hi-alert, warn, default
		printf(
			'<select class="sk-select" name="sk_notice_options[default_message_type]" %1$s>'.
			'<option value="%1$s" %2$s>%3$s</option>'.
			'<option value="%4$s" %5$s>%6$s</option>'.
			'<option value="%7$s" %8$s>%9$s</option>'.
			'<option value="%10$s" %11$s>%12$s</option>'.
			'<option value="%13$s" %14$s>%15$s</option>'.
			'<option value="%16$s" %17$s>%18$s</option>'.
			'<option value="%19$s" %20$s>%21$s</option>'.
			'</select>',
			'simple', selected( get_option('sk_notice_options')['default_message_type'], 'simple', false ), 'Simple',
			'default', selected( get_option('sk_notice_options')['default_message_type'], 'default', false ), 'Default',
			'info', selected( get_option('sk_notice_options')['default_message_type'], 'info', false ), 'Info',
			'success', selected( get_option('sk_notice_options')['default_message_type'], 'success', false ), 'Success',
			'alert', selected( get_option('sk_notice_options')['default_message_type'], 'alert', false ), 'Alert',
			'hi-alert', selected( get_option('sk_notice_options')['default_message_type'], 'hi-alert', false ), 'Hi-Alert',
			'warn', selected( get_option('sk_notice_options')['default_message_type'], 'warn', false ), 'Warn'
		);
	}
	public function default_notice_weekdays_callback() {
		$selected_days = get_option("sk_notice_options")["default_weekdays"];
		// var_dump($selected_days);

		printf(
			'<section class="sk-weekdays">'.
			'<input class="sk-checkbox" type="checkbox" name="sk_notice_options[default_weekdays][]" value="mon" %1$s>Monday'.
			'<input class="sk-checkbox" type="checkbox" name="sk_notice_options[default_weekdays][]" value="tue" %2$s>Tuesday'.
			'<input class="sk-checkbox" type="checkbox" name="sk_notice_options[default_weekdays][]" value="wed" %3$s>Wednesday'.
			'<input class="sk-checkbox" type="checkbox" name="sk_notice_options[default_weekdays][]" value="thu" %4$s>Thursday'.
			'<input class="sk-checkbox" type="checkbox" name="sk_notice_options[default_weekdays][]" value="fri" %5$s>Friday'.
			'<input class="sk-checkbox" type="checkbox" name="sk_notice_options[default_weekdays][]" value="sat" %6$s>Saturday'.
			'<input class="sk-checkbox" type="checkbox" name="sk_notice_options[default_weekdays][]" value="sun" %7$s>Sunday'.
			'</section>',
			checked( in_array("mon", $selected_days), true, false ),
			checked( in_array("tue", $selected_days), true, false ),
			checked( in_array("wed", $selected_days), true, false ),
			checked( in_array("thu", $selected_days), true, false ),
			checked( in_array("fri", $selected_days), true, false ),
			checked( in_array("sat", $selected_days), true, false ),
			checked( in_array("sun", $selected_days), true, false )
		);
	}

	/**
	 * DATE-TIME SETTINGS
	 */
	public function default_datetime_date_format_callback() {
		printf(
			'<input type="text" name="sk_datetime_options[default_date_format]" value="%1$s">',
			get_option('sk_datetime_options')['default_date_format']
		);
	}

	public function default_datetime_time_format_callback() {
		printf(
			'<input type="text" name="sk_datetime_options[default_time_format]" value="%1$s">',
			get_option('sk_datetime_options')['default_time_format']
		);
	}


	/**
	 * LIST OPTIONS, for debugging
	 */
	public function list_admin_options() {
    $options = get_option('sk_admin_options');

    echo "<h1>Admin Options</h1>";
    echo "<pre>";
    var_dump($options);
    echo "</pre>";
  }

  public function list_breadcrumb_options() {
    $options = get_option('sk_breadcrumb_options');

    echo "<h1>Breadcrumb Options</h1>";
    echo "<pre>";
    var_dump($options);
    echo "</pre>";
  }

  public function list_notice_options() {
    $options = get_option('sk_notice_options');

    echo "<h1>Notice Options</h1>";
    echo "<pre>";
    var_dump($options);
    echo "</pre>";
  }

  public function list_datetime_options() {
    $options = get_option('sk_datetime_options');

    echo "<h1>Date-Time Options</h1>";
    echo "<pre>";
    var_dump($options);
    echo "</pre>";
  }

}

if( is_admin() )
  $sk_settings_page = new SK_Settings();