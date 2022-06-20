<?php
namespace PhpArsenal\SoapClient\Soap;

use PhpArsenal\SoapClient\Soap\TypeConverter;

/**
 * Factory to create a \SoapClient properly configured for the Salesforce SOAP
 * client
 */
class SoapClientFactory
{
    /**
     * Default classmap
     *
     * @var array
     */
    protected $classmap = array(
        'ChildRelationship'     => 'PhpArsenal\SoapClient\Result\ChildRelationship',
        'DeleteResult'          => 'PhpArsenal\SoapClient\Result\DeleteResult',
        'DeletedRecord'         => 'PhpArsenal\SoapClient\Result\DeletedRecord',
        'DescribeGlobalResult'  => 'PhpArsenal\SoapClient\Result\DescribeGlobalResult',
        'DescribeGlobalSObjectResult' => 'PhpArsenal\SoapClient\Result\DescribeGlobalSObjectResult',
        'DescribeSObjectResult' => 'PhpArsenal\SoapClient\Result\DescribeSObjectResult',
        'DescribeTab'           => 'PhpArsenal\SoapClient\Result\DescribeTab',
        'EmptyRecycleBinResult' => 'PhpArsenal\SoapClient\Result\EmptyRecycleBinResult',
        'Error'                 => 'PhpArsenal\SoapClient\Result\Error',
        'Field'                 => 'PhpArsenal\SoapClient\Result\DescribeSObjectResult\Field',
        'GetDeletedResult'      => 'PhpArsenal\SoapClient\Result\GetDeletedResult',
        'GetServerTimestampResult' => 'PhpArsenal\SoapClient\Result\GetServerTimestampResult',
        'GetUpdatedResult'      => 'PhpArsenal\SoapClient\Result\GetUpdatedResult',
        'GetUserInfoResult'     => 'PhpArsenal\SoapClient\Result\GetUserInfoResult',
        'LeadConvert'           => 'PhpArsenal\SoapClient\Request\LeadConvert',
        'LeadConvertResult'     => 'PhpArsenal\SoapClient\Result\LeadConvertResult',
        'LoginResult'           => 'PhpArsenal\SoapClient\Result\LoginResult',
        'MergeResult'           => 'PhpArsenal\SoapClient\Result\MergeResult',
        'QueryResult'           => 'PhpArsenal\SoapClient\Result\QueryResult',
        'SaveResult'            => 'PhpArsenal\SoapClient\Result\SaveResult',
        'SearchResult'          => 'PhpArsenal\SoapClient\Result\SearchResult',
        'SendEmailError'        => 'PhpArsenal\SoapClient\Result\SendEmailError',
        'SendEmailResult'       => 'PhpArsenal\SoapClient\Result\SendEmailResult',
        'SingleEmailMessage'    => 'PhpArsenal\SoapClient\Request\SingleEmailMessage',
        'sObject'               => 'PhpArsenal\SoapClient\Result\SObject',
        'UndeleteResult'        => 'PhpArsenal\SoapClient\Result\UndeleteResult',
        'UpsertResult'          => 'PhpArsenal\SoapClient\Result\UpsertResult',
    );

    /**
     * Type converters collection
     *
     * @var TypeConverter\TypeConverterCollection
     */
    protected $typeConverters;

    /**
     * @param string $wsdl Path to WSDL file
     * @param array $soapOptions
     * @param string $environment
     * @return SoapClient
     */
    public function factory($wsdl, array $soapOptions = array(), $environment = 'prod')
    {
        $defaults = array(
            'trace'      => 1,
            'features'   => \SOAP_SINGLE_ELEMENT_ARRAYS,
            'classmap'   => $this->classmap,
            'typemap'    => $this->getTypeConverters()->getTypemap(),
            'cache_wsdl' => $environment == 'dev' ? \WSDL_CACHE_NONE : \WSDL_CACHE_MEMORY
        );

        $options = array_merge($defaults, $soapOptions);

        return new SoapClient($wsdl, $options);
    }

    /**
     * test
     *
     * @param string $soap SOAP class
     * @param string $php  PHP class
     */
    public function setClassmapping($soap, $php)
    {
        $this->classmap[$soap] = $php;
    }

    /**
     * Get type converter collection that will be used for the \SoapClient
     *
     * @return TypeConverter\TypeConverterCollection
     */
    public function getTypeConverters()
    {
        if (null === $this->typeConverters) {
            $this->typeConverters = new TypeConverter\TypeConverterCollection(
                array(
                    new TypeConverter\DateTimeTypeConverter(),
                    new TypeConverter\DateTypeConverter()
                )
            );
        }

        return $this->typeConverters;
    }

    /**
     * Set type converter collection
     *
     * @param TypeConverter\TypeConverterCollection $typeConverters Type converter collection
     *
     * @return SoapClientFactory
     */
    public function setTypeConverters(TypeConverter\TypeConverterCollection $typeConverters)
    {
        $this->typeConverters = $typeConverters;

        return $this;
    }
}
