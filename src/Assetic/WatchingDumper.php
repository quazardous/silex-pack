<?php

namespace Quazardous\Assetic;

use Assetic\Factory\LazyAssetManager;
use Assetic\AssetWriter;
use Assetic\AssetManager;
use SilexAssetic\Assetic\Dumper as BaseDumper;
use Assetic\Util\VarUtils;
use Symfony\Component\Console\Output\OutputInterface;

class WatchingDumper extends BaseDumper
{

    protected $watching = false;
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $commandOutput = null;
    
    /**
     * Start watch mode for command assetic:watch.
     * @param OutputInterface $output
     */
    public function watch(OutputInterface $output = null) {
        $this->watching = true;
        $this->commandOutput = $output;
    }
    
    protected $pathToWeb = null;
    public function __construct(AssetManager $am, LazyAssetManager $lam, AssetWriter $writer, $pathToWeb)
    {
        parent::__construct($am, $lam, $writer);
        $this->pathToWeb = $pathToWeb;
    }
    
    protected function dumpManagerAssets(AssetManager $am)
    {
        foreach ($am->getNames() as $name) {
            $asset = $am->get($name);

            if ($am instanceof LazyAssetManager) {
                $formula = $am->getFormula($name);
            }
            
            $dump = true;
            if ($this->watching) {
                // watching mode
                $dump = false;
                $resolved = VarUtils::resolve(
                    $asset->getTargetPath(),
                    $asset->getVars(),
                    $asset->getValues()
                    );
                $dest = $this->pathToWeb . '/' . $resolved;
                if (file_exists($dest)) {
                    $destmtime = filemtime($dest);
                } else {
                    $destmtime = 0;
                }
                // compare source and destination mtime
                if ($asset->getLastModified() > $destmtime) {
                    if ($this->commandOutput) {
                        $this->commandOutput->writeln("Dumping $dest");
                    }
                    $dump = true;
                }
            }
            if ($dump) {
                $this->writer->writeAsset($asset);
    
                if (!isset($formula[2])) {
                    continue;
                }
    
                $debug   = isset($formula[2]['debug'])   ? $formula[2]['debug']   : $am->isDebug();
                $combine = isset($formula[2]['combine']) ? $formula[2]['combine'] : null;
    
                if (null !== $combine ? !$combine : $debug) {
                    foreach ($asset as $leaf) {
                        
                        $this->writer->writeAsset($leaf);
                    }
                }
            }
        }
    }
}
