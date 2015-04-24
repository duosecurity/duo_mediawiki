<?php
class SpecialDuoAuth extends SpecialPage {
  var $success = false;

  function __construct() {
    parent::__construct( 'DuoAuth' );
  }

  function getName() {
    "Duo Authentication";
  }

  function execute( $par ) {
    global $wgUser, $mediaWiki, $wgRequest, $wgOut, $wgDuoIKey, $wgDuoSKey, $wgDuoHost, $IP, $wgServer, $wgScriptPath, $wgScript, $wgSecretKey;

    $this->setHeaders();
    require_once("$IP/extensions/DuoAuth/duo_web.php");

    if ($wgUser->isLoggedIn()) {
        $username = $wgUser->getName();
        $uid = $wgUser->getId();
        $duo_token = Duo::signRequest($wgDuoIKey, $wgDuoSKey, $wgSecretKey, $wgUser->getName());
        $wgOut->addHtml('
<script src="'. $wgServer . $wgScriptPath . '/extensions/DuoAuth/Duo-Web-v1.js"></script>
<script>
  Duo.init({
    \'host\':\'' . $wgDuoHost . '\',
    \'post_action\':\'\',
    \'sig_request\':\'' . $duo_token . '\'
  });
</script>
<iframe id="duo_iframe" style="width:100%;height:500px" frameborder="0"></iframe>
        ');
      $wgUser->logout();
      $_SESSION['du'] = $username;
      $_SESSION['id'] = $uid;
    } else if (isset($_POST["sig_response"]) && !empty($_POST["sig_response"])) {
      $duo_user = Duo::verifyResponse($wgDuoIKey, $wgDuoSKey, $wgSecretKey, $_POST["sig_response"]);
      if ($duo_user == $_SESSION['du']) {
        # TODO: should be able to do this with $wgUser->getIdFromName($_SESSION['du'])
        $wgUser->setId($_SESSION['id']);
        $wgUser->loadFromId();
        $wgUser->setCookies();
        $wgOut->redirect("$wgScript/Main Page");
      } else {
        $mediaWiki->restInPeace();
      }
    } else {
      $wgOut->addWikiText("You must login to see this page.");
    }
  }
}
?>
