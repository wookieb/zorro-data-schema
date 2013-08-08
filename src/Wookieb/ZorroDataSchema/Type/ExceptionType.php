<?php

namespace Wookieb\ZorroDataSchema\Type;
use Wookieb\ZorroDataSchema\Exception\InvalidValueException;


/**
 * @author Łukasz Kużyński "wookieb" <lukasz.kuzynski@gmail.com>
 */
class ExceptionType extends AbstractClassType
{

    protected $class = '\Exception';
    /**
     * @var StringType
     */
    private $stringType;

    public function __construct()
    {
        parent::__construct('Exception');
        $this->stringType = new StringType();
    }

    /**
     * Creates local representation of given data
     *
     * @param mixed $data
     * @return mixed
     * @throws InvalidValueException
     */
    public function create($data)
    {
        if ($data instanceof $this->class) {
            return $data;
        }

        if (!is_array($data) && !$data instanceof \stdClass) {
            $msg = vsprintf('Invalid data to create object of instance %s. Only array and \stdClass allowed',
                array($this->class));
            throw new InvalidValueException($msg);
        }
        if ($data instanceof \stdClass) {
            $data = (array)$data;
        }
        $object = new \Exception(@$data['message']);
        $this->setProperties($object, $data);
        return $object;
    }

    protected function setProperties($object, array $data, array $skipProperties = array())
    {
        if (!in_array('message', $skipProperties)) {
            $value = isset($data['message']) ? $data['message'] : null;
            $this->getReflectionProperty('message')->setValue($object, $this->stringType->create($value));
        }

        if (!in_array('cause', $skipProperties)) {

            $this->getReflectionProperty('previous')
                ->setValue($object, isset($data['cause']) ? $this->create($data['cause']) : null);



        }
    }

    /**
     * {@inheritDoc}
     */
    public function extract($value)
    {
        if (!$this->isTargetType($value)) {
            throw new InvalidValueException('Value to extract must be an object of class '.$this->class);
        }

        $data = array();
        $this->extractProperties($value, $data);
        return $data;
    }

    protected function extractProperties($object, &$data, array $skipProperties = array())
    {
        /** @var \Exception $object */
        if (!in_array('message', $skipProperties)) {
            $data['message'] = $this->stringType->extract($object->getMessage());
        }
        if (!in_array('cause', $skipProperties)) {
            if ($object->getPrevious()) {
                $data['cause'] = $this->extract($object->getPrevious());
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isTargetType($value)
    {
        return $value instanceof \Exception;
    }
}