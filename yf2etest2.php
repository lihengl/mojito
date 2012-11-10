<?php
require_once 'form_elements.php';
require_once 'inline_elements.php';
require_once 'block_elements.php';

class Yf2eTest2
{
    private static $doctype = "<!DOCTYPE html>";

    private static $indent_unit = "    ";
    private static $indent_level = 0;

    private static $jscript_url = "yf2etest2.js";

    private $html;
    private $script;    

    private $banner;
    private $instruction;
    private $searchbox;
    private $suggestionbox;
    private $resultlist;

    public function __construct($html_title) {
        $this->html = new HtmlElement($html_title);
        
        $this->script = new ScriptElement(self::$jscript_url);

        $this->banner = new H1Element("Instant Search");
        $this->instruction = new PElement("Candidate: Li-Heng Liang");
        
        $this->searchbox = new FormElement("handler.php");
        $input = $this->searchbox->push_input(InputElement::$TextType, "search", "", "");
        $input->id("searchbox");
        $input->placeholder("Enter Keyword");

        $this->suggestionbox = new DivElement();
        $this->suggestionbox->id("suggestion");
        $this->suggestionbox->push(new PElement("Suggestion: "));

        $this->resultlist = new DivElement();
        $this->resultlist->id("resultlist");

        $this->html->body_push($this->script);
        $this->html->body_push($this->banner);
        $this->html->body_push($this->instruction);
        $this->html->body_push($this->searchbox);
        $this->html->body_push($this->suggestionbox);
        $this->html->body_push($this->resultlist);

        $this->html->attach_entrypoint("main()");
        $this->html->attach_style("yf2etest2_layout.css");
    }

    public function render() {
        $doc = $this->html->render(self::$indent_unit, self::$indent_level);
        return self::$doctype . "\n" . $doc;
    }

    public function fetch($query) {

        $example = array("string1", "string2", "string3");

        $item = array("wordtitle"=>$query,
                      "descriptiontext"=>"descriptext",
                      "examples"=>$example);

        $list = array();

        for ($count = 0; $count < 3; $count++) {
            array_push($list, $item);
        }

        return json_encode($list);
    }
}
?>