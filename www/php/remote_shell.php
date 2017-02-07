<?php
/*
 * Copyright (C) 2005-2006
 * Emmanuel Saracco <esaracco@users.labs.libre-entreprise.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330,
 * Boston, MA 02111-1307, USA.
 */
// Sabai Technology - Apache v2 licence
// Copyright 2016 Sabai Technology
 
  define ('APP_NAME', 'phpRemoteShell');
  define ('APP_VERSION', '0.5.2');

  /* Main configuration array */
  $config = array ();

  /* //////////////////// BEGIN "CUSTOMIZE ME" SECTION \\\\\\\\\\\\\\\\\\\ */
  
  /* Authentication */
  define ('CHECK_AUTH', false);
  define ('AUTH_USER', '!!change_me!!');
  define ('AUTH_PASSWORD', '!!change_me!!');

  /* Default user/group for the remote webserver process when they can not 
   * be retreived automatically. */
  define ('HTTPD_DEFAULT_UID', 33);
  //define ('HTTPD_DEFAULT_UID', 65534);
  define ('HTTPD_DEFAULT_GID', 33);
  //define ('HTTPD_DEFAULT_GID', 65534);
  
  /* Downloads management */
  $config['download'] = array (
    'application' => 'tar -cf - %s | gzip -c > %s',
    'extension' => 'tar.gz',
    'mime-type' => 'application/x-gtar'
  );
  /* Remote informations */
  $config['rinfos'] = array (
    'System' => 'uname -a',
    'Ids' => 'id',
    'Shell' => 'echo $SHELL',
    'Environment' => 'env',
    'Apache' => 'apache -v',
    'Apache2' => 'apache2 -v',
    'Web server' => 'httpd -v',
    'Web server 2' => 'http2d -v',
    'Perl' => 'perl --version',
    'Shell PHP' => 'php --version',
    'MySQL' => 'mysql --version',
    'PostgreSQL' => 'psql --version'
  );
  
  /* //////////////////// END  "CUSTOMIZE ME" SECTION \\\\\\\\\\\\\\\\\\\\ */
  
  /* Common stat macros for file browser */
  define ('S_IFMT', 00170000);
  define ('S_IFSOCK', 0140000);
  define ('S_IFLNK', 0120000);
  define ('S_IFBLK', 0060000);
  define ('S_IFDIR', 0040000);
  define ('S_IFCHR', 0020000);
  define ('S_IFIFO', 0010000);
  define ('S_ISUID', 0004000);
  define ('S_ISGID', 0002000);

  /* Common stat functions for file browser */
  function S_ISLNK ($m) {return ((($m) & S_IFMT) == S_IFLNK);}
  function S_ISDIR ($m) {return ((($m) & S_IFMT) == S_IFDIR);}
  function S_ISCHR ($m) {return ((($m) & S_IFMT) == S_IFCHR);}
  function S_ISBLK ($m) {return ((($m) & S_IFMT) == S_IFBLK);}
  function S_ISFIFO ($m) {return ((($m) & S_IFMT) == S_IFIFO);}
  function S_ISSOCK ($m) {return ((($m) & S_IFMT) == S_IFSOCK);}

  /* Uniq index */
  $_uniq_code = 1;
 
  /* Try to deactivate PHP magic quotes */
  ini_set ('magic_quotes_gpc', '0');
 
  /* Cookie */
  define ('COOKIE_NAME', 'prs');

  /* Popups macros */
  define ('POPUP_DEFAULT_Y', 90);
  define ('POPUP_DEFAULT_X', 250);
  
  /* Shell history macros */
  define ('SHELL_EXECUTE', $_uniq_code++);
  define ('SHELL_HISTORY_EXECUTE', $_uniq_code++);
  define ('SHELL_HISTORY_DELETE', $_uniq_code++);

  /* Shell aliases macros */
  define ('SHELL_ALIASES_ADD', $_uniq_code++);
  define ('SHELL_ALIASES_DELETE', $_uniq_code++);

  /* Shell environment PATH macros */
  define ('SHELL_ENVPATH_ADD', $_uniq_code++);
  define ('SHELL_ENVPATH_DELETE', $_uniq_code++);

  /* Edit profiles macros */
  define ('EDIT_PROFILES_MAX', 5);
  define ('EDIT_PROFILES_SAVE', $_uniq_code++);
  define ('EDIT_PROFILES_LOAD', $_uniq_code++);
  define ('EDIT_PROFILES_UPDATE', $_uniq_code++);
  define ('EDIT_PROFILES_DELETE', $_uniq_code++);

  /* Application notebooks macros */
  define ('SHELL_TYPE_SHELL', $_uniq_code++);
  define ('SHELL_TYPE_PHP_CODE', $_uniq_code++);
  define ('SHELL_TYPE_ABOUT', $_uniq_code++);
  define ('SHELL_TYPE_REMOTE_INFOS', $_uniq_code++);
  define ('SHELL_TYPE_FILE_BROWSER', $_uniq_code++);

  /* Action menu */
  define ('ACTION_MENU_DELETE', $_uniq_code++);
  define ('ACTION_MENU_DOWNLOAD', $_uniq_code++);
  define ('ACTION_MENU_UPLOAD', $_uniq_code++);

  /* PHP functions used by this scripts */
  $config['php_functions'] = array (
    'popen' => array ('type' => 'exec', 'enabled' => false),
    'system' => array ('type' => 'exec', 'enabled' => false),
    'exec' => array ('type' => 'exec', 'enabled' => false),
    'passthru' => array ('type' => 'exec', 'enabled' => false),
    'opendir' => array ('type' => 'browse', 'enabled' => false),
    'readdir' => array ('type' => 'browse', 'enabled' => false)
  );

  /* Main menu */
  $config['main_menu'] = array (
    array (
      'label' => 'Edit',
      'smenu' => array (
        array (
          'label' => 'Profiles',
          'value' => 'profiles'
        )
      )
    ),
    array (
      'label' => "Remote information",
      'value' => SHELL_TYPE_REMOTE_INFOS
    ),
    array (
      'label' => "Shell", 
      'value' => SHELL_TYPE_SHELL,
      'smenu' => array (
        array (
          'label' => 'Command aliases',
          'value' => 'aliases'
        ),
        array (
          'label' => 'Environment PATH',
          'value' => 'envpath'
        )
      )
    ),
    array ( 
      'label' => "PHP code", 
      'value' => SHELL_TYPE_PHP_CODE,
      /* Old version of PHP accept just one parameter for the 
       * "highlight_string" function. */
      'smenu' => (@highlight_string ('dum', true)) ? array (
        array (
          'label' => 'Highlight code',
          'value' => 'highlight'
        )
      ) : null
    ),
    array (
      'label' => "File browser", 
      'value' => SHELL_TYPE_FILE_BROWSER,
      'smenu' => array (
        array (
          'label' => 'Initial path',
          'value' => 'initpath'
        )
      )
    ),
    array (
      'label' => "About", 
      'value' => SHELL_TYPE_ABOUT
    )
  );

  /* Main class */
  class PhpRemoteShell
  {
    var $vars = array ();
    var $sav_vars = array ();
    var $config = array ();
    var $use_opendir = false;
    
    function PhpRemoteShell ($config)
    {
      $this->check_auth (CHECK_AUTH);

      $this->config = $config;

      foreach (array_keys ($this->config['php_functions']) as $f)
        $this->config['php_functions'][$f]['enabled'] = 
          $this->_check_php_function ($f);

      $this->get_all_values ();

      $this->action ();
    }

    function get_all_values ()
    {
      foreach (array (
        'htmloutput',
        'display_type',
        'show_hide_aliases',
        'show_hide_envpath',
        'show_hide_initpath',
        'show_hide_highlight',
        'show_hide_profiles',
        'profile_current',
        'profiles_index',
        'profile_name',
        'profiles_box_x',
        'profiles_box_y',
        'command',
        'command_current',
        'env_current_path',
        'phpcode_current',
        'history_index',
        'envpath_index',
        'action_requested',
        'action_type',
        'action_result',
        'dir_current',
        'file_current_rights',
        'is_nav',
        'alias_name',
        'alias_value',
        'envpath_value',
        'file_browser_initpath',
        'aliases_box_x',
        'aliases_box_y',
        'envpath_box_x',
        'envpath_box_y',
        'initpath_box_x',
        'initpath_box_y',
        'highlight_box_x',
        'highlight_box_y',
        'command_current_output'

      ) as $var)
        if (!isset ($this->vars[$var]))
          $this->vars[$var] = 
            $this->utf8_decode ($this->_get_http_var ($var, ''));
  
      if (!isset ($this->vars['history']))
        $this->vars['history'] = 
          $this->form_unserialize ($this->_get_http_var ('history', array ()));
  
      if (!isset ($this->vars['aliases']))
        $this->vars['aliases'] = 
          $this->form_unserialize ($this->_get_http_var ('aliases', array ()));

      if (!isset ($this->vars['envpath']))
        $this->vars['envpath'] = 
          $this->form_unserialize ($this->_get_http_var ('envpath', array ()));

      if (!isset ($this->vars['profiles']))
        $this->vars['profiles'] = 
          $this->form_unserialize ($this->_get_http_var ('profiles', array ()));

      if (!isset ($this->vars['choice']))
        $this->vars['choice'] = $this->_get_http_var ('choice', array ());

      if (!isset ($this->vars['www_user']))
      {
        list ($this->vars['www_user'], $this->vars['www_group']) = 
          $this->get_www_user_infos ();
      }

      $this->_normalize_envpath ();
      $this->_normalize_aliases ();
      $this->_normalize_initpath ();
      $this->_normalize_profiles ();
      $this->_normalize_dir_current ();
      $this->_normalize_profile_name ();

      $this->_normalize_box_pos ('aliases');
      $this->_normalize_box_pos ('envpath');
      $this->_normalize_box_pos ('initpath');
      $this->_normalize_box_pos ('profiles');
      $this->_normalize_box_pos ('highlight');
    }

    function get_root_path ()
    {
      $res = $this->vars['file_browser_initpath'];
      if (!$res) $res = '/';

      if (!@is_dir ($res))
        $res = getcwd (); 
          
      if (!preg_match ('{\/$}', $res)) $res .= '/';

      return $res;
    }

    function get_execute_function ()
    {
      foreach ($this->config['php_functions'] as $k => $v)
        if ($v['type'] == 'exec' && $v['enabled']) return $k;

      return '';
    }

    function execute_enabled ()
    {
      foreach ($this->config['php_functions'] as $k => $v)
        if ($v['type'] == 'exec' && $v['enabled']) return true;

      return false;
    }

    function browse_enabled ()
    {
      return (
        $this->config['php_functions']['opendir']['enabled'] &&
        $this->config['php_functions']['readdir']['enabled']
      );

      return false;
    }

    function php_function_enabled ($name)
    {
      return $this->config['php_functions'][$name]['enabled'];
    }

    function _check_php_function ($name)
    {
      $ret = false;

      if (($f = ini_get ('disable_functions')) &&
        in_array ($name, explode (',', $f)))
          return false;

      switch ($name)
      {
        case 'exec':
          $ret = true;
          break;
        case 'passthru':
          $ret = true;
          break;
        case 'system':
          $ret = true;
          break;
        case 'popen':
          $ret = true;
          break;
        case 'opendir':
          if (($ret = @opendir ($this->get_root_path ())))
            @closedir ($ret);
          break;
        case 'readdir':
          if (($d = @opendir ($this->get_root_path ())))
          {
            $ret = @readdir ($d);
            @closedir ($d);
          }
          break;
      }

      return $ret;
    }

    function check_auth ($check)
    {
      if (!$check) return;
      
      if (
        !isset ($_SERVER['PHP_AUTH_USER']) ||
        $_SERVER['PHP_AUTH_USER'] != AUTH_USER ||
        !isset ($_SERVER['PHP_AUTH_PW']) ||
        $_SERVER['PHP_AUTH_PW'] != AUTH_PASSWORD)
      {
        header ('HTTP/1.1 401 Authorization Required');
        header ('Date: ' . gmdate ('D, d M Y H:i:s') . ' GMT');
        header ('WWW-Authenticate: Basic realm="PRS"');
        header ('Connection: close');
        header ('Content-Type: text/html; charset=iso-8859-1');

      	if ($_SERVER['PHP_AUTH_USER'] != AUTH_USER ||
    	    $_SERVER['PHP_AUTH_PW'] != AUTH_PASSWORD) exit (1);
      }
    }

    function get_www_user_infos ()
    {
      $this->_save_user_inputs ();
      
      $this->_reset_user_inputs ();
      $this->vars['command_current'] = "id -un";
      $this->command_current_execute ();
      $user = trim ($this->vars['command_current_output']);
      if (!$user) $user = HTTPD_DEFAULT_UID;

      $this->_reset_user_inputs ();
      $this->vars['command_current'] = "id -gn";
      $this->command_current_execute ();
      $group = trim ($this->vars['command_current_output']);
      if (!$group) $group = HTTPD_DEFAULT_GID;

      $this->_restore_user_inputs ();

      return array ($user, $group);
    }

    function setCookie ($key, $value)
    {
      $cookie = '';

      if (!empty ($value))
        $cookie = base64_encode (serialize ($value));

      setcookie ($key, $cookie, mktime (0, 0, 0, 1, 1, 2035), '/');
    }

    function getCookie ($key)
    {
      if (!isset ($_COOKIE[$key]) || empty ($_COOKIE[$key])) 
          return '';
      
      $cookie = $_COOKIE[$key];
      $cookie = unserialize (base64_decode ($cookie));

      return $cookie;
    }

    function array_clean_for_cookie ($arr)
    {
      foreach ($arr as $k => $v)
      {
        if (
          empty ($v) ||
          strpos ($k, 'profile') !== false ||
          $k == 'action_requested' ||
          $k == 'www_group' ||
          $k == 'www_user')
          unset ($arr[$k]);
        elseif (is_array ($v))
        {
          if (!count ($v))
            unset ($arr[$k]);
          else
            $this->array_clean_for_cookie ($arr[$k]);
        }
      }

      return $arr;
    }

    function save_profile ($name)
    {
      $value = $this->array_clean_for_cookie ($this->vars);
      $this->setCookie (COOKIE_NAME . "_$name", $value);
    }

    function update_profile ($name)
    {
      $this->save_profile ($name);
    }

    function delete_profile ($name)
    {
      $this->setCookie (COOKIE_NAME . "_$name", '');
    }

    function load_profile ($name)
    {
      $vars_sav = $this->vars;

      $value = $this->getCookie (COOKIE_NAME . "_$name");
      if (is_array ($value))
        $this->vars = $value;

      $this->vars['profiles'] = $this->get_profiles ();
      foreach ($vars_sav as $k => $v)
        if (strpos ($k, 'profile') !== false)
          $this->vars[$k] = $v;

      $this->get_all_values ();
    }

    function get_profiles ()
    {
      $profiles = array ();

      foreach ($_COOKIE as $k => $v)
        if (strpos ($k, COOKIE_NAME . '_') !== false) 
        {
          $k = substr ($k, 4, strlen ($k) - 3);
          $profiles[$k] = $k;
        }

      return $profiles;
    }

    function form_serialize ($val)
    {
      return base64_encode (serialize ($val));
    }

    function form_unserialize ($val)
    {
      return (is_array ($val)) ?
        $val : unserialize (base64_decode ($val));
    }

    function form_get_serialize ($name)
    {
      return $this->form_serialize ($this->vars[$name]);
    }

    function get_show_hide ($name)
    {
      if (
        $this->vars["show_hide_$name"] != 'hidden' &&
        $this->vars["show_hide_$name"] != 'visible'
      )
        $this->vars["show_hide_$name"] = 'hidden';

      return $this->vars["show_hide_$name"];
    }

    function get_display_type ()
    {
      return $this->vars['display_type'];
    }

    function get_profile_current ()
    {
      return $this->vars['profile_current'];
    }

    function get_command_current ()
    {
      return $this->vars['command_current'];
    }

    function get_phpcode_current ()
    {
      if (!$this->phpcode_current_exists ()) return '';

      if (!preg_match ("/;$/", $this->vars['phpcode_current']))
        $this->vars['phpcode_current'] .= ';';

      return $this->vars['phpcode_current'];
    }

    function history_exists ()
    {
      return (
        is_array ($this->vars['history']) && 
        count ($this->vars['history']) > 0
      );
    }

    function command_current_exists ()
    {
      return ($this->vars['command_current'] != '');
    }

    function phpcode_current_exists ()
    {
      return ($this->vars['phpcode_current'] != '');
    }

    function cmd_replace_aliases ($cmd)
    {
      if (preg_match_all ('/\$([a-z,_,0-9]+)/', $cmd, $matches))
      {
        foreach ($matches[1] as $alias)
        {
          if (isset ($this->vars['aliases'][$alias]))
            $cmd = preg_replace ("/\\$$alias/", 
              $this->vars['aliases'][$alias], $cmd);
        }
      }

      return $cmd;
    }

    function action ()
    {
      $this->vars['command_current'] = '';

      sort ($this->vars['history']);
      sort ($this->vars['envpath']);
      sort ($this->vars['profiles']);

      switch ($this->vars['action_requested'])
      {
        /* SHELL */

          /* History */

        case SHELL_EXECUTE:

          $this->vars['command_current'] = $this->vars['command'];
          break;

        case SHELL_HISTORY_EXECUTE:

          $this->vars['command_current'] = 
            $this->vars['history'][(int) $this->vars['history_index']];
          break;

        case SHELL_HISTORY_DELETE:

          unset ($this->vars['history'][(int) $this->vars['history_index']]);
          break;

          /* Aliases */

        case SHELL_ALIASES_ADD:

          $name = trim ($this->vars['alias_name']);
          $value = trim ($this->vars['alias_value']);

          if (!empty ($name) && !empty ($value))
            $this->vars['aliases'][$name] = $value;
          break;
      
        case SHELL_ALIASES_DELETE:

          unset ($this->vars['aliases'][$this->vars['alias_name']]);
          break;
      
          /* Environment PATH */

        case SHELL_ENVPATH_ADD:
          $value = trim ($this->vars['envpath_value']);
  
          if (!empty ($value) && !in_array ($value, $this->vars['envpath']))
            array_push ($this->vars['envpath'], $value);
          break;
      
        case SHELL_ENVPATH_DELETE:
          unset ($this->vars['envpath'][(int) $this->vars['envpath_index']]);
          break;

        /* EDIT */

          /* Profile */

        case EDIT_PROFILES_SAVE:
          $name = trim ($this->vars['profile_name']);

          if (strlen ($name) && !in_array ($name, $this->vars['profiles']))
          {
            array_push ($this->vars['profiles'], $name);
            $this->vars['profile_current'] = $name;
  
            $this->save_profile ($name);
          }
          break;

        case EDIT_PROFILES_UPDATE:
          $this->update_profile (
            $this->vars['profiles'][(int) $this->vars['profiles_index']]);
          break;
      
        case EDIT_PROFILES_LOAD:
          $this->load_profile (
            $this->vars['profiles'][(int) $this->vars['profiles_index']]);
          break;
      
        case EDIT_PROFILES_DELETE:
          $name = $this->vars['profiles'][(int) $this->vars['profiles_index']];
  
          unset ($this->vars['profiles'][(int) $this->vars['profiles_index']]);

          $this->delete_profile ($name);

          if ($this->vars['profile_current'] == $name)
        	  $this->vars['profile_current'] = '';
          break;
      }

      if ($this->command_current_exists ())
      {
        $this->vars['command_current'] = 
          $this->cmd_replace_aliases ($this->vars['command_current']);

        if (!in_array ($this->vars['command_current'], $this->vars['history']))
          array_push ($this->vars['history'], $this->vars['command_current']);
      }

      if ($this->vars['is_nav'] != 1 && $this->vars['action_type'] != '')
      {
        if (isset ($this->vars['choice']) && count ($this->vars['choice']))
      	{
          switch ($this->vars['action_type'])
      	  {
      	    case ACTION_MENU_DELETE:

      	      $this->vars['action_result'] = 
      	        $this->_delete_files ($this->vars['choice']);
      	      break;

      	    case ACTION_MENU_DOWNLOAD:

      	      $this->vars['action_result'] =
      	        $this->_download_files ($this->vars['choice']);
          }
      	}
      	elseif ($this->vars['action_type'] == ACTION_MENU_UPLOAD)
      	  $this->vars['action_result'] = $this->_upload_file ();
      }

      sort ($this->vars['profiles']);
      sort ($this->vars['envpath']);
      sort ($this->vars['history']);
    }

    function get_action_result_html ()
    {
      return "<p>" . $this->vars['action_result'] . "</p>";
    }

    function _upload_file ()
    {
      $output = '';

      if (!isset ($_FILES) || !$_FILES["upload_file"]["tmp_name"]) return;

      $src = $_FILES["upload_file"]["tmp_name"];
      $dest = $this->vars['dir_current'] . "/" . $_FILES["upload_file"]["name"];

      $output = "
        <table>
        <tr><th colspan=2 class='caption'>Result</th></tr>
      	<tr class='header'><th>Action</th><th>Message</th></tr>
        <tr class='odd'>
        <td class='label'>Uploading file to $dest</td>
        <td class='value'>
      ";

      ob_start (); 
      move_uploaded_file ($src, $dest);
      $ret = ob_get_contents ();
      ob_end_clean ();

      $output .= "
        $ret
        </td>
        </tr>
        </table>
      ";

      return $output;
    }

    function _download_files (&$files)
    {
      $output = '';
      
      $output = "
        <table>
        <tr><th colspan=2 class='caption'>Result</th></tr>
      	<tr class='header'><th>Action</th><th>Message</th></tr>
      ";

      $src = '';
      srand (time ());
      $dst = 
        "/tmp/.prs-tmp-" . rand () . '.' .
        $this->config['download']['extension']; 

      foreach ($files as $f)
        $src .= "$f ";
      
      $cmd = sprintf ($this->config['download']['application'], 
        "$src 2> /dev/null ", "$dst 2> /dev/null");

      $this->_save_user_inputs ();
      $this->_reset_user_inputs ();
      $this->vars['command_current'] = $cmd;
      $this->command_current_execute ();
      $this->_restore_user_inputs ();

      $this->_send_file ($dst, true);

      return $output;
    }

    function _send_file ($file, $delete_after)
    {
      header ('Content-Type: ' . $this->config['download']['mime-type']);
      header ('Content-Length: ' . filesize ($file));
      header ('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

      if (strstr ($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
      {
        header ('Content-Disposition: inline; filename="prs_download.' . 
    	  $this->config['download']['extension'] . '"');
        header ('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header ('Pragma: public');
      }
      else
      {
        header ('Content-Disposition: attachment; filename="prs_download.' . 
    	  $this->config['download']['extension'] . '"');
        header ('Pragma: no-cache');
      }

      if (!($h = @fopen ($file, 'r')))
        return;
      while ($data = fread ($h, 8192))
        print $data;
      fclose ($h);

      if ($delete_after)
        unlink ($file);
      
      exit (0);
    }

    function rmdirr ($dir) 
    {
      if (is_file ($dir))
        unlink ($dir);
      elseif ($handle = @opendir ($dir)) 
      {
        while (($item = @readdir ($handle)) !== false) 
        {
          if ($item != '.' && $item != '..') 
          {
            if (is_dir ("$dir/$item")) 
              rmdirr ("$dir/$item");
            else 
              unlink ("$dir/$item");
          }
        }

        closedir ($handle);
        rmdir ($dir);
      }
    }

    function _delete_files (&$files)
    {
      $output = '';
      
      $output = "
        <table>
        <tr><th colspan=2 class='caption'>Result</th></tr>
      	<tr class='header'><th>Action</th><th>Message</th></tr>
      ";

      $this->_save_user_inputs ();
      $row_color = '';
      foreach ($files as $file)
      {
        $row_color = ($row_color == 'odd') ? 'even' : 'odd';
        $output .= "<tr class='$row_color'>";
        $output .= "<td class='label'>Deleting file $file</td>";

        ob_start ();
        $this->rmdirr ($file);
        $this->vars['command_current_output'] = ob_get_contents ();
        ob_end_clean ();

      	$output .= "<td class='value'>" . 
    	  $this->vars['command_current_output'] . "</td>";
      	$output .= "</tr>";
      }
      $this->_restore_user_inputs ();

      $output .= "</table>";

      if (!is_file ($this->vars['dir_current']) && 
          !is_dir ($this->vars['dir_current']))
        $this->vars['dir_current'] = dirname ($this->vars['dir_current']);

      return $output;
    }

    function _save_user_inputs ()
    {
      $this->sav_vars = base64_encode (serialize ($this->vars));
    }

    function _restore_user_inputs ()
    {
      $this->vars = unserialize (base64_decode ($this->sav_vars));
    }

    function _reset_user_inputs ()
    {
      $this->vars['command_current'] = '';
      $this->vars['command_current_output'] = '';
    }

    function get_menu_html ()
    {
      $output = '';

      $output = "<table class='menu'><tr>";
      $i = 0;
      foreach ($this->config['main_menu'] as $m)
      {
        $have_smenu = isset ($m['smenu']) && is_array ($m['smenu']); 
        $smenu = ($i++) . '_' . 'smenu';

        if (isset ($m['value']) && $m['value'])
          $output .= sprintf ("
            <td><div class=\"menu\" %s
              onMouseOut=\"%s%s\"
              onMouseOver=\"this.style.cursor = 'default';
              this.style.color='yellow';menu_show('$smenu')\"
              onClick=\"document.forms[0].display_type.value='%s'; 
      		    document.forms[0].action_requested.value=''; 
              document.forms[0].action_type.value=''; 
              document.forms[0].dir_current.value='';
              _submit()\">%s</div>",
              (($this->vars['display_type'] == $m['value']) ? 
                ' style="color: yellow" ' : ''),
              (($have_smenu) ? "menu_hide_async('$smenu');" : ''),
              (($this->vars['display_type'] == $m['value']) ? 
                '' : "this.style.color='cornflowerblue'"),
              $m['value'],
              $m['label']
          );
        else
          $output .= sprintf ("
            <td><div class=\"menu\"
              onMouseOut=\"%s\"
              onMouseOver=\"this.style.cursor = 'default';
              menu_show('$smenu')\">%s</div>",
              (($have_smenu) ? "menu_hide_async('$smenu');" : ''),
              $m['label']
          );
         
        if ($have_smenu)
        {
          $output .= "
            <div id=\"$smenu\" class=\"smenu\" 
              style=\"visibility: hidden;position: absolute;\" 
              onMouseOver=\"currentOver='$smenu';menu_show('$smenu');\"
              onMouseOut=\"currentOver=null;menu_hide('$smenu');\">
          ";

          foreach ($m['smenu'] as $sm)
          {
            $output .= "
              <table><tr><td><input onClick=\"show_hide('" . $sm['value'] . 
              "', " . $sm['value'] . "_cb);\" type=\"checkbox\" name=\"" . 
              $sm['value'] . "_cb\"" . 
              (($this->get_show_hide ($sm['value']) == 'hidden') ? 
                '' : ' checked') . "></td>
              <td nowrap><a href=\"javascript:show_hide('" . $sm['value'] . 
              "', document.forms[0]." . $sm['value'] . "_cb);\">" . 
              $sm['label'] . "</a></td></tr></table>";
          }

          $output .= "</div>";
        }

        $output .= "</td>";
      }

      $output .= "</tr></table>";
      return $output;
    }

    function get_php_function_alert_html ($type = 'all')
    {
      $output = "
        Some common PHP functions are 
        <font color=\"red\"><b>not available</b></font>.
        <br />
      ";

      switch ($type)
      {
        case 'all': $output .= "This feature <b>has been disabled</b>.";break;
        case 'some': $output .= 
          "Some operations will <b>not be enabled</b> or will 
           <b>certainly fail</b>.";break;
      }

      return $output;
    }

    function get_safe_mode_alert_html ($type = 'all')
    {
      $output = "
        PHP <b>safe_mode</b> is <font color=\"red\"><b>activated</b></font>.
        <br />
      ";

      switch ($type)
      {
        case 'all': $output .= "This feature <b>has been disabled</b>.";break;
        case 'some': $output .=  
          "Some operations will <b>not be enabled</b> or will 
           <b>certainly fail</b>.";break;
      }

      return $output;
    }

    function get_remote_infos_html ()
    {
      $infos = array ();

      $this->_save_user_inputs ();
      foreach ($this->config['rinfos'] as $k => $v)
      {
        $this->_reset_user_inputs ();
        $this->vars['command_current'] = $v;
        $this->command_current_execute ();
      	if ($this->vars['command_current_output'] != '' &&
	      /* FIXME */
	      !strstr ($this->vars['command_current_output'], 'not found') &&
	      !strstr ($this->vars['command_current_output'], 'such file'))
          $infos[$k] = $this->vars['command_current_output'];
      }
      $this->_restore_user_inputs ();

      $output = "
        <table>
        <tr><th colspan=2 class='caption'>Some remote information</th></tr>
        <tr class='header'><th>Name</th><th>Value</th></tr>
      ";

      foreach ($infos as $k => $v)
      {
        $output .= "<tr><td class='label'>$k</td><td>";
      	$v = chop ($v);
      	if (strchr ($v, "\n"))
      	{
          $infos1 = explode ("\n", $v);
          $output .= "<table>";
      	  foreach ($infos1 as $v1)
      	  {
      	    if (strchr ($v1, '='))
      	    {
      	      list ($k2, $v2) = explode ('=', $v1);
      	      $output .= "<tr><td class='label'>$k2</td><td>$v2</td></tr>";
      	    }
      	    elseif ($v1)
      	      $output .= "<tr><td>$v1</td></tr>";
      	  }
      	  $output .= "</table>";
      	}
      	else
      	  $output .= "$v</td>";

        	$output .= "</tr>";
      }
      $output .= "</table>";

      return $output;
    }

    function get_dir_current ()
    {
      return $this->vars['dir_current'];
    } 
  
    function get_file_current_rights ()
    {
      return $this->vars['file_current_rights'];
    } 

    function _get_browse_path ()
    {    
      $path = '';
      $output = '';

      if (!@is_file ($this->vars['dir_current']) && 
        !@is_dir ($this->vars['dir_current']))
        $this->vars['dir_current'] = $this->get_root_path ();

      $p = $this->vars['dir_current'];
      
      for ($i = 0; $i < strlen ($p); $i++)
      {
        if ($p[$i] != '/')
      	{
      	  $path .= $p[$i];
      	  $name .= $p[$i];
      	}
        else
      	{
          $output .= ($path) ?
      	    "&nbsp;<input type='button' class='file_browser_path'
	            onClick=\"action_type.value='';" .
    		    "dir_current.value='$path';_submit()\" value=\"$name\" />" :
      	    "&nbsp;<input type='button' class='file_browser_path'
	            onClick=\"action_type.value='';" .
    		    "dir_current.value='/';_submit()\" value=\"/\" />";
      	    $path .= '/';
            $name = '';
        }
      }

      return $output;
    }
  
    function _get_file_data_from_line ($line)
    {
      $arr = preg_split ("/\s+/", $line, 9);

      /* Not a valid data */
      if (count ($arr) <= 3) return null;

      /* For the moment we do not manage devices */
      if ($this->_is_device ($arr[0])) return null;

      /* A problem with env PATH? */
      if (!isset ($arr[5])) return null;

      /* Fixed a problem with some 'ls' output and symlinks */
      if (isset ($arr[8]) && $this->_is_symlink ($arr[0]) &&
        preg_match ('{^\-\>}', $arr[8]))
      {
        $arr[7] = "$arr[7] $arr[8]";
        unset ($arr[8]);
      }

      /* To fix a problem with some system 'ls' output */
      if (preg_match ("/^([0-9]{4}.[0-9]{2}).([0-9]{2})$/", 
        $arr[5], $matches))
      {
        $arr[8] = $arr[7];
        $arr[7] = $arr[6];
        $arr[5] = $matches[1];
        $arr[6] = $matches[2];
      }

      return $arr;
    }

    function _normalize_profile_name ()
    {
      $name = $this->vars['profile_name'];
      $name = preg_replace ('#[\=,\,,\s,\013,\014]#', '_', $name);
      $this->vars['profile_name'] = $name;
    }

    function _normalize_box_pos ($name)
    {
      if (!$this->vars[$name . '_box_x'] && !$this->vars[$name . '_box_y'])
      {
        $this->vars[$name . '_box_x'] = POPUP_DEFAULT_X . 'px';
        $this->vars[$name . '_box_y'] = POPUP_DEFAULT_Y . 'px';
      }
    }

    function _normalize_envpath ()
    {
      /* Default env PATH */
      if (count ($this->vars['envpath']) == 0)
        $this->vars['envpath'] = array (
          '/bin',
          '/sbin',
          '/usr/bin',
          '/usr/sbin',
          '/usr/local/bin',
          '/usr/local/sbin'
        );
    }

    function _normalize_profiles ()
    {
      if (count ($this->vars['profiles']) == 0)
        $this->vars['profiles'] = $this->get_profiles ();
    }

    function _normalize_aliases ()
    {
      /* Default aliases */
      if (count ($this->vars['aliases']) == 0)
        $this->vars['aliases'] = array (
          'ls' => 'ls -al',
        );
    }

    function _normalize_dir_current ()
    {
      $path = trim ($this->vars['dir_current']);

      if (empty ($path))
        $path = $this->get_root_path ();

      $path = preg_replace ("/^\/\.\.$/", '', $path);

      if (preg_match ("/^(.*)\/[^\/]+\/\.\.$/", $path, $sub))
        $path = $sub[1];
	
      $path = preg_replace ("/\/\.$/", '', $path);

      $this->vars['dir_current'] = $path;
    }

    function _normalize_initpath ()
    {
      $path = trim ($this->vars['file_browser_initpath']);

      if (!preg_match ('{\/$}', $path)) $path .= '/';
      if (!preg_match ('{^\/}', $path)) $path = "/$path";
      $path = preg_replace ('{[\/\/]+}', '{/}', $path);

      if (strpos ($path, '.') !== false || !@is_dir ($path)) 
        $path = $this->get_root_path ();

      $this->vars['file_browser_initpath'] = $path;
    }

    function get_file_browser_initpath ()
    {
      return $this->vars['file_browser_initpath'];
    }

    function _fmodeToString ($mode)
    {
      $perms = $mode;

      if     (S_ISFIFO ($perms)) $permstr = 'p';
      elseif (S_ISCHR ($perms)) $permstr = 'c';
      elseif (S_ISDIR ($perms)) $permstr = 'd';
      elseif (S_ISBLK ($perms)) $permstr = 'b';
      elseif (S_ISLNK ($perms)) $permstr = 'l';
      elseif (S_ISSOCK ($perms)) $permstr = 's';
      else $permstr = '-'; 

      $permstr .= $perms & 0x0100 ? 'r' : '-';
      $permstr .= $perms & 0x0080 ? 'w' : '-';
      $permstr .= $perms & 0x0040 ? 'x' : '-';
      $permstr .= $perms & 0x0020 ? 'r' : '-';
      $permstr .= $perms & 0x0010 ? 'w' : '-';
      $permstr .= $perms & 0x0008 ? 'x' : '-';
      $permstr .= $perms & 0x0004 ? 'r' : '-';
      $permstr .= $perms & 0x0002 ? 'w' : '-';
      $permstr .= $perms & 0x0001 ? 'x' : '-';

      return $permstr;
    }

    function _get_opendir_output ($dir, $retry = 0)
    {
      $output = '';

      if (!preg_match ('{/$}', $dir)) $dir .= '{/}';

      if ((!$d = @opendir ($dir)) && !$retry)
        return $this->_get_opendir_output ($this->get_root_path (), ++$retry);
      elseif ($retry > 2)
        return '';

      while (($f = @readdir ($d)))
      {
        $p = "$dir$f";
        $p = preg_replace ('{\/\.$}', '{/}', $p);

        if (preg_match ('/\/\.\.$/', $p))
        {
          $i = 0;
          while ($p && $i++ < 2)
            $p = substr ($p, 0, strrpos ($p, '/'));
          $p .= '/';
        }
        
        if (@is_link ("$p"))
          $s = @lstat ("$p");
        elseif (!($s = @stat ("$p")))
          $s = array (
            'uid' => 'X', 'gid' => 'X',
            'mode' => 0,
            'mtime' => 0,
            'size' => 'X'
          );

        $user = $s['uid']; 
        $group = $s['gid'];

//        if (($p = @posix_getpwuid ($user))) $user = $p['name'];
//        if (($p = @posix_getgrgid ($group))) $group = $p['name'];

        $output .= $this->_fmodeToString ($s['mode']) . " 00 $user $group ";
        $output .= $s['size'] . " " . 
          (($s['mtime']) ? 
            strftime ("%b %d %H:%M", $s['mtime']) :
            'X X X:X') . " " . $f . "\n";
      }

      @closedir ($d);

      return $output;
    }

    function get_browse_dir ()
    {
      $can_write = true;
      $can_write_some = false;
      $can_read = true;
      $can_upload = null;
    
      /* If link come from a symlink name */
      if (preg_match ("/^(.*?)\s+\-\>\s+(.*?)$/", 
        $this->vars['dir_current'], $matches))
      {
        $symlink = $matches[1];
        $reallink = $matches[2];
        $dir = dirname ($symlink);
        if ($dir == '/' || $reallink[0] == '/') 
          $dir = '';

        $newfile = "$dir/$reallink";
        $newfile = preg_replace ('{/+}', '{/}', $newfile);

        /* Retreive new file rights */
        $this->_save_user_inputs ();
        $this->_reset_user_inputs ();
        $this->vars['command_current'] = 'ls -l ' . escapeshellarg ($newfile);
        $this->command_current_execute ();
      	$tmp = $this->vars['command_current_output'];
        $this->_restore_user_inputs ();
        $this->vars['dir_current'] = $newfile;

        $arr = $this->_get_file_data_from_line ($tmp);
        $this->vars['file_current_rights'] = "$arr[2],$arr[3],$arr[0]";
      }

      if (@is_file ($this->vars['dir_current']))
      {
        $arr = explode (',', $this->vars['file_current_rights']);
        $can_write = $this->_can_write_file ($arr[0], $arr[1], $arr[2]);
        $can_write_some = $can_write;
        $can_read = $this->_can_read_file ($arr[0], $arr[1], $arr[2]);
        printf ("<p>%s</p>", $this->_get_browse_path ());
	
        $dir = $this->vars['dir_current'];
        $this->_save_user_inputs ();
        $this->_reset_user_inputs ();
        $this->vars['command_current'] = 'file ' . escapeshellarg ($dir);
        $this->command_current_execute ();
      	$tmp = $this->vars['command_current_output'];
        $this->_restore_user_inputs ();

        if (!$tmp)
        {
          $tmp = $dir;
          $bad = (!preg_match (
          "/(php|htm|pl|pm|xml|xsl|sh|py|java|css|patch)/i", $tmp));
        }
        else
          $bad =  (!preg_match ("/(text|ascii|php|html|perl|script)/i", $tmp));

        if ($bad)
      	{
          printf ("
      	    <input type='hidden' name='choice[]' value=\"%s\" />
      	    <span class='title_file'>%s</span>
      	    <p>%s</p>
      	    <pre>%s</pre>",
      	    $this->htmlentities ($this->vars['dir_current']),
      	    basename ($dir),
      	    (strstr ($tmp, " empty")) ?
      	      "This is a empty file:" :
      	      "You can not view this file content:",
      	    $tmp
          );
      	}
        else
      	{
          $this->vars['command_current_output'] = 
            implode ('', file ($this->vars['dir_current']));

          $toolong = false;
          if (strlen ($this->vars['command_current_output']) > 5000)
          {
            $toolong = true;
            $this->vars['command_current_output'] = 
              substr ($this->vars['command_current_output'], 0, 5000) . 
              "\n[...]";
          }

      	  printf ("
            %s
    	      <input type='hidden' name='choice[]' value=\"%s\" />
    	      <pre>%s</pre><pre>%s</pre>", 
            ($toolong) ? 
              "File too long to be displayed entirely (max. 5000 chars)." : '',
    	      $this->vars['dir_current'],
    	      $this->htmlentities ($this->vars['dir_current']),
    	      $this->htmlentities ($this->vars['command_current_output'])
          );
        }
      }
      else
      {
        if (!preg_match ("/\/$/", $this->vars['dir_current']))
          $this->vars['dir_current'] .= '/';
        if (!preg_match ("/^\//", $this->vars['dir_current']))
          $this->vars['dir_current'] = '/' . $this->vars['dir_current'];

        printf ("<p>%s</p>", $this->_get_browse_path ());

        if (!$this->use_opendir)
        {
          $this->vars['command_current'] = 'ls -al ' . 
        	  escapeshellarg ($this->vars['dir_current']);
          $this->vars['command_current_output'] = '';
          $this->command_current_execute ();

          if (!$this->use_opendir &&
            $this->vars['command_current_output'] == '' &&
            $this->php_function_enabled ('opendir'))
          {
            $this->vars['command_current_output'] = 
              $this->_get_opendir_output ($this->vars['dir_current']);

            if ($this->vars['command_current_output'] != '')
              $this->use_opendir = true;
          }
        }
        else
          $this->vars['command_current_output'] = 
            $this->_get_opendir_output ($this->vars['dir_current']);

        print '<table class="file_browser">';
      	print "
      	  <th>&nbsp;</th>
      	  <th colspan=2>Rights</th>
      	  <th>User</th>
      	  <th>Group</th>
      	  <th>Size</th>
      	  <th>Month</th>
      	  <th>Day</th>
      	  <th>Time</th>
      	  <th>Name</th>
      	";

        $row_color = '';
        foreach (explode ("\n", $this->vars['command_current_output']) as $l)
        {
          $arr = $this->_get_file_data_from_line ($l);
          if (!$arr || !isset ($arr[8])) continue;

      	  if ($can_upload == null && $arr[8] == ".")
      	    $can_upload = $this->_can_write_file ($arr[2], $arr[3], $arr[0]);

      	  if (!empty ($arr[8]))
      	  {
      	    if ($this->_can_write_file ($arr[2], $arr[3], $arr[0]) && 
      	      !$this->_is_symlink ($arr[0]) && !$this->_is_socket ($arr[0]))
            {
    	        $class_color = 'rights_write';
              if (!$can_write_some)
                $can_write_some = true;
            }
      	    else if ($this->_can_read_file ($arr[2], $arr[3], $arr[0]))
      	      $class_color = 'rights_read';
      	    else
      	      $class_color = 'rights_bad';
	      
            $file_path = $this->vars['dir_current'] . $arr[8];
      	    $file_path = addslashes ($file_path);

      	    $value = ($this->_is_directory ($arr[0])) ? "[$arr[8]]/" : $arr[8];
      	    $row_color = ($row_color == 'odd') ? 'even' : 'odd';
            print "<tr class='$row_color'><td width='1%'>";
      	    if ($class_color != 'rights_bad' && 
    	        $arr[8] != '.' && $arr[8] != '..')
      	      print "
	              <input type=\"checkbox\" name=\"choice[]\" 
  	             value=\"" . addslashes ($this->htmlentities ($file_path)) . 
                 "\" />";
      	    else
      	      print '&nbsp;';

      	    print "
      	      </td>
      	      <td width='1%' class='$class_color'>&nbsp;</td>
      	      <td width='9%'>$arr[0]</td>
      	      <td width='10%'>$arr[2]</td>
      	      <td width='10%'>$arr[3]</td>
      	      <td width='5%'>$arr[4]</td>
	            <td width='5%'>$arr[5]</td>
      	      <td width='5%'>$arr[6]</td>
      	      <td width='5%'>$arr[7]</td>
      	      <td width='40%' class='name'>";

            if ($class_color != "rights_bad" && !$this->_is_socket ($arr[0]))
    	       print " 
                <input type='button' class='file_browser' 
	                 onClick=\"is_nav.value=1;" .
              "file_current_rights.value='" . 
              "$arr[2],$arr[3],$arr[0]" .
              "';dir_current.value='" . 
              addslashes ($this->htmlentities ($file_path)) . "';_submit()\" 
              value=\"" . 
              addslashes ($this->htmlentities ($value)) . "\" /></td></tr>";
            else
              print $this->htmlentities ($value);
          }
        }
        print '</table>';
      }

      print " 
        <p><table class='file_browser_menu'><tr>
          <td><input " . 
          (($can_read) ? "" : " class=\"disabled\" disabled") . 
          " type='button' onClick=\"dir_current.value='" .
        addslashes ($this->htmlentities ($this->vars['dir_current'])) . 
        "';action_type.value='" . ACTION_MENU_DOWNLOAD . 
        "';_submit();\" value='Download' /></td>";

      if (!is_file ($this->vars['dir_current']))
      {
        print "
          <td>
          <input type='file' name='upload_file' " . 
	        (($can_upload) ? "" : " class=\"disabled\" disabled") . "><br />
    	    <input " . (($can_upload) ? 
            "" : " class=\"disabled\" disabled ") . " type='button' 
	          onClick=\"dir_current.value='" .
	        addslashes ($this->htmlentities ($this->vars['dir_current'])) . 
          "';action_type.value='" . ACTION_MENU_UPLOAD . 
          "';_submit();\" value='Upload' /></td>";
      }

      print "
        <td><input " . (($can_write_some) ? 
          '' : " class=\"disabled\" disabled") . 
        " type='button' 
	        onClick=\"dir_current.value='" .
	      addslashes ($this->htmlentities ($this->vars['dir_current'])) . 
        "';action_type.value='" . ACTION_MENU_DELETE . 
        "';_submit();\" value='Delete' /></td>";
	
      print "</tr></table></p>";
    }

    function _is_symlink ($rights)
    {
      return ($rights{0} == 'l');
    }

    function _is_socket ($rights)
    {
      return ($rights{0} == 's');
    }

    function _can_write_file ($user, $group, $rights)
    {
      return (
        $rights && (
        /* write for all */
        ($rights[8] == 'w' && $rights[9] != 't') ||
      	/* write for group */
      	($rights[5] == 'w' && $this->vars['www_group'] == $group) ||
      	/* write for owner */
      	($rights[2] == 'w' && $this->vars['www_user'] == $user))
      );
    }

    function _can_read_file ($user, $group, $rights)
    {
      return (
        $rights && (
        /* read for all */
        ($rights[7] == 'r') ||
      	/* read for group */
      	($rights[4] == 'r' && $this->vars['www_group'] == $group) ||
      	/* read for owner */
      	($rights[1] == 'r' && $this->vars['www_user'] == $user))
      );
    }

    function _is_directory ($rights)
    {
      return ($rights && $rights[0] == 'd');
    }

    function _is_device ($rights)
    {
      return ($rights && ($rights[0] == 'b' || $rights[0] == 'c'));
    }

    function get_envpath ()
    {
      return implode (':', $this->vars['envpath']);
    }

    function _create_tmpdir ()
    {
      $tmpdir = @getenv ('TMPDIR');
      $current = @getenv ('PWD');
      $name = md5 (microtime ());

      if (!$tmpdir) $tmpdir = '/tmp';

      if (@mkdir ("$tmpdir/.$name"))
        return "$tmpdir/.$name";
      if (@mkdir ("$current/.$name"))
        return "$current/.$name";
      if (@mkdir ("$current/tmp/.$name"))
        return "$current/tmp/.$name";

      return '';
    }

    function command_current_execute ()
    {
      $path_old = @getenv ('PATH');
      $path_new = $this->get_envpath ();

      if ($path_new)
        @putenv ("PATH=$path_new");

      if ($this->php_function_enabled ('popen'))
      {
        if (($fd = @popen ($this->vars['command_current'] . " 2>&1", "r")))
        {
          while (!feof($fd))
            $this->vars['command_current_output'] .= fread ($fd, 1024);
          pclose ($fd);
        }
      }
      elseif (
        $this->execute_enabled () && 
        ($tmpdir = $this->_create_tmpdir ()) != '' &&
        ($fifo = tempnam ($tmpdir, '.'))
      )
      {
        $f = $this->get_execute_function ();
        $f ('(' . $this->vars['command_current'] . 
          " >$fifo) >/dev/null 2>&1 &");

        /* Wait a while */
        for ($i = 0; $i < 300000; $i++);

        if (!($fd = @fopen ($fifo, 'r')))
        {
          @unlink ($fifo);
          @rmdir ($tmpdir);
          return;
        }

        while (true) 
        {
          $out = fread ($fd, 1);
          if (strlen ($out) == 0) break;
          $this->vars['command_current_output'] .= $out;
        }
        fclose ($fd);

        @unlink ($fifo);
        @rmdir ($tmpdir);
      }

      @putenv ("PATH=$path_new");
    }

    function get_command_current_output ()
    {
      return $this->vars['command_current_output'];
    }

    function get_box_posX ($name)
    {
      return $this->vars[$name . '_box_x'];
    }

    function get_box_posY ($name)
    {
      return $this->vars[$name . '_box_y'];
    }

    function get_history_html ()
    {
      $output = "
        <table>
        <tr><th colspan=3 class='caption'>History</th></tr>
        <tr class='header'><th colspan=2>Command</th><th>Action</th></tr>
      ";
      $i = 0;
      foreach ($this->vars['history'] as $row)
      {
        $output .= "
          <tr>
            <td class='num'>" . ($i + 1) . "</td>
            <td nowrap>" . $this->htmlentities ($row) . "</td>
            <td nowrap>
	            <input type='button' title=\"Execute\" value='E' 
	             onClick=\"history_index.value=$i;action_requested.value='" . 
			       SHELL_HISTORY_EXECUTE . "';_submit()\" /> 
	            <input type='button' title=\"Select\" value='S' 
	             onClick=\"command.value='" . 
                addslashes ($this->htmlentities ($row)) . "'\" /> 
	            <input type='button' title=\"Delete\" value='D' 
	             onClick=\"history_index.value=$i;action_requested.value='" .
			       SHELL_HISTORY_DELETE ."';_submit()\" /> 
          </td>
        </tr>
        ";
        $i++;
      }
      $output .= "</table>";

      return $output;
    }
    
    function get_envpath_html ()
    {
      $output = "
        <div id=\"envpath_box\" class=\"box\"
          onClick=\"this.style.zIndex=++zIndex;\"
          style=\"top: " . $this->get_box_posY ('envpath') . 
          ";left: " . $this->get_box_posX ('envpath') . ";visibility: " . 
            $this->get_show_hide ('envpath') . " ;\">
        <table>
        <tr><th colspan=2 
          onMouseOver=\"this.style.cursor='move';\"
          onMouseDown=\"drag_begin('envpath_box')\" 
          onMouseUp=\"drag_end()\" 
        class='caption'>Environment PATH</th>
        <th class=\"win_close\"
          onClick=\"show_hide('envpath', forms[0].envpath_cb)\">X</th></tr>
        <tr class='header'><th colspan=2>Directory</th><th>Action</th></tr>
        <tr><td colspan=2><input type='text' name='envpath_value' value=\"\" />
        </td>
          <td>
  	        <input type='button' value='Add' 
	            onClick=\"action_requested.value='" . SHELL_ENVPATH_ADD .
              "';_submit()\" /> 
          </td></tr>
      ";
      $i = 0;
      foreach ($this->vars['envpath'] as $row)
      {
        $output .= "
          <tr>
            <td class='num'>" . ($i + 1) . "</td>
            <td nowrap>" . $this->htmlentities ($row) . "</td>
            <td nowrap>
	            <input type='button' value='Delete' 
	             onClick=\"envpath_index.value=$i;
		               action_requested.value='" .
			       SHELL_ENVPATH_DELETE
			       ."';_submit()\" /> 
          </td>
        </tr>
        ";
        $i++;
      }
      $output .= "</table></div>";

      return $output;
    }

    function get_file_browser_initpath_html ()
    {
      $output = "
        <div id=\"initpath_box\" class=\"box\"
          onClick=\"this.style.zIndex=++zIndex;\"
          style=\"top: " . $this->get_box_posY ('initpath') . 
          ";left: " . $this->get_box_posX ('initpath') . ";visibility: " . 
            $this->get_show_hide ('initpath') . " ;\">
        <table>
        <tr>
          <th 
          onMouseOver=\"this.style.cursor='move'\"
          onMouseDown=\"drag_begin('initpath_box')\" 
          onMouseUp=\"drag_end()\" 
          class='caption'>Initial Path</th>
          <th class=\"win_close\" 
          onClick=\"show_hide('initpath', forms[0].initpath_cb)\">X</th>
          </tr>
        <tr class='header'><th>Path</th><th>Action</th>
        </tr>
        <tr><td><input type='text' name='initpath_value' value=\"" .
          $this->htmlentities ($this->get_file_browser_initpath ()) . 
          "\" /></td>
          <td>
  	        <input type='button' value='Update' 
	            onClick=\"dir_current.value='" .
	            addslashes ($this->htmlentities ($this->vars['dir_current'])) . 
              "';file_browser_initpath.value=initpath_value.value;_submit()\" /> 
          </td></tr>
      ";
      $output .= "
        </table>
        </div>";

      return $output;
    }

    function get_highlight_html ()
    {
      $output = "
        <div id=\"highlight_box\" class=\"box\"
          onClick=\"this.style.zIndex=++zIndex;\"
          style=\"top: " . $this->get_box_posY ('highlight') . 
          ";left: " . $this->get_box_posX ('highlight') . ";visibility: " . 
            $this->get_show_hide ('highlight') . " ;\">
        <table>
        <tr>
          <th 
          onMouseOver=\"this.style.cursor='move'\"
          onMouseDown=\"drag_begin('highlight_box')\" 
          onMouseUp=\"drag_end()\" 
          class='caption'>PHP Code highlight</th>
          <th class=\"win_close\" 
          onClick=\"show_hide('highlight', forms[0].highlight_cb)\">X</th></tr>
          <tr><td colspan=2>
          " . 
          @highlight_string (
            "<?php\n" . 
              $this->_fix_magic_quotes ($this->get_phpcode_current ()) . 
            "\n?>", true) . " 
        </td></tr></table></div>";

      return $output;
    }

    function get_profiles_html ()
    {
      $output = "
        <div id=\"profiles_box\" class=\"box\"
          onClick=\"this.style.zIndex=++zIndex;\"
          style=\"top: " . $this->get_box_posY ('profiles') . 
          ";left: " . $this->get_box_posX ('profiles') . ";visibility: " . 
            $this->get_show_hide ('profiles') . " ;\">
        <table>
        <tr><th 
          onMouseOver=\"this.style.cursor='move'\"
          onMouseDown=\"drag_begin('profiles_box')\" 
          onMouseUp=\"drag_end()\" 
        colspan=2 class='caption'>Profiles management</th>
        <th class=\"win_close\" 
          onClick=\"show_hide('profiles', forms[0].profiles_cb)\">X</th></tr>
        <tr class='header'><th colspan=2>Name</th><th>Action</th></tr>
      ";

      if (count ($this->vars['profiles']) < EDIT_PROFILES_MAX)
        $output .= "
        <tr><td colspan=2>
        <input type='text' maxlength=\"50\" name='profile_name' value=\"\" />
        </td>
          <td colspan=2>
  	        <input type='button' value='Save' 
	            onClick=\"action_requested.value='" . EDIT_PROFILES_SAVE .
              "';_submit()\" /> 
          </td></tr>
      ";

      $i = 0;
      foreach ($this->vars['profiles'] as $name)
      {
        $output .= "
          <tr>
            <td class='num'>" . ($i + 1) . "</td>
            <td>" . $this->htmlentities ($name) . "</td>
            <td nowrap>
	            <input type='button' title=\"Load\" value='L' 
	              onClick=\"profiles_index.value=$i;profile_current.value='" . 
                  addslashes ($this->htmlentities ($name)). 
                "';action_requested.value='" .
                EDIT_PROFILES_LOAD ."';_submit()\" /> 

	            <input type='button' 
                title=\"Update/Replace with current\" value='U' 
	              onClick=\"profiles_index.value=$i;action_requested.value='" .
                EDIT_PROFILES_UPDATE ."';_submit()\" /> 

	            <input type='button' title=\"Delete\" value='D' 
	              onClick=\"profiles_index.value=$i;action_requested.value='" .
                EDIT_PROFILES_DELETE ."';_submit()\" /> 
          </td>
        </tr>
        ";
        $i++;
      }
      $output .= "
        </table>
        </div>";

      return $output;
    }

    function get_aliases_html ()
    {
      $output = "
        <div id=\"aliases_box\" class=\"box\"
          onClick=\"this.style.zIndex=++zIndex;\"
          style=\"top: " . $this->get_box_posY ('aliases') . 
          ";left: " . $this->get_box_posX ('aliases') . ";visibility: " . 
            $this->get_show_hide ('aliases') . " ;\">
        <table>
        <tr><th 
          onMouseOver=\"this.style.cursor='move'\"
          onMouseDown=\"drag_begin('aliases_box')\" 
          onMouseUp=\"drag_end()\" 
        colspan=3 class='caption'>Aliases</th>
        <th class=\"win_close\" 
          onClick=\"show_hide('aliases', forms[0].aliases_cb)\">X</th></tr>
        <tr class='header'><th colspan=2>Name</th><th>Value</th><th>Action</th>
        </tr>
        <tr><td colspan=2><input size=5 type='text' 
            name='alias_name' value=\"\" /></td>
        <td><input type='text' name='alias_value' value=\"\" /></td>
          <td>
  	        <input type='button' value='Add' 
	            onClick=\"action_requested.value='" . SHELL_ALIASES_ADD .
              "';_submit()\" /> 
          </td></tr>
      ";
      $i = 0;
      foreach ($this->vars['aliases'] as $name => $value)
      {
        $output .= "
          <tr>
            <td class='num'>" . ($i + 1) . "</td>
            <td><b>\$" . $this->htmlentities ($name) . "</b></td>
            <td>" . $this->htmlentities ($value) . "</td>
            <td>
	            <input type='button' value='Delete' 
	              onClick=\"alias_name.value='" . 
                  addslashes ($this->htmlentities ($name)) . 
                  "';action_requested.value='" .
			          SHELL_ALIASES_DELETE ."';_submit()\" /> 
          </td>
        </tr>
        ";
        $i++;
      }
      $output .= "
        </table>
        </div>";

      return $output;
    }

    function get_input_hidden_html ($name, $value)
    {
      return 
        "<input type=\"hidden\" name=\"$name\" value=\"" . 
        $this->htmlentities ($value) . 
        "\" />\n";
    }

    function _get_http_var ($name, $default = '')
    {
      $tmp = '';

      if (isset ($_POST[$name]))
        $tmp = $_POST[$name];

      if (empty ($tmp))
        $tmp = $default;

      return $tmp;
    }

    function _fix_magic_quotes ($str)
    {
      return (ini_get ('magic_quotes_gpc') == 1)  ?
        stripslashes ($str) : $str;
    }

    function utf8_decode ($str)
    {
      /* FIXME Apache child segfault in some cases */
      /*
      if (preg_match (
         '%^(?:
           [\x09\x0A\x0D\x20-\x7E]           # ASCII
         | [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
         |  \xE0[\xA0-\xBF][\x80-\xBF]       # excluding overlongs
         | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
         |  \xED[\x80-\x9F][\x80-\xBF]       # excluding surrogates
         |  \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
         | [\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
         |  \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
         )*$%xs', $str))
      */
      if (preg_match ('/./u', $str))
        $str = utf8_decode ($str);

      return $str;
    }

    function htmlentities ($str)
    {
      $str = $this->utf8_decode ($str);

      return htmlentities ($this->_fix_magic_quotes ($str));
    }

    function is_htmloutput ()
    {
      return ($this->vars['htmloutput'] != '');
    }

    function get_htmloutput_html ()
    {
      return "
        <p>
        <input type='checkbox' name='htmloutput' id='htmloutput' 
               title='This option can bother application look&amp;feel'> 
        <label for='htmloutput' >HMTL output expected</label>
        </p>
      ";
    }

    function done () {}
  }

  $prs = new PhpRemoteShell ($config);
  if ($prs->command_current_exists ())
    $prs->command_current_execute ();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?php echo APP_NAME;?> - <?php echo APP_VERSION;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style>
  body {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
  }
  input, textarea {
    border: 1px black solid;
    background: #98B2D7;
    color: black;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
  }
  input.menu_selected {
    background: white;
  }
  input.file_browser_path {
    padding: 3px;
    border: 1px black solid;
  }
  input.file_browser {
    padding: 0px;
    border: none;
    text-align: left;
  }
  a {
    color: black;
  }
  a:hover {
    color: cornflowerblue;
  }
  table.menu {
    background: cornflowerblue;
  }
  .menu {
    border: 1px yellow solid; 
    color: cornflowerblue;
    background: black;
    padding: 2px;
  }
  .title_file {
    font-weight: bold;
    font-size: 12px;
  }
  .smenu {
    border: 1px yellow solid; 
    color: cornflowerblue;
    background: black;
    border-collapse: collapse;
  }
  .smenu a {
    color: cornflowerblue;
    text-decoration: none;
  }
  .smenu a:hover {
    color: yellow;
  }
  .smenu input:hover {
    color: white;
  }
  div#title {
    background: cornflowerblue;
    border: 1px black solid;	
    padding: 5px;
    text-align: center;
    font-weight: bold;
    font-size: 12px;
  }
  div#title #app_name {
    color: white;
  }
  div#phpcode_output_title {
    text-align: center;
    font-weight: bold;
    font-size: 12px;
  }
  div#phpcode_output {
    background: #004594;
    border: 1px cornflowerblue solid;
    padding: 5px;
    color: white;
  }
  table {
    border: 1px black solid;
  }
  th {
    background: cornflowerblue;
    color: white;
    vertical-align: top;
    border: 1px black solid;
  }
  th.caption {
    background: black;
    color: cornflowerblue;
    vertical-align: top;
    border: 2px cornflowerblue solid;
    text-align: center;
  }
  th.win_close {
    text-align: right;
    background: black;
    color: orange;
    border: 1px orange solid;
  }
  table.action_result {
    border: 1px black solid;
    border-collapse: collapse;
    text-align: center;
  }
  table.action_result th {
    background: cornflowerblue;
    color: white;
    vertical-align: top;
  }
  table.file_browser {
    width: 90%;
    border: 1px black solid;
    border-collapse: collapse;
    text-align: center;
  }
  table.file_browser_legend {
    width: 1%;
    border: 1px black solid;
    border-collapse: collapse;
    text-align: left;
  }
  table.file_browser_menu {
    background: cornflowerblue;
  }
  table.file_browser th {
    background: cornflowerblue;
    color: white;
    vertical-align: top;
  }
  tr.odd {
    background: #004594;
    color: white;
  }
  tr.even {
    background: cornflowerblue;
  }
  table.file_browser_legend td.rights_read {
    background: green;
    border: 1px black solid;
  }
  table.file_browser_legend td.rights_write {
    background: blue;
    border: 1px black solid;
  }
  table.file_browser_legend td.rights_bad {
    background: red;
    border: 1px black solid;
  }
  table.file_browser td.rights_read {
    background: green;
    border: 1px black solid;
  }
  table.file_browser td.rights_write {
    background: blue;
    border: 1px black solid;
  }
  table.file_browser td.rights_bad {
    background: red;
    border: 1px black solid;
  }
  table.file_browser td.name {
    text-align: left;
  }
  tr.header {
    background: cornflowerblue;
    color: white;
  }
  td {
    vertical-align: top;
  }
  td.label {
    background: cornflowerblue;
    font-weight: bold;
    vertical-align: top;
  }
  td.num {
    background: black;
    color: orange;
    border: 1px cornflowerblue solid;
    font-weight: bold;
    vertical-align: middle;
    text-align: center;
    width: 20px;
  }
  pre {
    font-family: monospace, courier;
    background: #004594;
    color: white;
    border: 1px cornflowerblue solid;
    padding: 5px;
  }
  .box {
    background: white;
    top: <?php echo POPUP_DEFAULT_Y;?>px;
    left: <?php echo POPUP_DEFAULT_X;?>px;
    position: absolute;
    overflow: auto;
    visibility: visible;
    z-index: 1;
  }
  div#profile_title {
    text-align: right;
    border: 1px cornflowerblue solid;
    padding: 1px;
  }
  .disabled {
    background: #94AED6;
    color: #CEDFFF;
  }
</style>
<!--[if IE]>
<style>
   pre {
    font-family: courier;
    background: #004594;
    color: white;
    border: 1px cornflowerblue solid;
    padding: 5px;
  }
  code {
    font-family: courier;
  }
</style>
<![endif]-->
<script language="javascript">
  var zIndex = 1;
  var dragging = false;
  var xOffs = 0;
  var yOffs = 0;
  var mouseX = 0;
  var mouseY = 0;
  var currentPopup = null;
  var currentMenu = null;
  var currentOver = null;
  var is_ie = (navigator.appName.indexOf ("Microsoft") >= 0);

  addEvent (document, 'mousemove', _mouseMove);

  function _submit ()
  {
    var item = null;
    var f = document.forms[0];

    item = document.getElementById ('aliases_box');
    f.aliases_box_x.value = item.style.left;
    f.aliases_box_y.value = item.style.top;

    item = document.getElementById ('profiles_box');
    f.profiles_box_x.value = item.style.left;
    f.profiles_box_y.value = item.style.top;

    item = document.getElementById ('envpath_box');
    f.envpath_box_x.value = item.style.left;
    f.envpath_box_y.value = item.style.top;

    item = document.getElementById ('initpath_box');
    f.initpath_box_x.value = item.style.left;
    f.initpath_box_y.value = item.style.top;

    item = document.getElementById ('highlight_box');
    f.highlight_box_x.value = item.style.left;
    f.highlight_box_y.value = item.style.top;

    f.submit ();
  }

  function reset_pos (name)
  {
    item = document.getElementById (name + '_box');
    item.style.left = '<?php echo POPUP_DEFAULT_X;?>px';
    item.style.top = '<?php echo POPUP_DEFAULT_Y;?>px';
  }

  function addEvent (el, evname, func) 
  {
	  if (el.attachEvent) 
  		el.attachEvent ("on" + evname, func);
    else if (el.addEventListener) 
    {
  		el.addEventListener (evname, func, true);
    }
    else 
  		el["on" + evname] = func;
  }

  function removeEvent (el, evname, func)
  {
    if (el.detachEvent)
  		el.detachEvent ("on" + evname, func);
	  else if (el.removeEventListener)
  		el.removeEventListener (evname, func, true);
	  else
  		el["on" + evname] = null;
  }

  function _mouseMove (e)
  {
    if (dragging) return;

    if (document.layers)
    {
      mouseX = e.x;
      mouseY = e.y;
    }
    else if (document.all)
    {
      mouseX = event.clientX;
      mouseY = event.clientY;
    }
    else if (document.getElementById)
    {
      mouseX = e.clientX;
      mouseY = e.clientY;
    }
  }

  function drag_begin (id)
  {
    var posX = 0;
    var posY = 0;
    var item = null;

    currentPopup = id;

    item = document.getElementById (id);

    if (is_ie)
    {
      posX = mouseX + document.body.scrollLeft;
      posY = mouseY + document.body.scrollTop;
    }
    else
    {
      posX = mouseX + window.scrollX;
      posY = mouseY + window.scrollY;
    }

    xOffs = posX - parseInt (item.style.left);
    yOffs = posY - parseInt (item.style.top);

    addEvent (document, 'mousemove', drag_box);

    item.style.cursor = 'move';

    dragging = true;
  }

  function drag_end ()
  {
    var item = null;

    if (!dragging) return;
    dragging = false;

    item = document.getElementById(currentPopup);
    removeEvent (document, 'mousemove', drag_box);
    item.style.cursor = 'default';

    currentPopup = null;
  }

  function drag_box (e)
  {
    var item = document.getElementById (currentPopup);

    if (is_ie)
    {
      item.style.left = 
        (window.event.clientX + document.body.scrollLeft - xOffs) + 'px';
      item.style.top = 
        (window.event.clientY + document.body.scrollTop - yOffs) + 'px';
    }
    else
    {
      item.style.left = (e.pageX - xOffs) + 'px';
      item.style.top = (e.pageY - yOffs) + 'px';
    }
  }

  function menu_show (name)
  {
    var item = null;

    if ( !(item = document.getElementById (name)) )
      return;

    menu_hide (currentMenu);
    currentMenu = name;

    item.style.visibility = 'visible';
  }

  function menu_hide (name)
  {
    var item = null;

    if ( !(item = document.getElementById (name)) )
      return;

    item.style.visibility = 'hidden';
  }

  function show_hide (id, i)
  {
    var item = null;
    var state = null;

    if ( !(item = document.getElementById (id + '_box')) )
      return;

    state = item.style.visibility;

    if (state == 'hidden')
    {
      item.style.zIndex = ++zIndex;
      item.style.visibility = 'visible';
      eval ("document.forms[0].show_hide_" + id + ".value = 'visible'");
      i.checked = true;
    }
    else
    {
      item.style.visibility = 'hidden';
      eval ("document.forms[0].show_hide_" + id + ".value = 'hidden'");
      i.checked = false;
      reset_pos (id);
    }
  }

  function menu_hide_async (name)
  {
    setTimeout(
      "{if (currentOver != '" + name + 
      "') document.getElementById('" + name + 
      "').style.visibility = 'hidden';}", 100)
  }
</script>
</head>
<body onClick="drag_end()">

<div id="title">
  Welcome to <span id="app_name"><?php echo APP_NAME;?></span> 
  <?php echo APP_VERSION;?>
</div>
<br />
<div id="profile_title">
Current profile: <b><?php echo ($prs->get_profile_current ()) ? $prs->htmlentities ($prs->get_profile_current ()) : 'None';?></b>
</div>

<p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" 
  enctype="multipart/form-data">
<?php
  foreach (array (
    'show_hide_aliases' => $prs->get_show_hide ('aliases'),
    'show_hide_profiles' => $prs->get_show_hide ('profiles'),
    'show_hide_envpath' => $prs->get_show_hide ('envpath'),
    'show_hide_initpath' => $prs->get_show_hide ('initpath'),
    'show_hide_highlight' => $prs->get_show_hide ('highlight'),
    'is_nav' => 0,
    'action_type' => '',
    'display_type' => $prs->get_display_type (),
    'history_index' => 0,
    'profiles_index' => 0,
    'envpath_index' => 0,
    'dir_current' => $prs->get_dir_current (),
    'profile_current' => $prs->get_profile_current (),
    'file_current_rights' => $prs->get_file_current_rights (),
    'file_browser_initpath' => $prs->get_file_browser_initpath (),
    'action_requested' => '',
    'history' => $prs->form_get_serialize ('history'),
    'aliases' => $prs->form_get_serialize ('aliases'),
    'profiles' => $prs->form_get_serialize ('profiles'),
    'envpath' => $prs->form_get_serialize ('envpath'),
    'envpath_box_x' => $prs->get_box_posX ('envpath'),
    'envpath_box_y' => $prs->get_box_posY ('envpath'),
    'initpath_box_x' => $prs->get_box_posX ('initpath'),
    'initpath_box_y' => $prs->get_box_posY ('initpath'),
    'highlight_box_x' => $prs->get_box_posX ('highlight'),
    'highlight_box_y' => $prs->get_box_posY ('highlight'),
    'aliases_box_x' => $prs->get_box_posX ('aliases'),
    'aliases_box_y' => $prs->get_box_posY ('aliases'),
    'profiles_box_x' => $prs->get_box_posX ('profiles'),
    'profiles_box_y' => $prs->get_box_posY ('profiles') 
  ) as $n => $v)
    print $prs->get_input_hidden_html ($n, $v);

  /* Main menu */
  print $prs->get_menu_html ();
  
  if ($tmp = $prs->get_action_result_html ())
    print $tmp;
  
  /* Aliases table */
  print $prs->get_aliases_html ();

  /* Profiles table */
  print $prs->get_profiles_html ();

  /* Env PATH table */
  print $prs->get_envpath_html ();

  /* File browser initial path */
  print $prs->get_file_browser_initpath_html ();

  /* PHP code highlight */
  print $prs->get_highlight_html ();

  switch ($prs->get_display_type ())
  {
    /* 
     * NOTEBOOK: Remote informations 
     */
    case SHELL_TYPE_REMOTE_INFOS:
      if (ini_get ('safe_mode'))
        printf ("<p>%s</p>", $prs->get_safe_mode_alert_html ('all'));
      elseif (!$prs->execute_enabled ())
        printf ("<p>%s</p>", $prs->get_php_function_alert_html ());
      else
        printf ("<p>%s</p>", $prs->get_remote_infos_html ());
      break;
      
    /* 
     * NOTEBOOK: Shell code
     */
    case SHELL_TYPE_SHELL:
      if (ini_get ('safe_mode'))
        printf ("<p>%s</p>", $prs->get_safe_mode_alert_html ('all'));
      elseif (!$prs->execute_enabled ())
        printf ("<p>%s</p>", $prs->get_php_function_alert_html ());
      else
      {
        printf ("
          <p>%s</p>
          <p>
          New shell command to execute:
          <p>
          <input type='text' name='command' value=\"%s\" />
          </p>
          </p>
          <p><input type='button' onClick=\"
            action_requested.value='" . SHELL_EXECUTE . "';
            _submit()\" value='Execute' /></p>",
          $prs->get_htmloutput_html (),
          $prs->htmlentities ($prs->get_command_current ())
        );

        /* Commands History table */
        if ($prs->history_exists ())
          printf ("<td>%s</td>", $prs->get_history_html ());

       /* Last executed command */
        if ($prs->command_current_exists ()) 
          printf ("<p>Last executed command: <p><pre>%s</pre></p></p>", 
            $prs->htmlentities ($prs->get_command_current ()));

        if ($prs->command_current_exists ()) 
        {
          print "<p>Output: </p>";

          if ($prs->is_htmloutput ())
          {
            print "</form></body></html>\n";
            print $prs->get_command_current_output ();
            exit;
          }
          else
            printf ("<pre>%s</pre></p></p>",
              $prs->htmlentities ($prs->get_command_current_output ()));
        }
      }
      break;
      
    /* 
     * NOTEBOOK: PHP code execution 
     */
    case SHELL_TYPE_PHP_CODE:

      if (ini_get ('safe_mode'))
        printf ("<p>%s</p>", $prs->get_safe_mode_alert_html ('some'));

      printf ("
        <p>%s</p>
        <p>
          New PHP code to execute:
          <p><input type='button' onClick=\"_submit()\" value='Execute' /></p>
          <p>
            <textarea name='phpcode_current' cols='80' rows='10'>%s</textarea>
          </p>
        </p>
        <p><input type='button' onClick=\"_submit()\" value='Execute' /></p>",
        $prs->get_htmloutput_html (),
        $prs->htmlentities ($prs->get_phpcode_current ())
      );

      if ($prs->phpcode_current_exists ())
      {
        ob_start ();
        if (@eval ($prs->_fix_magic_quotes (
            $prs->get_phpcode_current ())) === false)
          $output = "A error occured while executing PHP code.";
        else
          $output = ob_get_contents();
        ob_end_clean ();

        print "<div id=\"phpcode_output_title\">PHP Result:</div>";

        if ($prs->is_htmloutput ())
        {
          print "</form></body></html>\n";
          print $output;
          exit;
        }
        else
          print "<p><div id=\"phpcode_output\">" .
            $prs->htmlentities ($output) . "</div></p>";
      }
      break;
      
    /*
     * NOTEBOOK: File browser
     */
    case SHELL_TYPE_FILE_BROWSER:

      if (!$prs->execute_enabled () && !$prs->browse_enabled ())
      {
        if (ini_get ('safe_mode'))
          printf ("<p>%s</p>", $prs->get_safe_mode_alert_html ('all'));  
        else
          printf ("<p>%s</p>", $prs->get_php_function_alert_html ());
      }
      else
      {
        if (ini_get ('safe_mode'))
          printf ("<p>%s</p>", $prs->get_safe_mode_alert_html ('some'));
        elseif (!$prs->execute_enabled ())
          printf ("<p>%s</p>", 
            $prs->get_php_function_alert_html ('some'));

        print "
          <p>
          <table class='file_browser_legend' align='left'>
        	<tr><th class='caption' colspan='6'>Legend</th></tr>
        	<tr>
          <td width='2%' class='rights_write'>&nbsp;</td>
          <td>Read/write</td>
          <td width='2%' class='rights_read'>&nbsp;</td>
          <td>Read</td>
          <td width='2%' class='rights_bad'>&nbsp;</td>
          <td>Nothing</td>
          </tr>
          </table>
          </p>
        ";
        print "<br /><br /><br /><p>"; 
        $prs->get_browse_dir ();
        print "</p>";
      }
      break;
  
    /* 
     * NOTEBOOK: About 
     */
    //case SHELL_TYPE_ABOUT:
    default: ;
      print 
        '<a href="http://phpremoteshell.labs.libre-entreprise.org/"
	    target="_BLANK">Project Homepage</a><p />' .
        '<pre>' .
        $prs->htmlentities ('
/*
 * Copyright (C) 2005-2006
 * Emmanuel Saracco <esaracco@users.labs.libre-entreprise.org>;
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330,
 * Boston, MA 02111-1307, USA.
 */
      ') .
      '</pre>';
      break;
  }
 ?>
</form>
</p>

</body>
</html>
<?php  
  $prs->done ();
?>
