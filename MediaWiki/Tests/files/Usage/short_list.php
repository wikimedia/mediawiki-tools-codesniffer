<?php

list( $a, $b ) = [ 1, 2 ];
list  ( , $b, ) = [ 1, 2, 3 ];
list( 'one' => $a, 'two' => $b ) = [ 'one' => 1, 'two' => 2 ];
list( , $one, , $three ) = [ 3 => 'three', 2 => 'two', 1 => 'one', 0 => 'zero' ];
list( 1 => $second, 3 => $fourth ) = [ 1, 2, 3, 4 ];
