<?php
class soshake extends Module {
        
        public $data;
        public $availableHooks;
        public $product;
        public $category;
        
        public function __construct() {
                $this->name = 'soshake';
		$this->tab = 'front_office_features';
                $this->version = 2.1;
                
                parent::__construct();
                
                $this->displayName = $this->l('SoShake');
                $this->description = $this->l('Retrouvez toutes les solutions SoShake dédiées aux réseaux sociaux conçues par Up 2 Social');
                
                $this -> features = array(
                        array("feature" => "like",      "nom" => "Button Like",         "tpl" => "likeButton"),
                        array("feature" => "tweet",     "nom" => "Button Twitter",      "tpl" => "tweetButton"),
                        array("feature" => "gplus",     "nom" => "Button Google +1",    "tpl" => "gplusButton"),
                        array("feature" => "linkedin",  "nom" => "Button LinkedIn",     "tpl" => "linkedinButton"),
                        array("feature" => "fanbox",    "nom" => "Facebook Fan Box",    "tpl" => "fanBox"),
                        array("feature" => "fbconnect", "nom" => "Facebook Connect",    "tpl" => "fbConnect"),
                );
                $this -> availableHooks = array(
                        array("hook" => "top", "lieu" => "l'en-tête de la page"),
                        array("hook" => "leftColumn", "lieu" => "colonne de gauche"),
                        array("hook" => "rightColumn", "lieu" => "colonne de droite"),
                        array("hook" => "footer", "lieu" => " pied de page"),
                        array("hook" => "home", "lieu" => "centre de la page d'accueil"),
                        array("hook" => "extraLeft", "lieu" => "au-dessus du lien 'Imprimer', sous la photo"),
                        array("hook" => "extraRight", "lieu" => "en dessous du bloc contenant le bouton 'Ajouter au panier'"),
                        array("hook" => "productActions", "lieu" => "intérieur du bloc contenant le bouton 'Ajouter au panier', sous ce bouton"),
                        array("hook" => "productFooter", "lieu" => "au-dessus des onglets"),
                );
                
                $this->data = array(
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
                        $this->data[$var] = unserialize($value);
                        if($var == "soshake" && !is_array($this->data["soshake"])) {
                                $this->data["soshake"] = array();
                                $this->data["soshake"][0] = unserialize($value);
                        }
                }
                
                $u2s_id_product = intval(Tools::getValue('id_product'));
                $this -> product = new Product($u2s_id_product);
                $id_categorie = $this -> product -> id_category_default;
                $this -> category = new Category($id_categorie);
                $this->registerHook('header');
                $this->registerHook('cart');
                $this->registerHook('orderConfirmation');
                $this->registerHook('extraLeft');
                $this->registerHook('extraRight');
                $this->registerHook('leftColumn');
                $this->registerHook('rightColumn');
                $this->registerHook('top');
        }
        
        public function install() {
                if(parent::install() == false) return false;
                
                $db = Db::getInstance();
                $q = "CREATE TABLE `soshake` (`ID` INT NOT NULL AUTO_INCREMENT , `var` VARCHAR( 250 ) NOT NULL , `value` LONGTEXT NOT NULL , PRIMARY KEY (  `ID` ) );";
                $r1 = $db->Execute($q);
                
                return $this->registerHook('footer');
                
        }
        
        public function uninstall()
        {
                Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'block_cms` WHERE `id_block` ='.intval($this->id));
                Db::getInstance()->Execute('DROP TABLE soshake');
                parent::uninstall();
        }
        
        public function getContent () {
                // Instructions de la page de configuration...
                global $smarty;
                //Formulaire soumis
                if(!defined(__DIR__)) { define("__DIR__", dirname( realpath(__FILE__))); }
                
                require(__DIR__."/php/admin.php");
        }
        
        public function share($side) {
                global $smarty;
                if($side == $this->data["column"]) {
                        $smarty -> assign('u2sFeature', "shares");
                        $smarty -> assign('featuresToDisplay', $this->data["soshake"]);
                        $smarty -> assign('u2sCategory', $this -> category -> name[1]);
                        $smarty -> assign('u2sProduct', $this -> product -> name[1]);
                        $smarty -> assign ('u2sAlign', $this->data["align"]);
                        $smarty -> assign('u2sPosition', $this->data["position"]);
                        return $this -> display(__FILE__, 'features.tpl');
                }
        }
        
        public function fanbox() {
                global $smarty;
                $smarty -> assign('u2sFeature', "fanbox");
                $smarty -> assign('u2sFanPage', $this->data["fanpage"]);
                return $this -> display(__FILE__, 'features.tpl');
        }
        
        public function fbconnect() {
                global $smarty;
                $smarty -> assign('u2sFeature', "fbconnect");
                return $this -> display(__FILE__, 'features.tpl');
        }
        
        //Points d'accroches
        public function hookLeftColumn($params) {
                global $smarty;
                if($this->data["fanbox"] == 1) return $this->fanbox();
        }
        public function hookRightColumn($params)
        {
                global $smarty;
                if($this->data["fanbox"] == 1) return $this->fanbox();
        }
        public function hookTop($params)
        {
                global $smarty;
                if($this->data["fbconnect"] == 1) return $this->fbconnect();
        }
        public function hookHome($params) {}
        public function hookFooter($params) {
                global $smarty, $cookie, $link;
                $smarty -> assign('up2_intro', $this->data["text-intro"]);
                
                if(count($this->data["soshake"]) > 0 ) {
                        $up2Bu = "true";
                        
                        // Recherche de Cross Sell
                        if($this->product->id > 0) {
                                $orders = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
                                SELECT o.id_order
                                FROM '._DB_PREFIX_.'orders o
                                LEFT JOIN '._DB_PREFIX_.'order_detail od ON (od.id_order = o.id_order)
                                WHERE o.valid = 1 AND od.product_id = '.$this->product->id);
                                if (sizeof($orders))
                                {
                                        $list = '';
                                        foreach ($orders AS $order)
                                                $list .= (int)$order['id_order'].',';
                                        $list = rtrim($list, ',');
                                        
                                        $orderProducts = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
                                        SELECT DISTINCT od.product_id, pl.name, pl.link_rewrite, p.reference, i.id_image, p.show_price, cl.link_rewrite category, p.ean13
                                        FROM '._DB_PREFIX_.'order_detail od
                                        LEFT JOIN '._DB_PREFIX_.'product p ON (p.id_product = od.product_id)
                                        LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (pl.id_product = od.product_id)
                                        LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (cl.id_category = p.id_category_default)
                                        LEFT JOIN '._DB_PREFIX_.'image i ON (i.id_product = od.product_id)
                                        WHERE od.id_order IN ('.$list.') AND pl.id_lang = '.(int)($cookie->id_lang).' AND cl.id_lang = '.(int)($cookie->id_lang).' 
                                        AND od.product_id != '.$this->product->id.' AND i.cover = 1 AND p.active = 1
                                        ORDER BY RAND()
                                        LIMIT 5');
                                        
                                        $taxCalc = Product::getTaxCalculationMethod();
                                        foreach ($orderProducts AS &$orderProduct) { $orderProduct = new Product($orderProduct['product_id']); }
                                        $crossSellProducts = $orderProducts;
                                } else {
                                        // Si pas de cross Sell categorie similaire
                                        /* If the visitor has came to this product by a category, use this one */
                                        if (isset($params['category']->id_category))
                                                $this->category = $params['category'];
                                        if (!Validate::isLoadedObject($this->category) OR !$this->category->active) 
                                                return;
                                        // Get infos
                                        $categoryProducts = $this->category->getProducts((int)($cookie->id_lang), 1, 5); /* 100 products max. */
                                        
                                        // Remove current product from the list
                                        if (is_array($categoryProducts) AND sizeof($categoryProducts))
                                        {
                                                foreach ($categoryProducts AS $key => $categoryProduct)
                                                        if ($categoryProduct['id_product'] == $this->product->id) {
                                                                unset($categoryProducts[$key]);
                                                        } else {
                                                                $categoryProducts[$key] = new Product($categoryProduct['id_product']);
                                                        }
                                        }
                                        $crossSellProducts = $categoryProducts;
                                }
                                
                        } else {
                                $crossSellProducts = array();
                        }
                        
                        $smarty -> assign('up2CrossSellProducts', $crossSellProducts);
                        $smarty -> assign('nbCrossSellProducts', count($crossSellProducts));
                }
                else $up2Bu = "false";
                
                if($this->data["fanbox"] == 1) $up2FB = "true";
                else $up2FB = "false";
                
                if($this->data["fbconnect"] == 1) $up2FBC = "true";
                else $up2FBC = "false";
                
                
                $smarty -> assign('cookie', $cookie);
                $smarty -> assign('up2FB', $up2FB);
                $smarty -> assign('up2FBC', $up2FBC);
                $smarty -> assign('up2Bu', $up2Bu);
                return $this->display(__FILE__, 'script.tpl');
        }
        public function hookHeader($params) {
                global $smarty;
                $client = json_decode(file_get_contents("http://soshake.com/api/me.json?url="._PS_BASE_URL_));
                $smarty->assign('urlProduct', "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                if($client->code == 200 && $client->result->facebookAdminID) {
                        $smarty -> assign('facebookID', $client->result->facebookAdminID);
                } else {
                        $smarty -> assign('facebookID', '');
                }
                return $this->display(__FILE__, 'header.tpl');
        }
        public function hookExtraLeft($params) {
                global $smarty;
                if(count($this->data["soshake"]) > 0) return $this->share("left");
        }
        public function hookExtraRight($params) {
                global $smarty;
                if(count($this->data["soshake"]) > 0) return $this->share("right");
        }
        public function hookorderConfirmation($params) {
                file_get_contents("http://soshake.com/api/actions/achat/".$params["total_to_pay"].":".$params["cart"]->nbProducts().".js?url="._PS_BASE_URL_);
        }
        public function hookcart($params) {
                file_get_contents("http://soshake.com/api/actions/panier/0:0.js?url="._PS_BASE_URL_);
        }
}
if(isset($_POST["up2FBConnect"]) && $_POST["up2FBConnect"] == 1) {
        global $cookie;
        $customer = new Customer();
        if (Customer::customerExists($_POST["email"])) {
                //Client déjà existant
        } else {
                if ($_POST["gender"] == "male")         $_POST['id_gender'] = 1;
                elseif ($_POST["gender"] == "female")   $_POST['id_gender'] = 2;
                else                                    $_POST['id_gender'] = 9;
                
                if($_POST["birthday"] != "") {
                        $customer_birthday = explode('/',$_POST["birthday"]);
                        $_POST["birthday"] = intval($customer_birthday[2]).'-'.$customer_birthday[0].'-'.$customer_birthday[1];
                }
                
                $customer->lastname     = $_POST["last_name"];
                $customer->firstname    = $_POST["first_name"];
                $customer->birthday     = $_POST["birthday"];
                $customer->fb_uid       = $_POST["fb_uid"];
                $customer->email        = $_POST["email"];
                $customer->passwd       = md5($_POST["email"]);
                $customer->add();
                if (!$customer->is_guest) {
                        if (!Mail::Send((int)(self::$cookie->id_lang), 'account', Mail::l('Welcome!'),
                        array('{firstname}' => $customer->firstname, '{lastname}' => $customer->lastname, '{email}' => $customer->email, '{passwd}' => Tools::getValue('passwd')), $customer->email, $customer->firstname.' '.$customer->lastname))
                                $this->errors[] = Tools::displayError('Cannot send email');
                }
        }
        //Log in session
        $customer = new Customer();
        $customer->getByEmail($_POST["email"]);
        $cookie->id_customer = (int)$customer->id;
        $cookie->customer_lastname = $customer->lastname;
        $cookie->customer_firstname = $customer->firstname;
        $cookie->logged = 1;
        $cookie->is_guest = $customer->isGuest();
        $cookie->passwd = $customer->passwd;
        $cookie->email = $customer->email;
}

//Generate a password
function generatePassword() {
    $length = 10;
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzw";
    $string = "";    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

?>