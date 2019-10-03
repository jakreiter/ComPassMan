<?php
namespace App\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(

            new \Twig_SimpleFunction('daysColoredStyleClass', array($this, 'daysColoredStyleClassFunction')),          
            new \Twig_SimpleFunction('diffSymbol', array($this, 'diffSymbolFunction'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('transactionValueMoneyFormat', array($this, 'transactionValueMoneyFormatFunction'), array('is_safe' => array('html'))),
            
        );
    }
        
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('noyes', array($this, 'noyesFunction')),
        	new \Twig_SimpleFilter('trunc', array($this, 'truncFunction')),
        	new \Twig_SimpleFilter('moneyFormat', array($this, 'moneyFormatFunction'), array('is_safe' => array('html'))),
        	new \Twig_SimpleFilter('moneyFormatPlain', array($this, 'moneyFormatPlainFunction')),
        	new \Twig_SimpleFilter('moneyFormatPlain2', array($this, 'moneyFormatPlain2Function')),
        	new \Twig_SimpleFilter('percentFormat', array($this, 'percentFormatFunction'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('isoDate', array($this, 'isoDateFunction')),
            new \Twig_SimpleFilter('emphasizeFragment', array($this, 'emphasizeFragmentFunction'), array('is_safe' => array('html'))),
        );
    }
    
    public function moneyFormatFunction($number, int $precision = 0)
    {
        $formatted = number_format($number, $precision, '.', '<span class="virtualspace"></span>');
        
        if ($number>0) $rHtml='<span class="positiveVal">'.$formatted.'</span>';
        else if ($number<0) $rHtml='<span class="negativeVal">'.$formatted.'</span>';
        else $rHtml='<span class="zeroVal">'.$formatted.'</span>';
        
        return $rHtml;
    }
    
    
    public function emphasizeFragmentFunction($text, $fragment)
    {
        $rHtml = str_replace( $fragment, '<span class="foundFragment">'.$fragment.'</span>', $text );        
        return $rHtml;
    }

    public function moneyFormatPlainFunction($number, int $precision = 0)
    {
        $formatted = number_format($number, $precision, ',', '');
        
        return $formatted;
    }
    
    public function moneyFormatPlain2Function($number, int $precision = 0)
    {
        $formatted = number_format($number, $precision, '.', '');
        
        return $formatted;
    }
    
    public function percentFormatFunction($number)
    {
        $formatted = number_format($number*100, 2, '.', '<span class="virtualspace"></span>');
        
        if ($number>0) $rHtml='<span class="positiveVal">'.$formatted.'%</span>';
        else if ($number<0) $rHtml='<span class="negativeVal">'.$formatted.'%</span>';
        else $rHtml='<span class="zeroVal">'.$formatted.'%</span>';
        
        return $rHtml;
    }
    
    public function noyesFunction($number)
    {
        if ($number) return 'Yes';
        else return 'No';
    }
    
    public function truncFunction($string, $charNumber=50)
    {
    	if (strlen($string)<=$charNumber) return $string;
    	else return substr($string,0,$charNumber).'…';
    }
    
    public function isoDateFunction($date)    
    {
        if (!$date) return null;
        if ('DateTime' != get_class($date)) return null;
    	if ($date < (new \DateTime('0001-01-01'))) return '-';
    	return $date->format('Y-m-d');
    }
    
    public function daysColoredStyleClassFunction($number)
    {

        $bc = 'timing-default';

        if ($number>1)       $bc = 'timing-ok';
        else if ($number>0)  $bc = 'timing-near-deadline';
        else if ($number==0) $bc = 'timing-deadline';
        else if ($number<0)  $bc = 'timing-delayed';
    
        return $bc;
    }

    public function transactionValueMoneyFormatFunction($number, $precautionary=false)
    {
    
        $formatted = number_format($number, 0, '.', '<span class="virtualspace"></span>');
        
        if ($number>0) $rHtml='<span class="positiveVal">'.$formatted.'</span>';
        else if ($number<0 && $precautionary) $rHtml='<span class="negativeValSoft">'.$formatted.'</span>';
        else if ($number<0) $rHtml='<span class="negativeVal">'.$formatted.'</span>';
        else $rHtml='<span class="zeroVal">'.$formatted.'</span>';
        
        return $rHtml;
        
    }
        
    public function diffSymbolFunction($val)
    {
    
        if ($val>0)       $bc = '&gt;';
        else if ($val<0)  $bc = '&lt;';
        else $bc = '=';

    
        return $bc;
    }

    public function getName()
    {
        return 'crm_extension';
    }
}