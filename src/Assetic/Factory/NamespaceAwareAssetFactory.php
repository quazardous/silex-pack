<?php
namespace Quazardous\Assetic\Factory;

use Assetic\Factory\AssetFactory;

/**
 * 
 * @author david
 *
 */
class NamespaceAwareAssetFactory extends AssetFactory
{
    protected $namespaces = [];
    
    /**
     * Add a namespace with its path.
     * @param string $ns
     * @param string $path
     */
    public function addNamespace($ns, $path) {
        $this->namespaces[$ns] = $path;
    }
    
    /**
     * Will detect inputs that begin with @MyNamespace/... and replace the namespace with the corresponding path.
     *
     * @see \Assetic\Factory\AssetFactory::parseInput()
     */
    protected function parseInput($input, array $options = array())
    {
        $matches = null;
        // search for @MyNamespace/path/to/asset
        if (preg_match("|^\@([a-z_][_a-z0-9]*)/|i", $input, $matches)) {
            $ns = $matches[1];
            if (!array_key_exists($ns, $this->namespaces)) {
                throw new \RuntimeException("$ns : unknown namespace !");
            }
            $input = $this->namespaces[$ns] . substr($input, strlen($ns) + 1);
        }
        return parent::parseInput($input, $options);
    }

}
