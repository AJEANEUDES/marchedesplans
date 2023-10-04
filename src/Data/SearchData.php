<?php

namespace App\Data;

use PhpParser\Node\Expr\Cast\String_;

class SearchData
{



  /**
   * Undocumented variable
   *
   * @var integer
   */
  public $page=1;


  
/**
 * Undocumented variable
 *
 * @var string
 */
    public $q = '';


 
    public  $types ;

    public  $formes;

    public  $superficies;



    /**
     * Undocumented variable
     *
     * @var integer
     */
    public int $garage;



    /**
     * Undocumented variable
     *
     * @var integer
     */
    public int  $nbre_piece;






    /**
     * @var null|integer
    */

    public $max;


    /**
     * @var null|integer
    */
    public $min;


    /**
     * Undocumented variable
    *
    * @var boolean
    */
    /* public $promo = false; */

}