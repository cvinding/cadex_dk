<?php
namespace VIEW\PARTIAL\BASE;

abstract class PartialView {

    public abstract function __construct(\Request $request);

    public abstract function build();

}