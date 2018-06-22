<?php

namespace Monte;

/**
 *  Site Class
 *
 *  Use this section to define what this class is doing, the PHPDocumentator will use this
 *  to automatically generate an API documentation using this information.
 *
 * @author Sergiu Finciuc
 */
class Site
{



    private $base = '';

    private $key = '';

    /**
     * Sample method
     *
     * Always create a corresponding docblock for each method, describing what it is for,
     * this helps the phpdocumentator to properly generator the documentation
     *
     * @param string $param1 A string containing the parameter, do this for each parameter to the function, make sure to make it descriptive
     *
     * @return string
     */
    public function hello($param1)
    {
        return "Hello World";
    }

    public static function getCollection($collectionName)
    {
        return $collectionName;
    }
}