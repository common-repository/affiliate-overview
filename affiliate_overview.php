<?php
/*
Plugin Name: Affiliate Overview
Plugin URI: http://rosch24.de/affiliate-overview
Description: Konto&uuml;bersicht f&uuml;r affilinet-Publisher.
Version: 1.0
Author: Sebastian Roschitzki
Author URL: http://rosch24.de
*/
include('affiliate_overview_affilinet.php');
add_action('activity_box_end', 'wpaffilinet');
wp_enqueue_style( 'overview_css', plugins_url( $path = '/affiliate-overview/style.css'), array() );

//ERRORS
$phpVersionError = 'Tut mir leid, Du brauchst mindestens <b>PHP5</b> um dieses Plugin verweden zu k&ouml;nnen. Deine Version ist: <b>'.phpversion().'</b>';
$noWsPwError = 'Du musst erst deine PublisherID und dein Webservicepasswort eintragen um dieses Plugin verwenden zu k&ouml;nnen.';
$wrongWsPwError = 'Deine Kontoinformationen konnten nicht geladen werden. Bist Du sicher, dass die eingegebenen Daten stimmen?';

//FUNCTIONS
function gerDate($value) 
{
  $year = substr($value, 0, 4);
  $month = substr($value, 5, 2);
  $day = substr($value, 8, 2);
  if($year > 2000)
  {
  $value = $day.'.'.$month.'.'.$year;
  }
  else
  {
  $value = "noch keine&nbsp;";
  }
  return $value;
}

function money($value) 
{
  if($value < 1)
  {
    $value = " - ";
  }
  $value = str_replace('.',',',$value);
  $value = $value.' &euro;';
  return $value;
}

function error($value) 
{
  $value= '<div style="display:block;background-color:#ffdbdb;border:dotted 1px red;text-align:center;padding-top:10px;padding-bottom:10px">'.$value.'</div>';
  return $value;
}
  
function wpaffilinet() 
{
  global $phpVersionError;
  global $noWsPwError;
  global $wrongWsPwError;
  echo '<hr style="display:none;" />';
  echo '<h2>Affiliate-Overview</h2>';

  if (phpversion() < 5)
  {    
     echo error($phpVersionError);
  }
  else
  {
    if(current_user_can('level_10')) 
    { 
      if(get_option("affilinetPubID")!="" && get_option("affilinetPubWsPw")!="")
      {
      try
         {      
            printAffilinetOverview();
         }
      catch(Exception $e)
         {
            echo error($wrongWsPwError);
         }
      }
      else
      {
         echo error($noWsPwError);
      }      
    }
  }
}


//ADMIN-PAGE
  $affilinetPubID = get_option('affilinetPubID');
  $affilinetPubID = get_option('affilinetPubWsPw');
 
  if ('insert' == $HTTP_POST_VARS['action'])
  {
   	update_option("affilinetPubID",$HTTP_POST_VARS['affilinetPubID']);
    update_option("affilinetPubWsPw",$HTTP_POST_VARS['affilinetPubWsPw']);
  }
  
function affiliate_overview_option_page() 
{
?>   
  	<div class="wrap">
  	  <h2>Affiliate-Overview Einstellungen</h2>
      	Um auf dein affilinet Konto zugreifen zu k&ouml;nnen musst Du deine PublisherID und dein Webservicepasswort eingeben.<br/>
        Das Webservicepasswort kannst Du im Loginbereich (<a href="http://publisher.affili.net" target="_blank" title="publisher.affili.net"><b>publisher.affili.net</b></a>) unter <b>Konto->Technische Einstellungen->Webservices->Publisher Webservices</b> generieren.<br/><br/>
        <form name="form1" method="post" action="<?=$location ?>">
          <table>
            <tr><td>PublisherID:</td><td><input name="affilinetPubID" value="<?=get_option("affilinetPubID");?>" type="text" /></td></tr>
            <tr><td>Webservice-Passwort:</td><td><input name="affilinetPubWsPw" value="<?=get_option("affilinetPubWsPw");?>" type="text" /></td></tr>
            <tr><td colspan="2"><input type="submit" value="Speichern" /></td></tr>
          </table>
  	  	      <input name="action" value="insert" type="hidden" />
  	    </form>
<?php if(isset($_POST['action'])) echo '<h4>&Auml;nderungen gespeichert</h4>';?>
    </div>
<?php
}
   
function affiliate_overview_menu() 
{
  add_option("affilinetPubWsPw","");
  add_option("affilinetPubID","");
	add_options_page('Affiliate-Overview', 'Affiliate-Overview', 9, __FILE__, 'affiliate_overview_option_page');
}
  
  add_action('admin_menu', 'affiliate_overview_menu');

?>