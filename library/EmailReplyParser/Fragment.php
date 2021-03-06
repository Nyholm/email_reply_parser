<?php
namespace EmailReplyParser;

/**
 * Represents a group of paragraphs in the email sharing common attributes.
 * Paragraphs should get their own fragment if they are a quoted area or a
 * signature.
 */
class Fragment
{
/**
 * Lines in this Fragment.
 *
 * @param array
 */
    public $lines = array();

/**
 * Determines if this Fragment should be hidden from users.
 *
 * @param boolean
 */
    public $hidden = false;

/**
 * Determines if this Fragment is a signature.
 *
 * @param boolean
 */
    public $signature = false;

/**
 * Determines if this Fragment is a quote.
 *
 * @param boolean
 */
    public $quoted = false;

/**
 * Determines the encoding to use when reversing this Fragment.
 *
 * @param string
 */
    public $encoding = null;

/**
 * This is reserved for the joined String that is build when this Fragment is finished.
 *
 * @param string
 */
    public $content;

/**
 * Store the first line and marks the Fragment as quoted, if it is.
 *
 * @param boolean $isQuoted Whether if the line if quoted or not.
 * @param string $firstLine A line of text from the email.
 * @param string $encoding Optional encoding to use when reversing this Fragment.
 */
    public function __construct($isQuoted, $firstLine, $encoding = null)
    {
        $this->quoted = $isQuoted;
        $this->lines[] = $firstLine;
        $this->encoding = $encoding;
    }

/**
 * Builds the string content by joining the lines and reversing them.
 *
 * @return void
 */
    public function finish()
    {
        $this->content = implode("\n", $this->lines);
        $this->content = self::reverse($this->content, $this->encoding);
        unset($this->lines);
    }

/**
 * Get the last line of this Fragment.
 *
 * @return string Last line of this Fragment.
 */
    public function getLastLine()
    {
        $count = count($this->lines);

        return $this->lines[$count - 1];
    }

/**
 * Utility method to reverse a text string.
 *
 * @param string $text Text to be reversed.
 * @param string $encoding Optional encoding to use when reversing the text.
 * @return string Reversed text.
 */
    public static function reverse($text, $encoding = null)
    {
        if (empty($encoding)) {
            $encoding = mb_detect_encoding($text);
        }
        $text = mb_convert_encoding($text, 'UTF-32BE', $encoding);
        $text = mb_convert_encoding(strrev($text), $encoding, 'UTF-32LE');

        return $text;
    }
}
