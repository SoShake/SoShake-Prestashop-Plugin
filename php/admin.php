<?php
/*
Plugin Name: SoShake by Up 2 Social
Plugin URI: http://soshake.com/
Description: easy social plugins integration.
Version: 2.1
Author: Up 2 Social
Author URI: http://up2social.com
*/

if(isset($_POST["soshake"])) {
        Db::getInstance()->Execute("TRUNCATE TABLE soshake");
        foreach($_POST as $var => $value) {
                Db::getInstance()->Execute("INSERT INTO soshake SET soshake.value='".mysql_real_escape_string(serialize($value))."', soshake.var='".mysql_real_escape_string($var)."'");
        }
        ?><div class="warn">Informations enregistrées</div><?php
}
$soshake = array(
        "soshake"       => array(),
        "align"         => "",
        "position"      => "",
        "text-intro"    => "",
        "fanbox"        => "",
        "fanpage"       => "",
        "fbconnect"     => "",
);
$r = Db::getInstance()->Execute("SELECT soshake.var, soshake.value FROM soshake");
while(list($var, $value) = mysql_fetch_row($r)) {
        $soshake[$var] = unserialize($value);
        if($var == "soshake" && !is_array($soshake["soshake"])) {
                $soshake["soshake"] = array();
                $soshake["soshake"][0] = unserialize($value);
        }
}
?>
<style>
        label {float:none;font-weight:normal;}
</style>
<script type="text/javascript" src="http://soshake.com/api/actions/cms/prestashop.js"></script>
<?php  $client = json_decode(file_get_contents("http://soshake.com/api/me.json?url="._PS_BASE_URL_)); if($client->code != 200 ) { ?><div class="error" style="">Vous n'avez pas créé de compte sur le service SoShake. Cela n'aura pas d'incidence sur le fonctionnement du module ou de nos widget, mais vous passez à côté de la puissance que vous apporte SoShake ! <a href="http://soshake.com" target="_blank" class="button">Faites le gratuitement en cliquant ici</a></div><?php } ?>
<form method="post" action="" id="up2-form-hook">
        <h1>Etape 1 - Quel boutons installer</h1>
        <h2>Quels boutons afficher</h2>
        <p>
                <input type="checkbox" name="soshake[]" value="like" id="up2-box-0" <?php if(in_array("like", $soshake["soshake"])) echo "checked"; ?> /> <label for="up2-box-0">Bouton Like</label><br />
                <input type="checkbox" name="soshake[]" value="tweet" id="up2-box-1" <?php if(in_array("tweet", $soshake["soshake"])) echo "checked"; ?> /> <label for="up2-box-1">Bouton Tweet</label><br />
                <input type="checkbox" name="soshake[]" value="gplus" id="up2-box-2" <?php if(in_array("gplus", $soshake["soshake"])) echo "checked"; ?> /> <label for="up2-box-2">Bouton +1</label><br />
                <input type="checkbox" name="soshake[]" value="linkedin" id="up2-box-3" <?php if(in_array("linkedin", $soshake["soshake"])) echo "checked"; ?> /> <label for="up2-box-3">Bouton LinkedIn</label><br />
                <input type="checkbox" name="soshake[]" value="pinit" id="up2-box-4" <?php if(in_array("pinit", $soshake["soshake"])) echo "checked"; ?> /> <label for="up2-box-4">Bouton Pinterest</label><br />
        </p>
        <h2>Position de vos SoShake</h2>
        <p>
                <input type="radio" name="column" value="left" <?php if($soshake["column"] == "left") echo "checked"; ?> id="up2-col-l" /> <label for="up2-col-l">Sous la photo</label><br />
                <input type="radio" name="column" value="right" <?php if($soshake["column"] == "right") echo "checked"; ?> id="up2-col-r" /> <label for="up2-col-r">Sous les actions produits</label><br />
        </p>
        <h2>Alignement de vos SoShake</h2>
        <p>
                <input type="radio" name="align" value="vertical" <?php if($soshake["align"] == "vertical") echo "checked"; ?> id="up2-ali-v" /> <label for="up2-ali-v">Vertical (les boutons de partages seront les uns au dessus des autres)</label><br />
                <input type="radio" name="align" value="horizontal" <?php if($soshake["align"] == "horizontal") echo "checked"; ?> id="up2-ali-h" /> <label for="up2-ali-h">Horizontal (les boutons de partages seront les uns à côté des autres)</label><br />
        </p>
        <h2>Disposition de vos SoShake</h2>
        <p>
                <input type="radio" name="position" value="left" <?php if($soshake["position"] == "left") echo "checked"; ?> id="up2-pos-l" /> <label for="up2-pos-l">Gauche (les boutons de partages seront placés sur la gauche)</label><br />
                <input type="radio" name="position" value="center" <?php if($soshake["position"] == "center") echo "checked"; ?> id="up2-pos-c" /> <label for="up2-pos-c">Centre (les boutons de partages seront placés au centre)</label><br />
                <input type="radio" name="position" value="right" <?php if($soshake["position"] == "right") echo "checked"; ?> id="up2-pos-r" /> <label for="up2-pos-r">Droite (les boutons de partages seront placés à droite)</label><br />
        </p>
        <h1>Etape 2 - Définir la phrase de remerciement</h1>
        <h2>Texte introduisant les produits liés</h2>
        <p>
                <input type="text" name="text-intro" value="<?php echo $soshake["text-intro"]; ?>" style="padding:3px;font-size:12pt;width:400px;" /> <br />
        </p>
        <h1>Etape 3 - Afficher une Fan box dans une colonne</h1>
        <p>
                <input type="radio" name="fanbox" value="1" id="up2-fanbox-1" <?php if($soshake["fanbox"] == "1") echo "checked"; ?> /> <label for="up2-fanbox-1">Oui</label><br />
                <input type="radio" name="fanbox" value="0" id="up2-fanbox-0" <?php if($soshake["fanbox"] == "0") echo "checked"; ?> /> <label for="up2-fanbox-0">Non</label><br />
        </p>
        <h2>Adresse de votre page Facebook</h2>
        <p>
                <input type="text" name="fanpage" value="<?php echo $soshake["fanpage"]; ?>" style="padding:3px;font-size:12pt;width:400px;" /> <br />
        </p>
        <p class="warn">Pour définir dans quelle colonne et a quelle position afficher votre fanbox, rendez-vous dans le menu "Position" de Prestashop</p>
        <h1>Etape 4 - Afficher un Facebook Connect dans l'entête de votre boutique</h1>
        <p class="warn">Le Facebook Connect permet à vos visiteurs de créer un compte dans votre boutique en utilisant les données de leur compte Facebook. La création du compte se fait avec un simple clic et permet de passer le remplissage d'un formulaire</p>
        <p>
                <input type="radio" name="fbconnect" value="1" id="up2-fbconnect-1" <?php if($soshake["fbconnect"] == "1") echo "checked"; ?> /> <label for="up2-fbconnect-1">Oui</label><br />
                <input type="radio" name="fbconnect" value="0" id="up2-fbconnect-0" <?php if($soshake["fbconnect"] == "0") echo "checked"; ?> /> <label for="up2-fbconnect-0">Non</label><br />
        </p>
        <h1>Etape 5 - Valider ! </h1>
        <p>
                <input type="submit" name="submit" class="up2-submit" value="Enregistrer cette configuration" />
        </p>
</form>
<div style="margin-top:30px;">
        <h1>Faits plus avec SoShake et configurer les actions à afficher à chaque partage !</h1>
        <p>
                Pour gérer les actions affichées suite à un partage vous devez vous rendre dans la categorie "Actions" sur le site SoShake: <br />
                <br />
                <a href="http://soshake.com/front" target="_blank" class="button">Accéder à mon compte sur SoShake.com</a>
        </p>
        <h1>Suivez nous pour découvrir nos dernières innovations</h1>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=201415856629259";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        <div class="fb-like-box" data-href="http://www.facebook.com/SoShake" data-width="292" data-show-faces="true" data-stream="false" data-header="false" style="float:left;margin-right:50px;"></div>
        
        <a href="https://twitter.com/soshake" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @soshake</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        <div style="clear:both"></div>


<div class='up2-content' id='copycode' style="display:none;">
        <h1>Insertion manuelle des SoShake</h1>
        <p>Vous pouvez insérer les SoShakes n'importe où dans vos pages, vous même. Soit en utilisant l'installeur ci dessus, ou en insérant vous même nos balises à l'endroit souhaité dans vos pages</p>
        <p>Pour insérer le bouton <b>"Like" de Facebook</b>, copiez le code suivant à l'endroit où vous souhaitez l'afficher : <br />
        <input type='text' value='<?php echo htmlentities("<div class=\"up2-like\" categorie=\"\" url=\"\"></div>"); ?>' onClick="select();" /></p>
        <p>Pour insérer le bouton <b>"Tweet" de Twitter</b>, copiez le code suivant à l'endroit où vous souhaitez l'afficher : <br />
        <input type='text' value='<?php echo htmlentities("<div class=\"up2-tweet\" categorie=\"\" url=\"\"></div>"); ?>' onClick="select();" /></p>
        <p>Pour insérer le bouton <b>"+1" de Google</b>, copiez le code suivant à l'endroit où vous souhaitez l'afficher : <br />
        <input type='text' value='<?php echo htmlentities("<div class=\"up2-gplus\" categorie=\"\" url=\"\"></div>"); ?>' onClick="select();" /></p>
        <p>Pour insérer le bouton <b>"Share" de LinkedIn</b>, copiez le code suivant à l'endroit où vous souhaitez l'afficher : <br />
        <input type='text' value='<?php echo htmlentities("<div class=\"up2-linkedin\" categorie=\"\" url=\"\"></div>"); ?>' onClick="select();" /></p>
        <p>&nbsp;</p>
        <p>
        Pour insérer une <b>FanBox d'une page Facebook</b>, copiez le code suivant à l'endroit où vous souhaitez l'afficher : <br />
        <input type='text' value='<?php echo htmlentities("<div class=\"up2-fanbox\"></div>"); ?>' onClick="select();" /><br /><br />
        La page Facebook affichée est celle que vous avez définie dans votre compte Up 2 Social.<br />
        Pour afficher la Fanbox il vous suffit de modifier ce code de la façon suivante : <br />
        <code><?php echo htmlentities("<div class=\"up2-fanbox\" url=\"ADRESSE_DE_VOTRE_PAGE_FACEBOOK\"></div>"); ?></code>
        </p>
        <p>&nbsp;</p>
        <p>Pour insérer le <b>Facebook Connect</b> et permettre à vos visiteurs de se créer un compte d'un clic avec leur compte Facebook, copiez le code suivant à l'endroit où vous souhaitez l'afficher : <br />
        <input type='text' value='<?php echo htmlentities("<div class=\"up2-fbconnect\" url=\"{$base_dir}\"></div>"); ?>' onClick="select();" /></p>
</div>

<div class='up2-content' id='actions' style="display:none;">
        <p>
                Pour gérer les actions affichées suite à un partage vous devez vous rendre dans la categorie "Actions" sur le site Up 2 Social : <br />
                <br />
                <a href="http://up2social.com/front" target="_blank" class="button">Accéder à mon compte sur Up 2 Social</a>
        </p>
</div>
