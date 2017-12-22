<?php namespace CompleteSolar\HelioConfig\Utilities;

class Config {
    public $properties;
    private $useSections;
    public function __construct($configFile, $useSections = true) {
        $this->properties = parse_ini_file ( $configFile, $useSections );
        $this->useSections = $useSections;
    }

    public function getProperty($indexArray) {
        $indexArray = $this->configureIndexArray($indexArray);
        if (count($indexArray) == 0) {
            return null;
        }
        $val = $this->properties;
        foreach($indexArray as $index){
            if (!array_key_exists($index, $val)){
                throw new \OutOfBoundsException("$index not found in config file.");
            }
            $val = $val[$index];
        }
        return $val;
    }

    public function setProperty($indexArray, $value){
        $indexArray = $this->configureIndexArray($indexArray);
        if (count($indexArray) == 0) {
            return null;
        }
        $val = &$this->properties;
        for($i=0;$i<count($indexArray)-1; $i++){
            $index = $indexArray[$i];
            if (!array_key_exists($index, $this->properties)){
                throw new \OutOfBoundsException("$index not found in config file.");
            }
            $val = &$val[$index];
        }
        $val[$indexArray[count($indexArray) - 1]] = $value;
    }

    public function write_ini_file($path) {
        $content = $this->useSections ? $this->getContent() : $this->getContentWithoutSections();
        if (!$handle = fopen($path, 'w')) {
            return false;
        }
        $success = fwrite($handle, $content);
        fclose($handle);
        return $success;
    }

    private function getContent(){
        $content = "";
        foreach ($this->properties as $key=>$elem) {
            $content .= "[".$key."]\n";
            foreach ($elem as $key2=>$elem2) {
                if(is_array($elem2)){
                    for($i=0;$i<count($elem2);$i++){
                        $content .= $key2."[] = \"".$elem2[$i]."\"\n";
                    }
                    continue;
                }
                $content .= $key2." = \"".$elem2."\"\n";
            }
        }
        return $content;
    }

    private function getContentWithoutSections(){
        $content = '';
        foreach ($this->properties as $key=>$elem) {
            if(is_array($elem)){
                for($i=0;$i<count($elem);$i++){
                    $content .= $key."[] = \"".$elem[$i]."\"\n";
                }
                continue;
            }
            $content .= $key." = \"".$elem."\"\n";
        }
        return $content;
    }

    private function configureIndexArray($indexArray){
        if (!is_array($indexArray)){
            $indexArray = array (
                    $indexArray
            );
        }
        return $indexArray;
    }
}