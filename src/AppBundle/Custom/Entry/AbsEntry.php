<?php
// +----------------------------------------------------------------------
// | Author: jdmake <503425061@qq.com>
// +----------------------------------------------------------------------
// | Date: 2019/8/4
// +----------------------------------------------------------------------


namespace AppBundle\Custom\Entry;


class AbsEntry implements \JsonSerializable, \ArrayAccess
{
    /**
     * 返回JSON格式
     * @return array|mixed
     * @throws \ReflectionException
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * 转为Array格式
     * @throws \ReflectionException
     */
    protected function toArray() {
        $results = [];
        $ref = new \ReflectionClass($this);
        foreach ($ref->getProperties() as $property) {
            $method = "get" . ucfirst($property->getName());
            if(method_exists($this, $method)) {
                $results[$property->getName()] = $this->$method();
            }
        }
        return $results;
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        $method = 'get' . ucfirst($offset);
        if(method_exists($this, $method)) {
            return $this->$method();
        }
        return null;
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}