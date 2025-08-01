<?php

class Meow_MWAI_Query_DroppedFile {
  private $data;
  private $rawData;
  private $type; // Defines what the data is about ('refId', 'url', or 'data')
  private $purpose; // Can be 'assistant', 'vision' or 'files' => this needs to be checked
  private $mimeType; // 'image/jpeg' or any other mime type
  private $fileId; // The ID of the file in the database
  public $originalPath; // The original file path (for files loaded from disk)

  public static function from_url( $url, $purpose, $mimeType = null, $fileId = null ) {
    if ( empty( $mimeType ) ) {
      $mimeType = Meow_MWAI_Core::get_mime_type( $url );
    }
    return new Meow_MWAI_Query_DroppedFile( $url, 'url', $purpose, $mimeType, $fileId );
  }

  public static function from_data( $data, $purpose, $mimeType = null ) {
    return new Meow_MWAI_Query_DroppedFile( $data, 'data', $purpose, $mimeType );
  }

  public static function from_path( $path, $purpose, $mimeType = null ) {
    $data = file_get_contents( $path );
    if ( empty( $mimeType ) ) {
      $mimeType = Meow_MWAI_Core::get_mime_type( $path );
    }
    $droppedFile = new Meow_MWAI_Query_DroppedFile( $data, 'data', $purpose, $mimeType );
    // Store the original path for filename extraction
    $droppedFile->originalPath = $path;
    return $droppedFile;
  }

  public function __construct( $data, $type, $purpose, $mimeType = null, $fileId = null ) {
    if ( !empty( $type ) && $type !== 'refId' && $type !== 'url' && $type !== 'data' ) {
      throw new Exception( 'AI Engine: The file type can only be refId, url or data.' );
    }
    if ( !empty( $purpose ) && $purpose !== 'assistant-in' && $purpose !== 'vision' && $purpose !== 'files' ) {
      throw new Exception( 'AI Engine: The file purpose can only be assistant, vision or files.' );
    }
    $this->data = $data;
    $this->type = $type;
    $this->purpose = $purpose;
    $this->mimeType = $mimeType;
    $this->fileId = $fileId;
  }

  public function get_url() {
    if ( $this->type === 'url' ) {
      return $this->data;
    }
    throw new Exception( 'AI Engine: The file is not an URL.' );
  }

  private function get_raw_data() {
    if ( !empty( $this->rawData ) ) {
      return $this->rawData;
    }
    if ( $this->type === 'url' ) {
      // Validate URL scheme to prevent SSRF attacks
      $parts = wp_parse_url( $this->data );
      if ( ! isset( $parts['scheme'] ) || ! in_array( $parts['scheme'], [ 'http', 'https' ], true ) ) {
        throw new Exception( 'Invalid URL scheme; only HTTP/HTTPS allowed.' );
      }
      
      $this->rawData = file_get_contents( $this->data );
      return $this->rawData;
    }
    else if ( $this->type === 'data' ) {
      return $this->data;
    }
    throw new Exception( 'AI Engine: The file is not data or an URL.' );
  }

  public function get_data() {
    if ( $this->type === 'url' ) {
      return $this->get_raw_data();
    }
    else if ( $this->type === 'data' ) {
      return $this->data;
    }
    throw new Exception( 'AI Engine: The file is not data or an URL.' );
  }

  public function get_base64() {
    $data = $this->get_raw_data();
    return base64_encode( $data );
  }

  // Will return something like "data:image/jpeg;base64,{data}"
  public function get_inline_base64_url() {
    $b64 = $this->get_base64();
    return "data:{$this->mimeType};base64,{$b64}";
  }

  public function get_type() {
    return $this->type;
  }

  public function get_purpose() {
    return $this->purpose;
  }

  public function get_mimeType() {
    return $this->mimeType;
  }

  public function is_image() {
    return strpos( $this->mimeType, 'image' ) !== false;
  }

  public function get_fileId() {
    return $this->fileId;
  }

  // Return a filename for this file. If the file is an URL, use the basename of
  // its path. If the file is raw data, generate a generic name based on the mime type.
  public function get_filename() {
    // If we have an original path (from from_path), use its basename
    if ( !empty( $this->originalPath ) ) {
      return basename( $this->originalPath );
    }
    if ( $this->type === 'url' ) {
      $path = parse_url( $this->data, PHP_URL_PATH );
      return basename( $path );
    }
    if ( $this->type === 'data' ) {
      if ( !empty( $this->mimeType ) ) {
        $parts = explode( '/', $this->mimeType );
        $ext = end( $parts );
        return 'file.' . $ext;
      }
      return 'file.bin';
    }
    return 'file';
  }
}
