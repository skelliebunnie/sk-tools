<?php

class SK_Functions {

  public function sk_pre($var) {
    echo "<br/><pre>";
    var_dump($var);
    echo "</pre><br/>";
  }

  public function get_current_url() {
    global $wp;
    return add_query_arg( $wp->query_vars, home_url( '/', $wp->request ) );
  }

  public function get_terms_by_taxonomy($taxonomy) {
    $list = $taxonomy; $results = array();

    // if $taxonomy is not already an array, make it one
    if( !is_array($taxonomy) )
      $list = explode(",", $taxonomy);

    if( $list[0] == "all" ) {
      $list = array();
      $all_taxonomies = get_taxonomies();

      foreach($all_taxonomies as $id=>$term) {
        array_push($list, $all_taxonomies[$id]);
      }
    }

    foreach($list as $section=>$term) {
      // if the strings have a colon, the section has not actually
      // been separated from the term - do so now.
      if( strpos($term, ":") !== false ) {
        $section_term = explode(":", $term);
        $section = $section_term[0];
        $term = $section_term[1];
      }

      $terms = get_terms(
        array(
          'taxonomy'    => $term,
          'hide_empty'  => false
        )
      );

      if( (is_array($terms) && !empty($terms)) || (is_object($terms) && !property_exists($terms, "errors")) ) {
        foreach($terms as $term) {
          $filter = (object)[
            'name'      => $term->name,
            'slug'      => $term->slug,
            'taxonomy'  => $term->taxonomy,
            'count'     => $term->count
          ];

          // if there was a section name (not just a [numeric] index),
          // make sure the results returned preserve that name
          // otherwise, just push everything into the $results array
          if( !is_numeric($section) && !array_key_exists($section, $results) )
            $results[$section] = array();

          if( !is_numeric($section) ) {
            array_push($results[$section], $filter);

          } else {
            if( !array_key_exists($filter->taxonomy, $results) )
              $results[$filter->taxonomy] = array();

            array_push($results[$filter->taxonomy], $filter);

          }       
        }
      }
    }

    return $results;
  }

  public function get_posts_by_type($post_type) {
    $results = array();

    if( !is_array($post_type) )
      $post_type = explode(",", $post_type);

    foreach($post_type as $section=>$type) {
      if( strpos($type, ":") !== 0 ) {
        $section_type = explode(":", $type);
        
        if( count($section_type) > 1 ) {
          $section = $section_type[0];
          $type = $section_type[1];
        } else {
          $section = $section;
          $type = $section_type[0];
        }
      }

      $args = array(
        'post_type'   => $type,
        'order'       => 'ASC',
        'post_status' => 'publish'
      );

      $the_query = new WP_Query( $args );

      if( $the_query->have_posts() ) {
        // return $the_query->posts;
        foreach( $the_query->posts as $post ) {
          $item = (object)[
            'title'     => $post->post_title,
            'guid'      => $post->guid,
            'post_type' => $post->post_type
          ];

          if( !is_numeric($section) && !array_key_exists($section, $results) )
            $results[$section] = array();

          if( !is_numeric($section) ) {
              array_push($results[$section], $item);

          } else {
            if( !array_key_exists($item->post_type, $results) )
              $results[$item->post_type] = array();

            array_push($results[$item->post_type], $item);

          }
        }
      }
    }

    return $results;
  }

  // From https://wordpress.stackexchange.com/a/10708
  // Added sk_ prefix as a precaution
  public function sk_get_page_by_slug($page_slug, $post_type = 'page', $output = OBJECT ) {
      global $wpdb;
      $page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $page_slug, $post_type ) );
      if ( $page )
        return get_page($page, $output);
      return null;
  }

  // https://stackoverflow.com/questions/12228644/how-to-detect-light-colors-with-php
  public function getColorLightness($hex) {
    $hex = strpos($hex, "#") === 0 ? str_replace("#", "", $hex) : $hex;
    
    $rgb = hexToRGB($hex, true);
    $r = $rgb['red']; $g = $rgb['green']; $b = $rgb['blue'];

    return (max($r, $g, $b) + min($r, $g, $b)) / 510.0; // HSL algorithm
  }

  public function getLuma($hex) {
    $hex = strpos($hex, "#") === 0 ? str_replace("#", "", $hex) : $hex;
    
    $rgb = hexToRGB($hex, true);

    return (0.2126 * $rgb['red'] + 0.7152 * $rgb['green'] + 0.0722 * $rgb['blue']) / 255;
  }

  public function getAlpha($color) {
    $alpha = array('dec' => 1, 'hex' => 1);

    preg_match("/(\s*\d*)\.*(\d*)\)/i", $color, $a);
    if( !empty($a) ) {
      $alpha['dec'] = substr($a[0], 0, strlen($a[0]) - 1);

      if( $alpha['dec'] < 1 ) { $alpha['dec'] = $alpha['dec'] * 100; }
      
      $alpha['hex'] = dechex( $alpha['dec'] );
    }

    return $alpha;
  }

  public function rgbToHex($color) {
    $regex = '/rgba?\(\s?([0-9]{1,3}),\s?([0-9]{1,3}),\s?([0-9]{1,3})/i';

    preg_match($regex, $color, $matches);

    if(count($matches) != 4) {
      die('color not valid RGB format');
    }

    $r = (int)$matches[1] <= 255 ? (int)$matches[1] : 255;
    $g = (int)$matches[2] <= 255 ? (int)$matches[2] : 255;
    $b = (int)$matches[3] <= 255 ? (int)$matches[3] : 255;

    $R = dechex($r); $G = dechex($g); $B = dechex($b);
    if($R === "0") { $R = "00"; }
    if($G === "0") { $G = "00"; }
    if($B === "0") { $B = "00"; }

    return strtoupper("{$R}{$G}{$B}");
  }

  public function hexToRGB($hex, $return_array=false) {
    if( $hex[0] == '#')
      $hex = substr($hex, 1);

    if (strlen($hex) == 3) {
      $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    $r = hexdec($hex[0] . $hex[1]);
    $g = hexdec($hex[2] . $hex[3]);
    $b = hexdec($hex[4] . $hex[5]);

    $rgb = array();
    $rgb['red'] = $r;
    $rgb['green'] = $g;
    $rgb['blue'] = $b;

    if($return_array) {
      return $rgb;

    } else {
      return $b + ($g << 0x8) + ($r << 0x10);
    }
  }

  public function hexToHSL($hex) {
    $RGB = hexToRGB($hex);

    $r = 0xFF & ($RGB >> 0x10);
    $g = 0xFF & ($RGB >> 0x8);
    $b = 0xFF & $RGB;

    $r = ((float)$r) / 255.0;
    $g = ((float)$g) / 255.0;
    $b = ((float)$b) / 255.0;

    $maxC = max($r, $g, $b);
    $minC = min($r, $g, $b);

    $l = ($maxC + $minC) / 2.0;

    if($maxC == $minC) {
      $s = 0;
      $h = 0;

    } else {
      if($l < .5) {
        $s = ($maxC - $minC) / ($maxC + $minC);

      } else {
        $s = ($maxC - $minC) / (2.0 - $maxC - $minC);
      }

      if($r == $maxC)
        $h = ($g - $b) / ($maxC - $minC);

      if($g == $maxC)
        $h = 2.0 + ($b - $r) / ($maxC - $minC);

      if($b == $maxC)
        $h = 4.0 + ($r - $g) / ($maxC - $minC);

      $h = $h / 6.0; 
    }

    $h = (int)round(255.0 * $h);
    $s = (int)round(255.0 * $s);
    $l = (int)round(255.0 * $l);

    return (object) Array('hue' => $h, 'saturation' => $s, 'lightness' => $l);
  }

  public function hslToHex($hsl) {
    $rgb = hslToRGB($hsl);

    return rgbToHex($rgb);
  }

  public function hslToRGB($hsl) {
    $r = 0; $g = 0; $b = 0;

    $hslArray = array('hue' => 0, 'saturation' => 0, 'lightness' => 0);

    if( !is_array($hsl) ) {
      if( strpos($hsl, "%") !== false ) { $hsl = str_replace("%", "", $hsl); }

      $regex = '/hsla?\(\s?([0-9]{1,3}),\s?([0-9]{1,3}),\s?([0-9]{1,3})/i';

      preg_match($regex, $hsl, $matches);

      $hslArray = array( 'hue' => $matches[1], 'saturation' => $matches[2], 'lightness' => $matches[3] );

    } else {
      if( !array_key_exists('hue', $hsl) ) {
        $hslArray = array('hue' => $hsl[0], 'saturation' => $hsl[1], 'lightness' => $hsl[2]);

      } else {
        $hslArray = $hsl;

      }

    }

    $h = $hslArray['hue']; $s = $hslArray['saturation']; $l = $hslArray['lightness'];
      
    if( $hslArray['hue'] > 1 ) { $h = $hslArray['hue'] / 360; }
    if( $hslArray['saturation'] > 1 ) { $s = $hslArray['saturation'] / 100; }
    if( $hslArray['lightness'] > 1 ) { $l = $hslArray['lightness'] / 100; }

    if( $s == 0 ) { 
      $r = $g = $b = $l;

    } else {

      $q = $l < 0.5 ? $l * ( 1.0 + $s ) : $l + $s - $l * $s; // 1

      $p = 2.0 * $l - $q; // 0
      $r = hueToRGB($p, $q, $h + 1.0 / 3.0) * 255;
      $g = hueToRGB($p, $q, $h) * 255;
      $b = hueToRGB($p, $q, $h - 1.0 / 3.0) * 255;

    }

    return "rgb({$r}, {$g}, {$b})";

  }

  public function hueToRGB($p,$q,$t) {
    if($t < 0) $t += 1.0;
    if($t > 1.0) $t -= 1.0;

    if($t < 1.0 / 6.0) return $p + ($q - $p) * 6.0 * $t;
    if($t < 1.0 / 2.0) return $q;
    if($t < 2.0 / 3.0) return $p + ($q - $p) * (2.0 / 3.0 - $t) * 6.0;

    return $p;
  }

  // https://stackoverflow.com/a/8468448
  public function readableColor($hex) {
      $rgb = hexToRGB($hex, true);

      $r = $rgb['red'];
      $g = $rgb['green'];
      $b = $rgb['blue'];

      $squared_contrast = (
          $r * $r * .299 +
          $g * $g * .587 +
          $b * $b * .114
      );

      if($squared_contrast > pow(130, 2)) {
        return '000000';

      } else {
        return 'FFFFFF';

      }
  }
}