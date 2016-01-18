<?php
// PHP topological sort function
// Author: Dan (http://www.calcatraz.com)
// Licensing: None - use it as you see fit
// Updates: http://blog.calcatraz.com/php-topological-sort-function-384
//
// Args:
// $nodeids - an array of node ids,
// e.g. array('paris', 'milan', 'vienna', ...);
// $edges - an array of directed edges,
// e.g. array(array('paris','milan'),
// array('milan', 'vienna'),
// ...)
// Returns:
// topologically sorted array of node ids, or NULL if graph is
// unsortable (i.e. contains cycles)

if (!function_exists('topological_sort')) {
    function topological_sort($nodeids, $edges)
    {
        
        // initialize variables
        $L = $S = $nodes = array();
        
        // remove duplicate nodes
        $nodeids = array_unique($nodeids);
        
        // remove duplicate edges
        $hashes = array();
        foreach ($edges as $k => $e) {
            $hash = md5(serialize($e));
            if (in_array($hash, $hashes)) {
                unset($edges[$k]);
            } else {
                $hashes[] = $hash;
            }
            ;
        }
        
        // Build a lookup table of each node's edges
        foreach ($nodeids as $id) {
            $nodes[$id] = array(
                'in' => array(),
                'out' => array()
            );
            foreach ($edges as $e) {
                if ($id == $e[0]) {
                    $nodes[$id]['out'][] = $e[1];
                }
                if ($id == $e[1]) {
                    $nodes[$id]['in'][] = $e[0];
                }
            }
        }
        
        // While we have nodes left, we pick a node with no inbound edges,
        // remove it and its edges from the graph, and add it to the end
        // of the sorted list.
        foreach ($nodes as $id => $n) {
            if (empty($n['in']))
                $S[] = $id;
        }
        while (! empty($S)) {
            $L[] = $id = array_shift($S);
            foreach ($nodes[$id]['out'] as $m) {
                $nodes[$m]['in'] = array_diff($nodes[$m]['in'], array(
                    $id
                ));
                if (empty($nodes[$m]['in'])) {
                    $S[] = $m;
                }
            }
            $nodes[$id]['out'] = array();
        }
        
        // Check if we have any edges left unprocessed
        foreach ($nodes as $n) {
            if (! empty($n['in']) or ! empty($n['out'])) {
                return null; // not sortable as graph is cyclic
            }
        }
        return $L;
    }
}
