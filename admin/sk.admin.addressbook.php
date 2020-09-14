<?php 
if( !defined( 'ABSPATH' ) ) { exit; }

class SKTools_AddressBook {
  /**
   * Add / Delete buttons for SW Options
   */
  private $addRowBtn    = '<i class="sk-contact--add fas fa-plus-square fa-lg"></i>';
  private $deleteRowBtn = '<i class="sk-contact--delete fas fa-window-close fa-lg"></i>';

  /**
   * Holds the values to be used in the fields callbacks
   */
  private $addressbook;

  /**
   * Start up
   */
  public function __construct() {
    // add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    add_action( 'admin_init', array( $this, 'page_init' ) );
  }

  /**
   * Options page callback
   */
  public function create_page() {
    // Set class property
    $this->addressbook = get_option( 'sk_addressbook', array() ); // my_option_name

    wp_enqueue_style('sk-admin-styles');
    ?>
    <form id="sk_addressbook_form" method="post" action="options.php">
        <input type="hidden" name="sk_addressbook_nonce" value="<?php echo wp_create_nonce(); ?>" />
      <?php
        settings_fields( 'sk_addressbook_group' ); // my_option_group
        do_settings_sections( 'sk-addressbook' ); // my-setting-admin
        submit_button();
      ?>
    </form>
    <?php
  }

  /**
   * Register and add settings
   */
  public function page_init() {
    $this->addressbook();
  }

  private function addressbook() {
    $admin_args = array(
      'sanitize_callback' => array($this, 'sanitize_addressbook'),
      'default' => array()
    );

    register_setting(
      'sk_addressbook_group', // Option group [ settings_fields() name ]
      'sk_addressbook', // Option name
      $admin_args
      //array( $this, 'sanitize' ), // Sanitize callback
    );

    /**
     * ADMIN SETTINGS SECTION
     */
    add_settings_section(
      'sk_addressbook_section', // ID
      'SK AddressBook', // Title
      array( $this, 'print_section_info' ), // Callback
      'sk-addressbook' // Page [ do_settings_section() name ]
    );

    add_settings_field(
      'sk_contact_info', // ID
      'Contacts', // Title 
      array( $this, 'sk_contact_info_callback' ), // Callback
      'sk-addressbook', // Page
      'sk_addressbook_section' // Section       
    );
  }

	/** 
   * Print the Section text
   */
  public function print_section_info() {
    // print 'Enter your settings below:';
    // $this->list_options();
  }

  /**
   * Sanitize each setting field as needed
   *
   * @param array $input Contains all settings fields as array keys
   */
  public function sanitize_addressbook( $input ) {
    $new_input = array();

    foreach($input as $new) {
      $contact['name']  = sanitize_text_field($new['name']);
      $contact['email'] = sanitize_email($new['email']);
      $contact['title'] = sanitize_text_field($new['title']);
      $contact['phone'] = sk_sanitize_phone_number($new['phone']);

      array_push($new_input, $contact);
    }

    // $new_input = $input;

    return $new_input;
  }

  /**
   * ADDRESSBOOK ADD CONTACT
   */
  public function sk_contact_info_callback() {
  	$contacts = is_array( get_option('sk_addressbook') ) ? get_option('sk_addressbook') : explode(",", get_option('sk_addressbook'));

  	if( !empty($contacts) && $contacts !== NULL ) {
      $index = 0;
  		foreach($contacts as $contact) {
        $name = array_key_exists('name', $contact) ? $contact['name'] : '';
        $email = array_key_exists('email', $contact) ? $contact['email'] : '';
        $title = array_key_exists('title', $contact) ? $contact['title'] : '';
        $phone = array_key_exists('phone', $contact) ? $contact['phone'] : '';

  			printf('<div id="index_%1$s" class="sk-contact-container">%2$s'.
					'Name: <input class="sk-input" type="text" name="sk_addressbook[%3$s][name]" value="%4$s">'.
					' Email: <input class="sk-input" type="text" name="sk_addressbook[%5$s][email]" value="%6$s">'.
					' Title: <input class="sk-input" type="text" name="sk_addressbook[%7$s][title]" value="%8$s">'.
          ' Phone: <input class="sk-input" type="text" name="sk_addressbook[%9$s][phone]" value="%10$s">'.
					'%11$s</div>',
					$index, // for container id
					$this->deleteRowBtn,
					$index, // for name input
          $name,
          $index, // for email input
          $email,
          $index, // for title input
          $title,
          $index, // for the phone input
          $phone,
					$this->addRowBtn
				);

        $index++;
  		}

  	} else {

      printf(
        '<div id="index_0" class="sk-contact-container">'.
        'Name: <input class="sk-input" type="text" name="sk_addressbook[0][name]" value="">'.
        ' Email: <input class="sk-input" type="text" name="sk_addressbook[0][email]" value="">'.
        ' Title: <input class="sk-input" type="text" name="sk_addressbook[0][title]" value="">'.
        ' Phone: <input class="sk-input" type="text" name="sk_addressbook[0][phone]" value="">'.
        '%1$s</div>'.
        '<div id="index_1" class="sk-contact-container">%2$s'.
        'Name: <input class="sk-input" type="text" name="sk_addressbook[1][name]" placeholder="Name">'.
        ' Email: <input class="sk-input" type="text" name="sk_addressbook[1][email]" placeholder="Email">'.
        ' Title: <input class="sk-input" type="text" name="sk_addressbook[1][title]" placeholder="Title">'.
        ' Phone: <input class="sk-input" type="text" name="sk_addressbook[1][phone]" placeholder="Phone">'.
        '%3$s</div>',
        $this->addRowBtn,
        $this->deleteRowBtn,
        $this->addRowBtn
      );
  	}
  }

  public function list_options() {
    $addressbook = get_option('sk_addressbook');
    $count = is_array($addressbook) ? count($addressbook) : 0;
    
    echo "<h1>AddressBook ($count)</h1><pre>";
    var_dump($addressbook);
    echo "</pre>";
  }
}

if( is_admin() )
  $my_settings_page = new SKTools_AddressBook();