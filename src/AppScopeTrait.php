<?php

namespace atk4\core;

trait AppScopeTrait {

    public $_appScopeTrait = true;

    /**
     * Always points to current Application
     *
     * @var App_CLI
     */
    public $app;

    /**
     * When using mechanism for ContainerTrait, they inherit name of the
     * parent to generate unique name for a child. In a framework it makes
     * sense if you have a unique identifiers for all the objects because
     * this enables you to use them as session keys, get arguments, etc.
     *
     * Unfortunatelly if those keys become too long it may be a problem,
     * so ContainerTrait contains a mechanism for auto-shortening the
     * name based around max_name_length. The mechanism does only work
     * if AppScopeTrait is used, $app property is set and has a
     * max_name_length defined.
     *
     * See http://stackoverflow.com/a/9399615/1466341 for more info.
     *
     * @var int
     */
    public $max_name_length = 60;

    /**
     * As more names are shortened, the substituted part is being placed into
     * this hash and the value contains the new key. This helps to avoid creating
     * many sequential prefixes for the same character sequenece. Those
     * hashes can also be used to re-build the long name of the object, but
     * ths functionality is not essential and excluded from traits. You
     * can find it in a test suite.
     *
     * @var array
     */
    public $unique_hashes = array();
}
