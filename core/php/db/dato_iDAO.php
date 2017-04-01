<?php
/**
 * Copyright (c) 2017 Juri Jäger
 * The full notice can be found in the LICENSE file.
 */
interface dato_iDAO{
    public function getPKParams();
    public function getInsertableParams();
    public function getParams();
    public function insert();
    public function load();
    public function save();
    public function remove();
    public function toArray();
}
?>