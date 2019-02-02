<?php namespace Core\Util;

class YamlReader {
    protected $file;

    public function __construct($path) {
        $this->file = yaml_parse_file(realpath($path));
    }

    public static function parseFile($path) {
        return yaml_parse_file(realpath($path));
    }

    public function getContent() {
        return $this->file;
    }
}