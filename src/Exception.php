<?php declare(strict_types=1);

// vim:ts=4:sw=4:et:fdm=marker

namespace atk4\core;

use atk4\core\ExceptionRenderer\Console;
use atk4\core\ExceptionRenderer\HTML;
use atk4\core\ExceptionRenderer\HTMLText;
use atk4\core\ExceptionRenderer\JSON;
use atk4\core\ExceptionRenderer\RendererAbstract;
use atk4\core\Translator\ITranslatorAdapter;
use atk4\core\Translator\Translator;
use Throwable;

/**
 * All exceptions generated by Agile Core will use this class.
 *
 * @license   MIT
 * @copyright Agile Toolkit (c) http://agiletoolkit.org/
 */
class Exception extends \Exception
{
    use TranslatableTrait;

    protected $custom_exception_title = 'Critical Error';

    /** @var string The name of the Exception for custom naming */
    protected $custom_exception_name = null;

    /**
     * Most exceptions would be a cause by some other exception, Agile
     * Core will encapsulate them and allow you to access them anyway.
     */
    private $params = [];

    /** @var array */
    public $trace2; // because PHP's use of final() sucks!

    /** @var string[] */
    private $solutions = []; // store solutions

    /**
     * Constructor.
     *
     * @param string|array $message
     * @param int          $code
     * @param Throwable    $previous
     */
    public function __construct(
        $message = '',
        ?int $code = null,
        Throwable $previous = null
    ) {
        if (is_array($message)) {
            // message contain additional parameters
            $this->params = $message;
            $message = array_shift($this->params);
        }

        parent::__construct($message, $code ?? 0, $previous);
        $this->trace2 = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
    }

    /**
     * Change message (subject) of a current exception. Primary use is
     * for localization purposes.
     *
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Return trace array.
     *
     * @return array
     */
    public function getMyTrace()
    {
        return $this->trace2;
    }

    /**
     * Return exception message using color sequences.
     *
     * <exception name>: <string>
     * <info>
     *
     * trace
     *
     * --
     * <triggered by>
     *
     * @return string
     */
    public function getColorfulText()
    {
        return (string)new Console($this);
    }

    /**
     * Similar to getColorfulText() but will use raw HTML for outputting colors.
     *
     * @return string
     */
    public function getHTMLText()
    {
        return (string)new HTMLText($this);
    }

    /**
     * Return exception message using HTML block and Semantic UI formatting. It's your job
     * to put it inside boilerplate HTML and output, e.g:.
     *
     *   $l = new \atk4\ui\App();
     *   $l->initLayout('Centered');
     *   $l->layout->template->setHTML('Content', $e->getHTML());
     *   $l->run();
     *   exit;
     *
     * @return string
     */
    public function getHTML()
    {
        return (string)new HTML($this);
    }

    /**
     * Return exception in JSON Format.
     *
     * @return string
     */
    public function getJSON(): string
    {
        return (string)new JSON($this);
    }

    /**
     * Safely converts some value to string.
     *
     * @param mixed $val
     *
     * @return string
     */
    public function toString($val): string
    {
        return RendererAbstract::toSafeString($val);
    }

    /**
     * Follow the getter-style of PHP Exception.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Augment existing exception with more info.
     *
     * @param string $param
     * @param mixed  $value
     *
     * @return $this
     */
    public function addMoreInfo($param, $value): self
    {
        $this->params[$param] = $value;

        return $this;
    }

    /**
     * Add a suggested/possible solution to the exception.
     *
     * @TODO can be added more features? usually we are out of App
     *
     * @param string $solution
     *
     * @return Exception
     */
    public function addSolution(string $solution)
    {
        $this->solutions[] = $solution;

        return $this;
    }

    /**
     * Get the solutions array.
     */
    public function getSolutions(): array
    {
        return $this->solutions;
    }

    /**
     * Get the custom Exception name, if defined in $custom_exception_name.
     *
     * @return string
     */
    public function getCustomExceptionName(): string
    {
        return $this->custom_exception_name ?? get_class($this);
    }

    /**
     * Get the custom Exception title, if defined in $custom_exception_title.
     *
     * @return string
     */
    public function getCustomExceptionTitle(): string
    {
        return $this->custom_exception_title;
    }

    /**
     * Translate the Exception using the adapter or Translator.
     *
     * @param ITranslatorAdapter|null $adapter
     */
    public function translate(?ITranslatorAdapter $adapter = null): void
    {
        $this->message = null !== $adapter ? $adapter->_($this->message) : Translator::instance()->_($this->message);
    }
}
