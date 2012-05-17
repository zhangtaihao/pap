<?php
/**
 * @file
 * Manifest processing for providing consumable application information.
 */

/**
 * Application manifest meta-producer for creating consumable file-based
 * package metadata.
 */
class ApplicationManifests implements ApplicationInformationConsumable {
  /**
   * Manifest scanner for the application.
   * @var ManifestScanner
   */
  protected $scanner;

  /**
   * Creates an application-wide manifest source.
   */
  public function __construct() {
    $this->setUp();
  }

  /**
   * Sets up the consumable for later processing.
   */
  protected function setUp() {
    $this->scanner = $this->createScanner();
  }

  /**
   * Creates a manifest scanner for the application.
   */
  protected function createScanner() {
    // Construct a scanner for the application root.
    $root = new DirectoryIterator(APP_ROOT);
    return new ManifestScanner($root);
  }

  /**
   * Processes produced information with a consumer.
   *
   * @param ApplicationInformationConsumer $consumer
   */
  public function process(ApplicationInformationConsumer $consumer) {
    $this->scanner->process($consumer);
  }
}

/**
 * Manifest meta-producer traversing a directory for manifest files.
 */
class ManifestScanner implements ApplicationInformationConsumable {
  /**
   * Name of the manifest file.
   */
  const MANIFEST_FILENAME = 'manifest.xml';

  /**
   * Root directory handle for scanning manifests.
   * @var DirectoryIterator
   */
  protected $root;

  /**
   * File-based package manifest readers.
   * @var ManifestReader[]
   */
  protected $readers;

  /**
   * Constructs a manifest scanner for a directory.
   *
   * @param DirectoryIterator $root
   *   A traversable directory.
   */
  public function __construct($root) {
    $this->root = $root;
    $this->readers = array();
    $this->setUp();
  }

  /**
   * Sets up the scanner.
   */
  protected function setUp() {
    if (isset($this->root)) {
      // Create manifest file readers.
      foreach ($this->scanManifests($this->root) as $file) {
        $this->readers[] = new ManifestReader($file);
      }
    }
  }

  /**
   * Scans a directory for manifest files.
   *
   * @param DirectoryIterator $dir
   *   Base directory to scan.
   * @return SplFileObject[]
   *   Array of manifest file objects.
   */
  protected function scanManifests($dir) {
    $files = array();
    // Check for manifest in the given directory.
    if ($dir->isReadable()) {
      /** @var $file SplFileObject */
      foreach ($dir as $file) {
        if ($file->isReadable()) {
          // Perform recursion into subdirectory.
          if ($file->isDir()) {
            $files = array_merge($files, $this->scanManifests($dir));
          }
          // Add manifest file.
          elseif ($file->isFile() && $file->getFilename() == self::MANIFEST_FILENAME) {
            $files[] = $file->openFile();
          }
        }
      }
    }
    return $files;
  }

  /**
   * Processes produced information with a consumer.
   *
   * @param ApplicationInformationConsumer $consumer
   */
  public function process(ApplicationInformationConsumer $consumer) {
    foreach ($this->readers as $reader) {
      $reader->process($consumer);
    }
  }
}

/**
 * Consumable manifest file readers.
 */
class ManifestReader implements ApplicationInformationConsumable {
  /**
   * Parser instance.
   * @var ManifestParser
   */
  private static $parser;

  /**
   * Gets an instance of a manifest parser.
   *
   * @return ManifestParser
   *   Parser object.
   */
  final public static function getParser() {
    if (!isset(self::$parser)) {
      self::$parser = new ManifestParser();
    }
    return self::$parser;
  }

  /**
   * Manifest file object.
   * @var SplFileObject
   */
  protected $file;

  /**
   * Manifest data.
   * @var mixed
   */
  protected $data;

  /**
   * Creates a manifest reader for a file.
   *
   * @param SplFileObject $file
   *   Manifest file object.
   */
  public function __construct($file) {
    $this->file = $file;
  }

  /**
   * Prepares manifest metadata for processing.
   */
  protected function prepareData() {
    if (!isset($this->data)) {
      $this->data = $this->readIntoArray();
      // Mark data as unreadable for future attempts.
      if (empty($this->data)) {
        $this->data = FALSE;
      }
    }
  }

  /**
   * Reads the manifest file into an array structure.
   *
   * @return array|NULL
   *   Array structure suitable for consumers, or NULL if parsing failed.
   */
  protected function readIntoArray() {
    $parser = self::getParser();
    // Parse and normalize NULL results.
    return $parser->parse($this->file);
  }

  /**
   * Processes manifest metadata with a consumer.
   *
   * @param ApplicationInformationConsumer $consumer
   *   Metadata consumer.
   */
  public function process(ApplicationInformationConsumer $consumer) {
    $this->prepareData();
    if (!empty($this->data)) {
      $consumer->consume($this->data);
    }
  }
}

/**
 * Parser for a manifest file.
 */
class ManifestParser {
  /**
   * Root manifest element name.
   */
  const ROOT_ELEMENT = 'manifest';

  /**
   * XML parser instance.
   * @var resource
   */
  protected $parser;

  /**
   * Temporary parsed manifest data.
   * @var array
   */
  protected $manifest;

  /**
   * Constructs a new manifest parser.
   */
  public function __construct() {
    $this->parser = xml_parser_create();
    $this->setUp();
  }

  /**
   * Sets up the parser.
   */
  protected function setUp() {
    xml_set_object($this->parser, $this);
    xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, FALSE);
    xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, TRUE);
    xml_set_element_handler($this->parser, 'startElement', 'endElement');
    xml_set_character_data_handler($this->parser, 'elementData');
  }

  /**
   * Parses a manifest file.
   *
   * @param SplFileObject $file
   *   Open file to parse.
   * @return array|null
   *   Manifest data, or NULL if parsing failed.
   */
  public function parse(SplFileObject $file) {
    $this->reset();
    foreach ($file as $line) {
      xml_parse($this->parser, $line);
    }
    return $this->getData();
  }

  /**
   * Resets the parser.
   */
  protected function reset() {
    $this->manifest = NULL;
  }

  /**
   * Gets the parsed manifest data.
   *
   * @return array|null
   *   Manifest data, or NULL if none parsed.
   */
  protected function getData() {
    return $this->manifest;
  }

  /**
   * Starts an XML element.
   */
  public function startElement($parser, $element, array $attributes) {
    // Initialize manifest data on encountering <manifest>.
    if ($element == self::ROOT_ELEMENT) {
      $this->manifest = array();
    }

    // TODO
  }

  /**
   * Captures element data.
   */
  public function elementData($parser, $data) {
    // TODO
  }

  /**
   * Ends an XML element.
   */
  public function endElement($parser, $element, array $attributes) {
    // TODO
  }

  /**
   * Destructs the object.
   */
  public function __destruct() {
    if (isset($this->parser)) {
      xml_parser_free($this->parser);
    }
  }
}
