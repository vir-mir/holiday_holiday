<?php
/**
 * Created by PhpStorm.
 * User: vir-mir
 * Date: 20.11.13
 * Time: 19:40
 */

namespace library;


class Xml {

    private $_sep = "\n";
    private $_tab = "\t";

    public function setNods($name, $list, $dop = null) {
        $xml = "<{$name}>" . $this->_sep;
        $nodName = substr($name,0,-1);
        foreach ($list as $nod) {
            $xml .= $this->setNod($nodName, $nod);
        }
        if ($dop)
            $xml .= $this->_tab . $dop . $this->_sep;
        $xml .= "</{$name}>" . $this->_sep;

        return $xml;
    }

    public function setNod($name, $nod) {
        $nod = to_array($nod);
        $xml = "<{$name} id='{$nod['id']}'>" . $this->_sep;
        foreach ($nod as $key=>$el) {
            $xml .= $this->_tab . "<$key>{$el}</$key>" . $this->_sep;
        }
        $xml .= "</{$name}>" . $this->_sep;

        return $xml;
    }

} 