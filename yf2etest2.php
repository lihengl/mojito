<?php
require_once 'form_elements.php';
require_once 'inline_elements.php';
require_once 'block_elements.php';

class Yf2eTest2
{
    private static $doctype = "<!DOCTYPE html>";

    private static $indent_unit = "    ";
    private static $indent_level = 0;

    private static $title = "Responsive Search";
    private static $subtitle = "A Frontend Engineering Test Project for Yahoo!";

    private static $jscript = "yf2etest2.js";

    private static $db_server = "mysql:host=127.0.0.1;dbname=yf2etest2";
    private static $db_uesrname = "yf2etest2";
    private static $db_password = "yf2etest2";

    private $html;
    private static $db;

    public static function fetch($query) {
        
        try {
            self::$db = new PDO(self::$db_server, self::$db_uesrname, self::$db_password);
        } catch (PDOException $e) {
            //
        }

        $db_query = self::$db->query('select now()');
        $data = $db_query->fetchAll(PDO::FETCH_ASSOC);

        $examples = array("string1", "string2", "string3");

        $item = array("wordtitle"=>$data[0]['now()'],
                      "descriptiontext"=>"descriptext",
                      "examples"=>$examples);

        $list = array();
        for ($count = 0; $count < 20; $count++) {
            array_push($list, $item);
        }

        self::$db = NULL;

        return json_encode($list);
    }

    private function body_pushes() {
        $this->html->body_push($this->script);

        $this->html->body_push($this->application_title);
        $this->html->body_push($this->application_subtitle);
        
        $this->html->body_push($this->searchbox);

        $this->html->body_push($this->suggest_label);
        $this->html->body_push($this->suggest_list);

        $this->html->body_push($this->result_label);
        $this->html->body_push($this->result_list);        
    }

    public function __construct($html_title) {
        $this->html = new HtmlElement($html_title);
        
        $this->script = new ScriptElement(self::$jscript);

        $this->application_title = new H1Element(self::$title);
        $this->application_subtitle = new PElement(self::$subtitle);
        
        $this->searchbox = new FormElement("main.php");
        $input = $this->searchbox->push_input(InputElement::$TextType, "search", "", "");
        $input->id("searchbox");
        $input->placeholder("Enter Keyword");

        $this->suggest_label = new PElement("Suggestions: ");

        $this->suggest_list = new OlElement();
        $this->suggest_list->id("suggestlist");

        $this->result_label = new PElement("Results: ");

        $this->result_list = new DlElement();
        $this->result_list->id("resultlist");

        $this->body_pushes();

        $this->html->attach_style("yf2etest2_layout.css");
        $this->html->attach_style("yf2etest2_color.css");
        $this->html->attach_style("yf2etest2_typeface.css");

        $this->html->attach_scriptentry("main()");       
    }

    public function render() {
        $doc = $this->html->render(self::$indent_unit, self::$indent_level);
        return self::$doctype . "\n" . $doc;
    }
}
?>