<?php

/**
 * Undocumented class
 */
class View{

	public $html;

	/**
	 * [__construct description]
	 * @param   Array  $data    soit un tableau associatif, soit tableau simple
	 * @param   String $template [description]
	 * @return  void
	 */
	public function __construct($data, $template){
	    switch (isset($data[0])) {
	      case true:
	        $this->html = $this->mergeSeveralWithTemplate($data, $template);
	        break;
	      
	      default:
	        $this->html = $this->mergeWithTemplate($data, $template);
	        break;
	   };
  }

	public function __toString(){
		return $this->html;
	}
	  
  	/**
	   * Undocumented function
	   *
	   * @param [type] $args
	   * @param [type] $template
	   * @return void
	   */
    private function mergeWithTemplate($args, $template){

    	global $config;
    	$args["{{ path }}"] = $config["path"];

	    return str_replace(
	      array_keys($args),
	      $args,
	      file_get_contents("./templates/$template.html")
	    );
	  }
	
	/**
	 * Undocumented function
	 *
	 * @param [type] $template
	 * @param [type] $data
	 * @return void
	 */
	private function mergeSeveralWithTemplate($data, $template){
	    $html = "";
	    foreach ($data as $value) {
	      $html .= "\n".$this->mergeWithTemplate($value, $template);
	    }
	    return $html;
	  }
}