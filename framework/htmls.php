<?php
require_once 'dombase.php';

interface Sortable
{
    public function sort();
}

interface Labelable
{
    public function label($text);
}

class HtmlElement extends HtmlBase
{   
    private static $tag = "html";

    private static $csstype_value = "text/css";
    private static $cssrel_value = "stylesheet";

    private $head;
    private $body;

    public function __construct($charset, $title) {
        parent::__construct(self::$tag);
        $this->children = array();

        $this->head = new HeadElement($this, $charset, $title);
        array_push($this->children, $this->head);

        $this->body = new BodyElement($this);
        array_push($this->children, $this->body);
    }

    public function style_push($stylesheet_url) {
        $stylelink = new LinkElement($this->head);
        $stylelink->href($stylesheet_url);
        $stylelink->type(self::$csstype_value);
        $stylelink->rel(self::$cssrel_value);

        $this->head->push_link($stylelink);
        
        return $stylelink;
    }

    public function body_push(HtmlBase $element) {
        $this->body->push($element);
        return $element;
    }
}

class HeadElement extends HtmlBase
{
    private static $tag = "head";

    public function __construct(HtmlElement $host, $charset_value, $title) {
        parent::__construct(self::$tag);
        $this->children = array();

        $meta = new MetaElement($this);
        $meta->charset($charset_value);
        array_push($this->children, $meta);

        $title = new TitleElement($this, $title);
        array_push($this->children, $title);
    }

    public function push_link(LinkElement $link) {
        array_push($this->children, $link);
        return $link;
    }
}

class BodyElement extends HtmlBase
{
    private static $tag = "body";

    private $scripts;

    public function __construct(HtmlElement $host) {
        parent::__construct(self::$tag);
        $this->children = array();
        $this->scripts = array();
    }

    public function push(HtmlBase $element) {
        // keep scripts in a separate array so that it can be
        // place at the end of all other child element upon rendering
        if ($element instanceof ScriptElement) {
            array_push($this->scripts, $element);
        } else {
            array_push($this->children, $element);
        }
    }

    // push script element into children before rendering
    // this way we keep the scripts always the last elements in body
    // and other visual elements can be loaded first
    public function render($indent_unit, $indent_level) {
        foreach($this->scripts as $script) {
            array_push($this->children, $script);
        }
        return parent::render($indent_unit, $indent_level);
    }
}

class MetaElement extends HtmlBase
{
    private static $tag = "meta";

    private static $charset = "charset";

    public function __construct(HeadElement $host) {
        parent::__construct(self::$tag);
        $this->children = NULL;        

        $this->attributes[self::$charset] = "";
    }

    public function charset($value) {
        return $this->attribute(self::$charset, $value);
    }
}

class LinkElement extends HtmlBase
{
    private static $tag = "link";

    private static $href = "href";
    private static $type = "type";
    private static $rel = "rel";

    public function __construct(HeadElement $host) {
        parent::__construct(self::$tag);
        $this->children = NULL;
    }

    public function href($value) {
        return $this->attribute(self::$href, $value);
    }

    public function type($value) {
        return $this->attribute(self::$type, $value);
    }

    public function rel($value) {
        return $this->attribute(self::$rel, $value);
    }
}

class TitleElement extends HtmlBase
{
    private static $tag = "title";

    public function __construct(HeadElement $parent, $title_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $text = new HtmlText($title_text);
        array_push($this->children, $text);
    }
}

class ScriptElement extends HtmlBase
{
    private static $tag = "script";

    private static $src = "src";
    private static $type = "type";

    private static $type_value = "text/javascript";

    public function __construct($script_url) { 
        parent::__construct(self::$tag);
        $this->children = array();        

        $this->attributes[self::$src] = $script_url;
        $this->attributes[self::$type] = self::$type_value;
    }

    public function src($script_url) {
        return $this->attribute(self::$src, $script_url);
    }

    public function render($indent_unit, $indent_level) {
        $zeroindented_output = parent::render($indent_unit, 0);
        return $zeroindented_output;
    }
}

class BrElement extends HtmlBase
{
    private static $tag = "br";

    public function __construct() {
        parent::__construct(self::$tag);
        $this->children = NULL;    
    }
}

class SpanElement extends HtmlBase
{
    private static $tag = "span";

    public function __construct() { 
        parent::__construct(self::$tag);
        $this->children = array();
    }
}

class AElement extends HtmlBase
{
    private static $tag = "a";

    private static $href = "href";
    private static $target = "target";

    private static $blankwin_value = "_blank";

    public function __construct($link_url, $link_text) { 
        parent::__construct(self::$tag);
        $this->children = array();

        $this->attributes[self::$href] = $link_url;
        $this->attributes[self::$target] = "";

        $text = new HtmlText($link_text);
        array_push($this->children, $text);
    }

    public function href($link_url) {
        return $this->attribute(self::$href, $link_url);
    }

    public function blankwindow() {
        $this->attributes[self::$target] = self::$blankwin_value;
    }
}

class ButtonElement extends HtmlBase
{
    private static $tag = "button";

    public function __construct($button_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $text = new HtmlText($button_text);
        array_push($this->children, $text);
    }
}

class ImgElement extends HtmlBase
{
    private static $tag = "img";

    private static $src = "src";
    private static $alt = "alt";
    private static $title = "title";

    public function __construct($src_value, $alt_value) {
        parent::__construct(self::$tag);
        $this->children = NULL;
        
        $this->attributes[self::$src] = $src_value;
        $this->attributes[self::$alt] = $alt_value;
        $this->attributes[self::$title] = "";
    }

    public function src($image_url = NULL) {
        return $this->attribute(self::$src, $image_url);
    }

    public function alt($text = NULL) {
        return $this->attribute(self::$alt, $text);
    }

    public function title($text = NULL) {
        return $this->attribute(self::$title, $text);
    }
}

class DivElement extends HtmlBase
{
    private static $tag = "div";

    public function __construct() {
        parent::__construct(self::$tag);
        $this->children = array();
    }

    public function push(HtmlBase $element) {
        array_push($this->children, $element);
        return $element;
    }
}

class LiElement extends HtmlBase
{
    private static $tag = "li";

    public function __construct(Sortable $host, Renderable $content) {
        parent::__construct(self::$tag);
        $this->children = array($content);
    }

    public function push_text($item_text) {
        $text = new HtmlText($item_text);
        array_push($this->children, $text);
        return $text;
    }
}

class OlElement extends HtmlBase implements Sortable
{
    private static $tag = "ol";

    public function __construct() {
        parent::__construct(self::$tag);
        $this->children = array();
    }

    public function sort() {
    }

    public function push(Renderable $content) {
        $item = new LiElement($this, $content);
        array_push($this->children, $item);
        return $item;
    }
}

class UlElement extends HtmlBase implements Sortable
{
    private static $tag = "ul";

    public function __construct() {
        parent::__construct(self::$tag);
        $this->children = array();
    }

    public function sort() {
    }

    public function push(Renderable $content) {
        $item = new LiElement($this, $content);
        array_push($this->children, $item);
        return $item;
    }
}

class DtElement extends HtmlBase
{
    private static $tag = "dt";

    public function __construct(DlElement $host, Renderable $content) {
        parent::__construct(self::$tag);
        $this->children = array($content);
    }
}

class DdElement extends HtmlBase
{
    private static $tag = "dd";

    public function __construct(DlElement $host, Renderable $content) {
        parent::__construct(self::$tag);
        $this->children = array($content);
    }
}

class DlElement extends HtmlBase
{
    private static $tag = "dl";

    public function __construct() {
        parent::__construct(self::$tag);
        $this->children = array();
    }

    public function push(Renderable $title, Renderable $description) {
        $title_item = new DtElement($this, $title);
        $description_item = new DdElement($this, $description);
        array_push($this->children, $title_item);
        array_push($this->children, $description_item);

        $item = array("title"=>$title_item, "description"=>$description_item);
        return $item;
    }
}

class PElement extends HtmlBase
{
    private static $tag = "p";

    public function __construct($paragraph_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $text = new HtmlText($paragraph_text);     
        array_push($this->children, $text);
    }

    public function push_text($paragraph_text) {
        $text = new HtmlText($paragraph_text);
        array_push($this->children, $text);
    }

    public function push_break() {
        $break = new BrElement();
        array_push($this->children, $break);
    }
}

class HrElement extends HtmlBase
{
    private static $tag = "hr";

    public function __construct() {
        parent::__construct(self::$tag);
        $this->children = NULL;    
    }
}

class H1Element extends HtmlBase
{
    private static $tag = "h1";

    public function __construct($heading_text) { 
        parent::__construct(self::$tag);
        $this->children = array();

        $text = new HtmlText($heading_text);
        array_push($this->children, $text);
    }
}

class H2Element extends HtmlBase
{
    private static $tag = "h2";

    public function __construct($heading_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $text = new HtmlText($heading_text);
        array_push($this->children, $text);
    }
}

class H3Element extends HtmlBase
{
    private static $tag = "h3";

    public function __construct($heading_text) {
        parent::__construct(self::$tag);
        $this->children = array();
           
        $text = new HtmlText($heading_text);
        array_push($this->children, $text);
    }
}

class H4Element extends HtmlBase
{
    private static $tag = "h4";

    public function __construct($heading_text) {
        parent::__construct(self::$tag);
        $this->children = array();
           
        $text = new HtmlText($heading_text);
        array_push($this->children, $text);
    }
}

class H5Element extends HtmlBase
{
    private static $tag = "h5";

    public function __construct($heading_text) {
        parent::__construct(self::$tag);
        $this->children = array();
           
        $text = new HtmlText($heading_text);
        array_push($this->children, $text);
    }
}

class H6Element extends HtmlBase
{
    private static $tag = "h6";

    public function __construct($heading_text) {
        parent::__construct(self::$tag);
        $this->children = array();
           
        $text = new HtmlText($heading_text);
        array_push($this->children, $text);
    }
}

class LabelElement extends HtmlBase
{
    private static $tag = "label";

    private $text;

    public function __construct($label_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $this->text = new HtmlText($label_text);
        // do not push this into the child yet
    }

    public function put_ahead($input) {
        array_push($this->children, $this->text);
        array_push($this->children, $input);        
    }

    public function put_behind($input) {
        array_push($this->children, $input);
        array_push($this->children, $this->text);
    }
}

class InputElement extends HtmlBase implements Labelable
{
    public static $TextType = "text";
    public static $PasswordType = "password";
    public static $RadioType = "radio";
    public static $CheckboxType = "checkbox";
    public static $FileType = "file";
    public static $ButtonType = "button";
    public static $HiddenType = "hidden";
    public static $SubmitType = "submit";

    private static $tag = "input";

    private static $type = "type";
    private static $name = "name";
    private static $value = "value";

    private static $maxlength = "maxlength";
    private static $checked = "checked";
    private static $placeholder = "placeholder";

    private static $checked_value = "checked";

    public function __construct(FormElement $host, $type, $name, $value) {
        parent::__construct(self::$tag);
        $this->children = NULL;

        if ($type == self::$TextType) {
            $this->attributes[self::$type] = self::$TextType;
        } else if ($type == self::$PasswordType) {
            $this->attributes[self::$type] = self::$PasswordType;
        } else if ($type == self::$RadioType) {
            $this->attributes[self::$type] = self::$RadioType;
        } else if ($type == self::$CheckboxType) {
            $this->attributes[self::$type] = self::$CheckboxType;
        } else if ($type == self::$FileType) {
            $host->use_post();
            $host->encrypt();
            $this->attributes[self::$type] = self::$FileType;
        } else if ($type == self::$ButtonType) {
            $this->attributes[self::$type] = self::$ButtonType;
        } else if ($type == self::$HiddenType) {
            $this->attributes[self::$type] = self::$HiddenType;
        } else if ($type == self::$SubmitType) {
            $this->attributes[self::$type] = self::$SubmitType;
        } else {
            echo "[InputElement] Error: invalid input type";
            $this->attributes[self::$type] = self::$HiddenType;            
        }

        $this->attributes[self::$name] = $name;
        $this->attributes[self::$value] = $value;        
        
        $this->attributes[self::$maxlength] = "";
        $this->attributes[self::$checked] = "";
    }

    private function texttype() {
        $type = $this->attributes[self::$type];
        
        if ($type == self::$TextType || $type == self::$PasswordType) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function checktype() {
        $type = $this->attributes[self::$type];
        
        if ($type == self::$RadioType || $type == self::$CheckboxType) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function label($text) {
        $label = new LabelElement($text);

        if ($this->checktype() === TRUE) {
            $label->put_behind($this);            
        } else {
            $label->put_ahead($this);            
        }

        return $label;
    }    

    public function maxlength($value = NULL) {
        if ($this->texttype() === TRUE) {
            return $this->attribute(self::$maxlength, $value);
        } else {
            // do nothing
        }
    }

    public function check() {
        if ($this->checktype() === TRUE) {
            $this->attribute[self::$checked] = self::$checked_value;
        } else {
            // do nothing
        }
    }

    public function placeholder($text) {
        if ($this->texttype() === TRUE) {
            $this->attributes[self::$placeholder] = $text;
        } else {
            // do nothing
        }
    }
}

class SelectElement extends HtmlBase implements Labelable
{
    private static $tag = "select";

    private static $name = "name";

    private $grouped;

    public function __construct(FormElement $host, $name, $options) {
        parent::__construct(self::$tag);
        $this->children = array();

        $this->attributes[self::$name] = $name;

        foreach ($options as $value=>$text) {
            $option = new OptionElement($this, $value, $text);
            array_push($this->children, $option);
        }

        $this->grouped = FALSE;
    }

    public function label($text) {
        $label = new LabelElement($text);
        $label->put_ahead($this);
        return $label;
    }

    public function push_optgroup($groups) {
        $optgroup = NULL;

        if ($this->grouped === TRUE) {
            echo "[SelectElement] Error: selection already grouped";
        } else if (count($groups) < 0) {
            echo "[SelectElement] Error: number of groups must be positive";
        } else if (count($groups) > count($this->children)) {
            echo "[SelectElement] Error: number of groups must be smaller";
        } else {
            $group_index = 0;

            foreach ($groups as $label=>$count) {
                $optgroup = new OptgroupElement($this, $label);
                $set = array_splice($this->children, $group_index, $count, array($optgroup));
                
                $optgroup->push($set);
                $group_index += 1;
            }
        }

        $this->grouped = TRUE;
        
        return $optgroup;
    }    
}

class TextareaElement extends HtmlBase implements Labelable
{
    private static $tag = "textarea";

    private static $name = "name";

    public function __construct(FormElement $host, $name, $init_value) {
        parent::__construct(self::$tag);
        $this->children = array();  
        
        $this->attributes[self::$name] = $name;

        $initial_text = new HtmlText($init_value);
        array_push($this->children, $initial_text);
    }

    public function label($text) {
        $label = new LabelElement($text);
        $label->put_ahead($this);
        return $label;
    }

    // override
    public function render($indent_unit, $indent_level) {
        $unindented_output = parent::render("", 0);
        return $unindented_output;
    }    
}

class FormElement extends HtmlBase
{
    private static $tag = "form";

    private static $action = "action";
    private static $method = "method";
    private static $enctype = "enctype";

    private static $getmthd_value = "get";
    private static $postmthd_value = "post";
    private static $fileenc_value = "multipart/form-data";

    public function __construct($handler_url) {
        parent::__construct(self::$tag);
        $this->children = array();        

        $this->attributes[self::$action] = $handler_url;
        $this->attributes[self::$method] = self::$getmthd_value;
        $this->attributes[self::$enctype] = "";
    }

    public function use_post() {
        $this->attributes[self::$method] = self::$postmthd_value;
    }

    public function encrypt() {
        $this->attributes[self::$enctype] = self::$fileenc_value;
    }

    private function push_labeled(Labelable $input, $label_text) {
        $labeled = $input->label($label_text);
        array_push($this->children, $labeled);
        return $labeled;        
    }

    public function push_input($type, $name, $value, $label_text) {
        $input = new InputElement($this, $type, $name, $value);
        $labeled = $this->push_labeled($input, $label_text);
        return $input;
    }

    public function push_textarea($name, $value, $instruction) {
        $textarea = new TextareaElement($this, $name, $value);
        $labeled = $this->push_labeled($textarea, $instruction);
        return $textarea;
    }

    public function push_selection($name, $options, $instruction) {
        $selection = NULL;

        if (count($options) < 2) {
            echo "[FormElement] Error: options must be than two";
        } else {
            $selection = new SelectElement($this, $name, $options);
            $labeled = $this->push_labeled($selection, $instruction);
        }
        
        return $selection;
    }

    public function push_fieldset($description, $begin, $count) {
        $fieldset = NULL;

        if ($begin < 0) {
            echo "[FormElement] Error: begin must be positive";
        } else if ($begin >= count($this->children)) {
            echo "[FormElement] Error: begin must be smaller";
        } else if ($count <= 0) {
            echo "[FormElement] Error: count must be positive";
        } else {
            $fieldset = new FieldsetElement($this, $description);
            $set = array_splice($this->children, $begin, $count, array($fieldset));
            $fieldset->push($set);
        }
        
        return $fieldset;
    }
}

class OptionElement extends HtmlBase
{
    private static $tag = "option";

    private static $value = "value";
    private static $selected = "selected";

    private static $selected_value = "selected";

    public function __construct(SelectElement $host, $value, $option_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $this->attributes[self::$value] = $value;
        $this->attributes[self::$selected] = "";

        $text = new HtmlText($option_text);
        array_push($this->children, $text);
    }

    public function select() {
        $this->attributes[self::$selected] = self::$selected_value;
    }
}

class OptgroupElement extends HtmlBase
{
    private static $tag = "optgroup";

    private static $label = "label";
    private static $disabled = "disabled";

    private static $disabled_value = "disabled";

    public function __construct(SelectElement $host, $label_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $this->attributes[self::$label] = $label_text;
        $this->attributes[self::$disabled] = "";
    }

    public function disable($do) {
        if ($do === TRUE) {
            $this->attributes[self::$disabled] = self::$disabled_value;
        } else {
            $this->attributes[self::$disabled] = "";            
        }
    }

    public function push($options) {
        foreach ($options as $option) {
            array_push($this->children, $option);
        }
    }
}

class LegendElement extends HtmlBase
{
    private static $tag = "legend";

    public function __construct(FieldsetElement $host, $content_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $text = new HtmlText($content_text);
        array_push($this->children, $text);
    }
}

class FieldsetElement extends HtmlBase
{
    private static $tag = "fieldset";

    public function __construct(FormElement $host, $legend_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        if ($legend_text == "") {
            // this is a fieldset without legend
        } else {
            $legend = new LegendElement($this, $legend_text);
            array_push($this->children, $legned);
        }
    }

    public function push($inputs) {
        foreach ($inputs as $input) {
            array_push($this->children, $input);
        }
    }
}
?>