<?php

defined('ABSPATH') or die("...");

/**
 * A class to hold the plugin settings
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */
class LiddMCOptions
{
	/**
	 * @var     array   options     Saved options
     * @since   2.0.0
	 */
	private $options;
    
    /**
     * @var     array   defaults    Default options
     * @since   2.2.7
     */
    private $defaults;
	
	/**
	 * Get the settings from the database and store in the object.
	 */
	public function __construct()
	{
		$this->options  = get_option( LIDD_MC_OPTIONS );
        $this->defaults = include LIDD_MC_ROOT . 'includes/defaults.php';
	}
	
	/**
	 * Return an option value.
	 *
	 * @param  string           $option  The name of the plugin option.
	 * @return string|int|null           The value of the option.
	 */
	public function getOption( $option )
	{
		if ( isset( $this->options[$option] ) ) {
			return $this->options[$option];
		}
        
        if ( isset( $this->defaults[$option] ) ) {
            return $this->defaults[$option];
        }
        
        return null;
	}
	
}
