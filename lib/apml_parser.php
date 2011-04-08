<?php
class APML_Parser
{
  // APML Parser Class version 1.0
  // By Jon Cianciullo - Founder of Cluztr.com
  // Email: jon.cianciullo@gmail.com
  // Based on the IAM_OPML_Parser class 
  // Ref: http://www.phpclasses.org/browse/file/19995.html
     var $parser;
     var $data;
     var $index = 0;

  // Outline items we wish to map and their mapping names:  'attribute_name' => 'var_name'
  var $apml_map_vars = array('KEY' => 'concept_key',
    'VALUE' => 'value', 
    'UPDATED' => 'updated', 
    'FROM' => 'from');
  
  /**
   * Fetch Contents of Page (from URL).
   *
   * @param string $url
   * @return string contents of the page at $url
   */
  function getContent($url='')
  {
    $ch   = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // timeout after n secs (prevent large files!)
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }

     function ParseElementStart($parser, $tagName, $attrs)
     { 
    $map = $this->apml_map_vars;
    if ($tagName == 'CONCEPT')
    {
          foreach (array_keys($this->apml_map_vars) as $key)
      {
                 if (isset($attrs[$key]))
        {
                      $$map[$key] = $attrs[$key];
                 }
            }
            // save the data away.
            $this->data[$this->index]['concept_key'] = $concept_key;
            $this->data[$this->index]['value'] = (float) $value; // cast to float
            $this->data[$this->index]['updated'] = $updated;
            $this->data[$this->index]['from'] = $from;
            $this->index++;
       } // end if outline
     }

  function ParseElementEnd($parser, $name)
     {
       // nothing to do.
     }

  function ParseElementCharData($parser, $name)
     {
       // nothing to do.
     }

     function Parse($XMLdata)
     {
          $this->parser = xml_parser_create();
          xml_set_object($this->parser, $this);

          xml_set_element_handler($this->parser,
               array(&$this, 'ParseElementStart'),
               array(&$this, 'ParseElementEnd'));

    xml_set_character_data_handler($this->parser,
      array(&$this, 'ParseElementCharData'));

          xml_parse($this->parser, $XMLdata);

          xml_parser_free($this->parser);

     }

     function getFeeds( $apml_url )
     {
    $this->index = 0;
    $this->Parse($this->getContent($apml_url));
    $this->index = 0;
    return $this->data;
  }

  function getAPMLConcepts( $apml_url )
  {
    return $this->getFeeds($apml_url);
  }
}
?>