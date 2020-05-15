<?php

namespace src\template;

class TemplateEngine
{
    private string $input;
    private string $html;
    private array $params;

    private const VARIABLE_REGEX = "/{{\w+}}/";
    private const PROPERTY_REGEX = "/{=\w+.\w+}}/";
    private const FUNCTION_REGEX = "/{=\w+->\w+}}/";
    private const IF_REGEX = "/{% IF (.*?) %}\s*(.*?)\s*{% ENDIF %}/i";
    private const IF_ELSE_REGEX = "/{% IF (.*?) %}\s*(.*?)\s*{% ELSE %}\s*(.*?)\s*{% ENDIF %}/i";
    private const IF_ELSEIF_ELSE_REGEX = "/{% IF (.*?) %}\s*(.*?)\s*{% ELSEIF (.*?) %}\s*(.*?)\s*{% ELSE %}\s*(.*?)\s*{% ENDIF %}/i";
    private const FOR_REGEX = "/{% for (\w+) in (\w+) %}\s*(.*?)\s*{% ENDFOR %}/is";


    public function __construct(string $path)
    {
        $this->input = file_get_contents($path);
        $this->html = $this->input;
    }


    private function toHtml()
    {
        $matches = [];
        preg_match_all(self::VARIABLE_REGEX, $this->html, $matches);
        foreach ($matches[0] as $match) {
            $variable = substr($match, 2, strlen($match) - 4);
            $replace = $this->params[$variable] ?? "";

            $this->html = str_replace($match, $replace, $this->html);
        }

        $matches = [];
        preg_match_all(self::PROPERTY_REGEX, $this->html, $matches);
        foreach ($matches[0] as $match) {
            $matchExtract = substr($match, 2, strlen($match) - 4);
            [$object, $property] = explode(".", $matchExtract);
            $getter = "get" . ucfirst($property);

            if (!isset($this->params[$object])) {
                $replace = "";
            } else {
                $replace = $this->params[$object]->$property ?? $this->params[$object]->$getter() ?? "";
            }
            $this->html = str_replace($match, $replace, $this->html);
        }


        $matches = [];
        preg_match_all(self::FUNCTION_REGEX, $this->html, $matches);
        foreach ($matches[0] as $match) {
            $matchExtract = substr($match, 2, strlen($match) - 4);
            [$object, $function] = explode("->", $matchExtract);

            $replace = $this->params[$object]->$function() ?? "";
            $this->html = str_replace($match, $replace, $this->html);
        }


        $matches = [];
        preg_match_all(self::IF_REGEX, $this->html, $matches);

        // iteriraj po svim nadjenim if izrazima
        for ($i = 0; $i < count($matches[0]); $i++) {
            $fullMatch = $matches[0][$i];
            $condition = $matches[1][$i];
            $action = $matches[2][$i];

            //todo varijable unutar if uvjeta sa zagradama?
            if (eval('return (' . $condition . ');')) {
                $this->html = str_replace($fullMatch, $action, $this->html);
            } else {
                $this->html = str_replace($fullMatch, "", $this->html);
            }
        }


        $matches = [];
        preg_match_all(self::IF_ELSE_REGEX, $this->html, $matches);

        // iteriraj po svim nadjenim if izrazima
        for ($i = 0; $i < count($matches[0]); $i++) {
            $fullMatch = $matches[0][$i];
            $condition = $matches[1][$i];
            $actionIf = $matches[2][$i];
            $actionElse = $matches[3][$i];

            //todo varijable unutar if uvjeta sa zagradama?
            if (eval('return (' . $condition . ');')) {
                $this->html = str_replace($fullMatch, $actionIf, $this->html);
            } else {
                $this->html = str_replace($fullMatch, $actionElse, $this->html);
            }
        }

        $matches = [];
        preg_match_all(self::IF_ELSEIF_ELSE_REGEX, $this->html, $matches);

        // iteriraj po svim nadjenim if izrazima
        for ($i = 0; $i < count($matches[0]); $i++) {
            $fullMatch = $matches[0][$i];
            $condition = $matches[1][$i];
            $actionIf = $matches[2][$i];
            $elseIfCondition = $matches[3][$i];
            $elseIfAction = $matches[4][$i];
            $elseAction = $matches[5][$i];

            //todo varijable unutar if uvjeta sa zagradama?
            if (eval('return (' . $condition . ');')) {
                $this->html = str_replace($fullMatch, $actionIf, $this->html);
            } else if (eval('return (' . $elseIfCondition . ');')) {
                $this->html = str_replace($fullMatch, $elseIfAction, $this->html);
            } else {
                $this->html = str_replace($fullMatch, $elseAction, $this->html);
            }
        }


        $matches = [];
        preg_match_all(self::FOR_REGEX, $this->html, $matches);

        // iteriraj po svim nadjenim for petljama
        for ($i = 0; $i < count($matches[0]); $i++) {
            $fullMatch = $matches[0][$i];
            $item = $matches[1][$i];
            $list = $matches[2][$i];
            $action = $matches[3][$i];

            $fullAction = "";

            if (isset($this->params[$list])) {
                for ($j = 0; $j < count($this->params[$list]); $j++) {

                    $callback = function (array $matches) use ($j, $list): string {
                        $match = $matches[0];
                        $parts = explode(".", $match);
                        if (count($parts) > 1) {
                            $matchExtract = substr($match, 3, strlen($match) - 5);
                            [$object, $property] = explode(".", $matchExtract);
                            $getter = "get" . ucfirst($property);

                            if (!isset($this->params[$list][$j])) {
                                return "";
                            }

                            return $this->params[$list][$j]->$property ?? $this->params[$list][$j]->$getter() ?? "";
                        }
                        return $this->params[$list][$j];
                    };

                    $newAction = preg_replace_callback("/{{%$item(.*?)}}/", $callback, $action);
                    $fullAction .= $newAction;
                }
            }
            $this->html = str_replace($fullMatch, $fullAction, $this->html);
        }
    }

    public function getHtml()
    {
        $this->toHtml();
        return $this->html;
    }

    public function addParam(string $key, $value)
    {
        $this->params[$key] = $value;
    }
}